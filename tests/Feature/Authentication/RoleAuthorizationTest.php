<?php

namespace Tests\Feature\Authentication;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_protected_areas(): void
    {
        $this->get('/superadmin/dashboard')->assertRedirect(route('login'));
        $this->get('/kol/dashboard')->assertRedirect(route('login'));
    }

    public function test_superadmin_can_access_superadmin_notifications(): void
    {
        $user = $this->userWithRole('superadmin');

        $this->actingAs($user)->get('/superadmin/notifications')->assertOk();
    }

    public function test_kol_can_access_kol_notifications(): void
    {
        $user = $this->userWithRole('kol');

        $this->actingAs($user)->get('/kol/notifications')->assertOk();
    }

    public function test_roles_cannot_access_each_others_area(): void
    {
        $superadmin = $this->userWithRole('superadmin');
        $kol = $this->userWithRole('kol');

        $this->actingAs($superadmin)->get('/kol/notifications')->assertForbidden();
        $this->actingAs($kol)->get('/superadmin/notifications')->assertForbidden();
    }

    public function test_user_without_role_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/superadmin/notifications')->assertForbidden();
        $this->actingAs($user)->get('/kol/notifications')->assertForbidden();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password'),
            'is_active' => false,
        ]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::create(['name' => $role, 'display_name' => ucfirst($role)]));

        return $user;
    }
}
