<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InfoPageController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $pages = $this->pages();

        if (! array_key_exists($slug, $pages)) {
            abort(404);
        }

        $page = $pages[$slug];

        $doctorsQuery = Doctor::query()->with('user', 'services')->orderBy('id');

        $doctors = match ($slug) {
            'therapy' => $doctorsQuery
                ->where(function ($q) {
                    $q->where('specialization', 'like', '%Therapist%')
                        ->orWhere('specialization', 'like', '%General Practitioner%');
                })
                ->get(),
            'diagnostics' => $doctorsQuery
                ->where(function ($q) {
                    $q->where('specialization', 'like', '%Ultrasound%')
                        ->orWhere('specialization', 'like', '%CT%')
                        ->orWhere('specialization', 'like', '%MRI%')
                        ->orWhere('specialization', 'like', '%X-Ray%')
                        ->orWhere('specialization', 'like', '%Radiology%')
                        ->orWhere('specialization', 'like', '%Functional%');
                })
                ->get(),
            'narrow-directions' => $doctorsQuery->get(),
            'online-booking' => $doctorsQuery->get(),
            default => $doctorsQuery->get(),
        };

        $specializations = null;
        if ($slug === 'narrow-directions') {
            $specializations = $doctors
                ->pluck('specialization')
                ->filter()
                ->map(fn ($s) => trim((string) $s))
                ->filter()
                ->unique()
                ->sort()
                ->values();
        }

        return view('info.show', [
            'slug' => $slug,
            'page' => $page,
            'doctors' => $doctors,
            'specializations' => $specializations,
        ]);
    }

    /**
     * @return array<string, array{title:string, lead:string, about:array<int,string>, cases:array<int, string>, equipment:array<int, array{title:string, text:string}>, cta?:array{title:string, text:string}}>
     */
    private function pages(): array
    {
        return [
            'consultation' => [
                'title' => 'Consultation',
                'lead' => 'You describe what hurts or worries you — the doctor examines, explains, and tells you what to do next.',
                'about' => [
                    'The doctor listens, asks questions, and examines you if needed.',
                    'Then they explain in plain language and may suggest tests or refer you to another specialist.',
                ],
                'cases' => [
                    'Something hurts or bothers you and you are not sure which specialist to see',
                    'Fever, cold, cough',
                    'You want a check-up or a second opinion',
                    'You need to know which tests to take',
                ],
                'equipment' => [
                    ['title' => 'What you might need', 'text' => 'Blood tests, ultrasound, or other exams — only if the doctor decides they are necessary.'],
                    ['title' => 'No unnecessary extras', 'text' => 'Everything is explained clearly, without jargon.'],
                ],
            ],
            'diagnostics' => [
                'title' => 'Diagnostics',
                'lead' => 'Examinations that help understand what is going on: lab tests, ultrasound, imaging.',
                'about' => [
                    'Diagnostics means checking your body: blood tests, ultrasound, or scans to find the cause of symptoms.',
                    'The doctor orders only what is really needed based on your complaints.',
                ],
                'cases' => [
                    'Routine health screening',
                    'Symptoms — you need to find the cause',
                    'Checking whether treatment is working',
                    'Preparing for surgery or a procedure',
                ],
                'equipment' => [
                    ['title' => 'Ultrasound', 'text' => 'Painless imaging on a screen — internal organs can be assessed.'],
                    ['title' => 'CT', 'text' => 'Layered scans when high detail is required.'],
                    ['title' => 'MRI', 'text' => 'Detailed imaging without X-rays — e.g. joints, spine.'],
                    ['title' => 'Lab tests', 'text' => 'Blood, urine, etc. — as prescribed by the doctor.'],
                ],
            ],
            'therapy' => [
                'title' => 'Therapy',
                'lead' => 'A visit to a therapist — a general doctor who handles common complaints and refers you to a specialist if needed.',
                'about' => [
                    'A therapist is who you see when you are not sure what hurts or whom to visit. They examine you and may order tests or ultrasound.',
                    'If you need a focused specialist (ENT, skin, etc.), they will tell you whom to book.',
                ],
                'cases' => [
                    'Cold, cough, fever',
                    'Blood pressure swings, palpitations',
                    'Weakness, dizziness',
                    'Stomach pain, digestive issues',
                ],
                'equipment' => [
                    ['title' => 'At the visit', 'text' => 'Blood pressure, pulse, ECG if needed.'],
                    ['title' => 'If more detail is needed', 'text' => 'The doctor may order ultrasound or lab tests.'],
                ],
            ],
            'narrow-directions' => [
                'title' => 'Specialist care',
                'lead' => 'Doctors focused on one area: throat and ears, skin, women’s health, nerves, and more. Pick a specialty and book the right doctor.',
                'about' => [
                    'A specialist focuses on one field — for example only ENT, only dermatology, or only gynecology.',
                    'Browse the list by specialty and book online.',
                ],
                'cases' => [
                    'Headaches, numbness, sleep issues',
                    'Women’s health, pain, cycle changes',
                    'Rash, itching, skin concerns',
                    'Sore throat, ear pain, blocked nose',
                ],
                'equipment' => [
                    ['title' => 'Examinations', 'text' => 'The doctor may order tests or imaging if needed.'],
                    ['title' => 'Working with other doctors', 'text' => 'Another specialist can be involved if necessary.'],
                ],
                'cta' => [
                    'title' => 'Choose a doctor',
                    'text' => 'Open the specialists list and book — no account required.',
                ],
            ],
            'online-booking' => [
                'title' => 'Online booking',
                'lead' => 'Pick a doctor and time online, enter your name, email, and phone — your appointment is set. Registration is optional.',
                'about' => [
                    'You only need name, email, and phone. No password required.',
                    'The slot is held for 5 minutes. If you later register with the same email, the booking appears in your account.',
                ],
                'cases' => [
                    'Quick booking without creating an account',
                    'You choose the day and time yourself',
                    'Hold a slot and confirm later',
                ],
                'equipment' => [
                    ['title' => 'Busy slots', 'text' => 'The system will not let you book a time that is already taken.'],
                    ['title' => 'Doctor is informed', 'text' => 'Your doctor will see the booking and expect you.'],
                ],
                'cta' => [
                    'title' => 'Book now',
                    'text' => 'Use the button below and fill in a short form — about a minute.',
                ],
            ],
        ];
    }
}
