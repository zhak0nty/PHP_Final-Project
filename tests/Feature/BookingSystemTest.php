<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Review;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New Client',
            'email' => 'newclient@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['token', 'user' => ['id', 'email', 'role']]);

        $this->assertDatabaseHas('users', [
            'email' => 'newclient@example.com',
            'role' => User::ROLE_CLIENT,
        ]);
    }

    public function test_user_can_login_and_receive_token(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'email']]);
    }

    public function test_client_can_create_appointment(): void
    {
        $this->seed();

        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $doctor = Doctor::firstOrFail();
        $service = $doctor->services()->firstOrFail();
        $slot = TimeSlot::where('doctor_id', $doctor->id)->firstOrFail();

        Sanctum::actingAs($client);

        $response = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.status', Appointment::STATUS_SCHEDULED);
    }

    public function test_cannot_book_taken_time_slot(): void
    {
        $this->seed();

        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $doctor = Doctor::firstOrFail();
        $service = $doctor->services()->firstOrFail();
        $slot = TimeSlot::where('doctor_id', $doctor->id)->firstOrFail();

        Appointment::create([
            'doctor_id' => $doctor->id,
            'client_id' => $client->id,
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        Sanctum::actingAs($client);

        $response = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('time_slot_id');
    }

    public function test_client_cannot_manage_doctors_or_services(): void
    {
        $this->seed();

        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();

        Sanctum::actingAs($client);

        $this->postJson('/api/doctors', [
            'user_id' => $client->id,
        ])->assertStatus(403);

        $this->postJson('/api/services', [
            'name' => 'Test',
            'duration_minutes' => 30,
        ])->assertStatus(403);
    }

    public function test_client_can_cancel_own_appointment(): void
    {
        $this->seed();

        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $doctor = Doctor::firstOrFail();
        $service = $doctor->services()->firstOrFail();
        $slot = TimeSlot::where('doctor_id', $doctor->id)->firstOrFail();

        Sanctum::actingAs($client);

        $created = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
        ])->assertCreated();

        $id = $created->json('data.id');

        $this->postJson("/api/appointments/{$id}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status', Appointment::STATUS_CANCELLED);
    }

    public function test_client_cannot_cancel_another_clients_appointment(): void
    {
        $this->seed();

        $owner = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $otherClient = User::factory()->create([
            'role' => User::ROLE_CLIENT,
        ]);
        $doctor = Doctor::firstOrFail();
        $service = $doctor->services()->firstOrFail();
        $slot = TimeSlot::where('doctor_id', $doctor->id)->firstOrFail();

        Sanctum::actingAs($owner);

        $created = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
        ])->assertCreated();

        $id = $created->json('data.id');

        Sanctum::actingAs($otherClient);

        $this->postJson("/api/appointments/{$id}/cancel")
            ->assertForbidden();
    }

    public function test_client_can_show_update_and_delete_own_appointment(): void
    {
        $this->seed();

        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $doctor = Doctor::firstOrFail();
        $service = $doctor->services()->firstOrFail();

        $nearSlot = TimeSlot::create([
            'doctor_id' => $doctor->id,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addMinutes(30),
        ]);
        $farSlot = TimeSlot::create([
            'doctor_id' => $doctor->id,
            'starts_at' => now()->addDays(5),
            'ends_at' => now()->addDays(5)->addMinutes(30),
        ]);
        Sanctum::actingAs($client);

        $created = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $nearSlot->id,
        ])->assertCreated();

        $id = $created->json('data.id');

        $this->getJson("/api/appointments/{$id}")
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->putJson("/api/appointments/{$id}", [
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $farSlot->id,
        ])->assertOk()
            ->assertJsonPath('data.time_slot_id', $farSlot->id);

        $this->deleteJson("/api/appointments/{$id}")
            ->assertNoContent();
    }

    public function test_client_cannot_delete_appointment_within_two_days(): void
    {
        $this->seed();

        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $doctor = Doctor::firstOrFail();
        $service = $doctor->services()->firstOrFail();
        $slot = TimeSlot::create([
            'doctor_id' => $doctor->id,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addMinutes(30),
        ]);

        Sanctum::actingAs($client);

        $created = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
        ])->assertCreated();

        $id = $created->json('data.id');

        $this->deleteJson("/api/appointments/{$id}")
            ->assertForbidden();
    }

    public function test_client_can_delete_appointment_at_least_two_days_ahead(): void
    {
        $this->seed();

        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $doctor = Doctor::firstOrFail();
        $service = $doctor->services()->firstOrFail();
        $slot = TimeSlot::create([
            'doctor_id' => $doctor->id,
            'starts_at' => now()->addDays(3),
            'ends_at' => now()->addDays(3)->addMinutes(30),
        ]);

        Sanctum::actingAs($client);

        $created = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
        ])->assertCreated();

        $id = $created->json('data.id');

        $this->deleteJson("/api/appointments/{$id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('appointments', ['id' => $id]);
    }

    public function test_admin_has_full_reviews_crud_in_api(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        Sanctum::actingAs($admin);

        $created = $this->postJson('/api/reviews', [
            'kind' => 'review',
            'name' => 'API Tester',
            'phone' => '+100000000',
            'text' => 'Great doctor and service.',
        ])->assertCreated();

        $id = $created->json('data.id');

        $this->getJson('/api/reviews')
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);

        $this->getJson("/api/reviews/{$id}")
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->patchJson("/api/reviews/{$id}", [
            'text' => 'Updated feedback',
        ])->assertOk()
            ->assertJsonPath('data.text', 'Updated feedback');

        $this->deleteJson("/api/reviews/{$id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('reviews', ['id' => $id]);
        $this->assertTrue(Review::query()->whereKey($id)->doesntExist());
    }
}
