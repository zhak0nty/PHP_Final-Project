<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use App\Services\TimeSlotGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
        ]);

        $services = collect([
            ['name' => 'Consultation', 'duration_minutes' => 30],
            ['name' => 'First visit', 'duration_minutes' => 45],
            ['name' => 'Follow-up', 'duration_minutes' => 30],
            ['name' => 'Diagnostics', 'duration_minutes' => 60],
            ['name' => 'Ultrasound', 'duration_minutes' => 45],
        ])->map(fn (array $data) => Service::create([
            'name' => $data['name'],
            'duration_minutes' => $data['duration_minutes'],
        ]));

        $doctorsData = [
            ['name' => 'James Wilson', 'specialization' => 'Urologist'],
            ['name' => 'Sarah Miller', 'specialization' => 'Therapist'],
            ['name' => 'Robert Chen', 'specialization' => 'Endoscopist'],
            ['name' => 'Emily Davis', 'specialization' => 'Neurologist'],
            ['name' => 'Lisa Anderson', 'specialization' => 'Gynecologist'],
            ['name' => 'Michael Brown', 'specialization' => 'ENT / Otolaryngologist'],
            ['name' => 'David Lee', 'specialization' => 'Traumatologist'],
            ['name' => 'Anna Garcia', 'specialization' => 'Dermatologist'],
            ['name' => 'Thomas White', 'specialization' => 'General Practitioner (GP)'],
            ['name' => 'Maria Martinez', 'specialization' => 'Pediatrician'],
            ['name' => 'Paul Johnson', 'specialization' => 'Neurologist'],
            ['name' => 'Chris Taylor', 'specialization' => 'Ophthalmologist'],
            ['name' => 'Jennifer Moore', 'specialization' => 'Cardiologist'],
            ['name' => 'Kevin Harris', 'specialization' => 'Endocrinologist'],
            ['name' => 'Alex Reed', 'specialization' => 'Diagnostic Imaging (Ultrasound, CT, MRI)'],
        ];

        $doctors = collect($doctorsData)->map(function (array $data, int $i) use ($services) {
            $user = User::factory()->create([
                'name' => $data['name'],
                'email' => 'doctor'.($i + 1).'@example.com',
                'role' => User::ROLE_DOCTOR,
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization' => $data['specialization'],
            ]);

            $doctor->services()->sync($services->random(min(3, $services->count()))->pluck('id')->all());

            return $doctor;
        });

        $client = User::factory()->create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'role' => User::ROLE_CLIENT,
        ]);

        $demoDoctorUser = User::factory()->create([
            'name' => 'Dr. Anna Demo',
            'email' => 'dr.demo@medbooking.local',
            'password' => 'doctor123',
            'role' => User::ROLE_DOCTOR,
        ]);

        $demoDoctor = Doctor::create([
            'user_id' => $demoDoctorUser->id,
            'specialization' => 'Therapist',
            'bio' => 'Demo doctor account for testing the doctor dashboard and appointments.',
        ]);

        $demoDoctor->services()->sync($services->take(3)->pluck('id')->all());

        TimeSlotGenerator::ensureUpcomingSlotsExist();

    }
}

