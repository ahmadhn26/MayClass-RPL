<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile(): void
    {
        $this->get(route('student.profile'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_student_can_view_profile(): void
    {
        $studentId = 'MC-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::factory()->create([
            'role' => 'student',
            'student_id' => $studentId,
            'phone' => '081234567890',
            'gender' => 'male',
            'parent_name' => 'Budi Santoso',
            'address' => 'Jl. Contoh No. 1',
        ]);

        $this->actingAs($user)
            ->get(route('student.profile'))
            ->assertOk()
            ->assertSeeText('Edit Profil')
            ->assertSee($studentId, false);
    }

    public function test_authenticated_visitor_can_view_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'visitor',
            'student_id' => null,
        ]);

        $this->actingAs($user)
            ->get(route('student.profile'))
            ->assertOk()
            ->assertSeeText('Edit Profil')
            ->assertSee($user->email, false);
    }

    public function test_student_can_update_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'student',
            'student_id' => 'MC-000001',
        ]);

        $payload = [
            'name' => 'Student Baru',
            'email' => 'student@example.test',
            'phone' => '081234567810',
            'gender' => 'female',
            'parent_name' => 'Orang Tua',
            'address' => 'Alamat baru',
        ];

        $this->actingAs($user)
            ->post(route('student.profile.update'), $payload)
            ->assertRedirect(route('student.profile'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('users', array_merge($payload, [
            'id' => $user->id,
            'student_id' => 'MC-000001',
        ]));
    }
}
