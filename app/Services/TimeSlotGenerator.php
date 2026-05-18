<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\TimeSlot;

class TimeSlotGenerator
{
    /**
     * Create slots for the next 30 days for any doctor who has none yet.
     */
    public static function ensureUpcomingSlotsExist(): void
    {
        Doctor::query()->each(fn (Doctor $doctor) => self::ensureDoctorHasUpcomingSlots($doctor));
    }

    public static function ensureDoctorHasUpcomingSlots(Doctor $doctor): void
    {
        if (TimeSlot::query()
            ->where('doctor_id', $doctor->id)
            ->where('starts_at', '>', now())
            ->exists()) {
            return;
        }

        $now = now();

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
