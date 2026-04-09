<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
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
}
