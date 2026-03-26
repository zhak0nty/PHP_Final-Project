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
            ['name' => 'Консультация', 'duration_minutes' => 30],
            ['name' => 'Первичный приём', 'duration_minutes' => 45],
            ['name' => 'Повторный приём', 'duration_minutes' => 30],
            ['name' => 'Диагностика', 'duration_minutes' => 60],
            ['name' => 'УЗИ', 'duration_minutes' => 45],
        ])->map(fn (array $data) => Service::create([
            'name' => $data['name'],
            'duration_minutes' => $data['duration_minutes'],
        ]));

        // Врачи по данным с emirmed.kz/ru/specialists/
        $doctorsData = [
            ['name' => 'Абдиев Габит Серикович', 'specialization' => 'Уролог'],
            ['name' => 'Абдулл Рашид Именжанулы', 'specialization' => 'Терапевт'],
            ['name' => 'Аманжолов Бахтияр Рахимович', 'specialization' => 'Эндоскопист'],
            ['name' => 'Байболов Канат Заутбекович', 'specialization' => 'Невропатолог / Невролог'],
            ['name' => 'Байкенже Бибигуль Сериковна', 'specialization' => 'Гинеколог'],
            ['name' => 'Бектасова Майра Асылбековна', 'specialization' => 'ЛОР / Оториноларинголог'],
            ['name' => 'Даулеткулов Нуржан Мейрамбекович', 'specialization' => 'Травматолог'],
            ['name' => 'Джусанбаева Асель Сагатовна', 'specialization' => 'Дерматолог'],
            ['name' => 'Маткасимов Рустем Жанатбекович', 'specialization' => 'Врач общей практики (ВОП)'],
            ['name' => 'Саяндинова Назгуль Абильевна', 'specialization' => 'Педиатр'],
            ['name' => 'Сыдыкова Гульнара Жарылкасиновна', 'specialization' => 'Невропатолог / Невролог'],
            ['name' => 'Ахмеджанов Данияр Даулетбекович', 'specialization' => 'Офтальмолог / Окулист'],
            ['name' => 'Агманова Бибигул Сериковна', 'specialization' => 'Кардиолог'],
            ['name' => 'Токаева Гульнара Сагиналыевна', 'specialization' => 'Эндокринолог'],
            ['name' => 'Байдаулетов Жомарт Жумабаевич', 'specialization' => 'Хирург'],
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

        TimeSlotGenerator::ensureUpcomingSlotsExist();

    }
}
