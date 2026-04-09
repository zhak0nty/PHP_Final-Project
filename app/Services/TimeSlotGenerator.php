<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\TimeSlot;

class TimeSlotGenerator
{
    /**
     * If there are no future slots in the database (old seed / empty DB),
     * create slots for the next 30 days for all doctors.
     */
    public static function ensureUpcomingSlotsExist(): void
    {
        if (TimeSlot::query()->where('starts_at', '>', now())->exists()) {
            return;
        }

        $now = now();
        $doctors = Doctor::query()->get();

        foreach ($doctors as $doctor) {
            foreach (range(1, 30) as $dayOffset) {
                foreach ([9, 10, 11, 14, 15] as $hour) {
                    $start = $now->copy()->addDays($dayOffset)->setTime($hour, 0);
                    if ($start->lte($now)) {
                        continue;
                    }
                    $end = $start->copy()->addMinutes(30);

                    TimeSlot::firstOrCreate(
                        [
                            'doctor_id' => $doctor->id,
                            'starts_at' => $start,
                        ],
                        [
                            'ends_at' => $end,
                        ]
                    );
                }
            }
        }
    }
}
