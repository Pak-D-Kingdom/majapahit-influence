<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_records_the_authenticated_actor_and_request_context(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/');
        $log = app(AuditLogService::class)->record('profile_updated', 'users', $user->id, ['name' => 'Lama'], ['name' => 'Baru']);

        $this->assertSame($user->id, $log->user_id);
        $this->assertSame('profile_updated', $log->action);
        $this->assertSame('Baru', $log->new_values['name']);
        $this->assertNotNull($log->ip_address);
    }

    public function test_it_redacts_sensitive_values_and_supports_system_actions(): void
    {
        $log = AuditLog::log('system_check', 'system', null, ['password' => 'old', 'meta' => ['token' => 'secret']], ['api_token' => 'new']);

        $this->assertNull($log->user_id);
        $this->assertSame('[REDACTED]', $log->old_values['password']);
        $this->assertSame('[REDACTED]', $log->old_values['meta']['token']);
        $this->assertSame('[REDACTED]', $log->new_values['api_token']);
    }
}
