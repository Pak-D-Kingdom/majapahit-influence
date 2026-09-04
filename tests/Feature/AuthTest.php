<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $kolUser;

    protected function setUp(): void
    {
        parent::setUp();

        $superadminRole = Role::firstOrCreate(['name' => 'superadmin'], ['display_name' => 'Superadmin']);
        $kolRole = Role::firstOrCreate(['name' => 'kol'], ['display_name' => 'KOL']);

        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin_test@majapahit.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $this->adminUser->assignRole('superadmin');

        $this->kolUser = User::firstOrCreate(
            ['email' => 'kol_test@majapahit.com'],
            [
                'name' => 'Test KOL',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $this->kolUser->assignRole('kol');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('MAJAPAHIT');
    }

    public function test_admin_can_login_and_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin_test@majapahit.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->adminUser);

        // Pastikan login tercatat di audit_logs
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->adminUser->id,
            'action' => 'auth.login',
            'entity_type' => 'User',
        ]);
    }

    public function test_kol_can_login_and_redirects_to_kol_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'kol_test@majapahit.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('kol.dashboard'));
        $this->assertAuthenticatedAs($this->kolUser);

        // Pastikan login tercatat di audit_logs
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->kolUser->id,
            'action' => 'auth.login',
            'entity_type' => 'User',
        ]);
    }

    public function test_users_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin_test@majapahit.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_failed_login_is_recorded_without_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin_test@majapahit.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'auth.login_failed',
            'entity_type' => 'User',
        ]);
        $this->assertDatabaseMissing('audit_logs', ['new_values' => json_encode(['password' => 'wrong-password'])]);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $inactiveUser = User::firstOrCreate(
            ['email' => 'inactive@majapahit.com'],
            [
                'name' => 'Inactive User',
                'password' => Hash::make('password123'),
                'is_active' => false,
            ]
        );
        $inactiveUser->assignRole('kol');

        $response = $this->post('/login', [
            'email' => 'inactive@majapahit.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_kol_cannot_access_admin_dashboard_gets_403(): void
    {
        $response = $this->actingAs($this->kolUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_kol_can_access_kol_dashboard(): void
    {
        $response = $this->actingAs($this->kolUser)->get('/kol/dashboard');
        $response->assertStatus(200);
        $response->assertSee('KOL Dashboard');
    }

    public function test_admin_cannot_access_kol_dashboard_gets_403(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/kol/dashboard');
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_superadmin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_gets_json_401_for_superadmin_dashboard(): void
    {
        $response = $this->getJson('/admin/dashboard');

        $response->assertUnauthorized();
    }

    public function test_inactive_user_cannot_access_protected_dashboard(): void
    {
        $inactiveUser = User::factory()->create(['is_active' => false]);
        $inactiveUser->assignRole('superadmin');

        $response = $this->actingAs($inactiveUser)->get('/admin/dashboard');

        $response->assertStatus(403);
        $this->assertGuest();
    }

    public function test_user_without_role_cannot_access_protected_dashboard(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_inactive_user_gets_json_403(): void
    {
        $inactiveUser = User::factory()->create(['is_active' => false]);
        $inactiveUser->assignRole('kol');

        $response = $this->actingAs($inactiveUser)->getJson('/kol/dashboard');

        $response->assertForbidden();
        $this->assertGuest();
    }

    public function test_user_can_logout_and_audit_log_created(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->adminUser->id,
            'action' => 'auth.logout',
            'entity_type' => 'User',
        ]);
    }

    public function test_kol_can_view_set_password_screen(): void
    {
        $response = $this->get('/kol/set-password/sample-token?email=kol_test@majapahit.com');
        $response->assertStatus(200);
        $response->assertSee('Aktivasi Akun KOL Anda');
    }

    public function test_kol_can_set_password_with_valid_token_and_redirects_to_dashboard(): void
    {
        $token = \Illuminate\Support\Facades\Password::broker()->createToken($this->kolUser);

        $response = $this->post('/kol/set-password', [
            'token' => $token,
            'email' => 'kol_test@majapahit.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('kol.dashboard'));
        $this->assertAuthenticatedAs($this->kolUser);

        // Verify password updated
        $this->assertTrue(Hash::check('newpassword123', $this->kolUser->fresh()->password));

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->kolUser->id,
            'action' => 'auth.password_changed',
            'entity_type' => 'User',
        ]);
    }

    public function test_user_can_request_password_reset_link(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'admin_test@majapahit.com',
        ]);

        $response->assertSessionHas('status');
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $token = \Illuminate\Support\Facades\Password::broker()->createToken($this->adminUser);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'admin_test@majapahit.com',
            'password' => 'brandnewpass123',
            'password_confirmation' => 'brandnewpass123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('brandnewpass123', $this->adminUser->fresh()->password));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->adminUser->id,
            'action' => 'auth.password_reset',
            'entity_type' => 'User',
        ]);
    }

    public function test_password_reset_revokes_existing_database_sessions(): void
    {
        DB::table('sessions')->insert([
            'id' => 'session-to-revoke',
            'user_id' => $this->adminUser->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test-agent',
            'payload' => 'payload',
            'last_activity' => now()->timestamp,
        ]);

        $token = \Illuminate\Support\Facades\Password::broker()->createToken($this->adminUser);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => 'admin_test@majapahit.com',
            'password' => 'brandnewpass123',
            'password_confirmation' => 'brandnewpass123',
        ]);

        $this->assertDatabaseMissing('sessions', ['id' => 'session-to-revoke']);
    }
}
