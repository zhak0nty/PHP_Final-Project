<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\TimeSlot;

class TimeSlotGenerator
{
    /**
     * Если в БД нет ни одного будущего слота (старый сид / пустая база),
     * создаёт слоты на 30 дней вперёд для всех врачей.
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
