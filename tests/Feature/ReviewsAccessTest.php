<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewsAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_reviews_page_does_not_list_reviews(): void
    {
        Review::create([
            'kind' => 'review',
            'name' => 'Hidden',
            'text' => 'Secret review text',
        ]);

        $this->get(route('reviews.index'))
            ->assertOk()
            ->assertDontSee('Secret review text')
            ->assertDontSee('Latest reviews');
    }

    public function test_client_cannot_access_staff_reviews_page(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->actingAs($client)
            ->get(route('staff.reviews.index'))
            ->assertForbidden();
    }

    public function test_doctor_can_access_staff_reviews_page(): void
    {
        $doctor = User::factory()->create(['role' => User::ROLE_DOCTOR]);

        Review::create([
            'kind' => 'review',
            'name' => 'Patient',
            'text' => 'Staff only review',
        ]);

        $this->actingAs($doctor)
            ->get(route('staff.reviews.index'))
            ->assertOk()
            ->assertSee('Staff only review')
            ->assertSee('Patient feedback');
    }

    public function test_admin_can_access_staff_reviews_page(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('staff.reviews.index'))
            ->assertOk()
            ->assertSee('Patient feedback');
    }
}
