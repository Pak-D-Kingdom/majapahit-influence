<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_notifications_list(): void
    {
        $user = $this->createUserWithRole('kol');

        Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => 'Test Notification',
            'body' => 'This is a test notification.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->get(route('kol.notifications.index'));

        $response->assertOk();
        $response->assertSee('Test Notification');
    }

    public function test_user_can_mark_single_notification_as_read(): void
    {
        $user = $this->createUserWithRole('kol');

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => 'Test Notification',
            'body' => 'This is a test notification.',
            'is_read' => false,
            'target_url' => '/dashboard',
        ]);

        $response = $this->actingAs($user)->patch(route('kol.notifications.read', $notification));

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = $this->createUserWithRole('kol');

        Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => 'Test Notification 1',
            'body' => 'Message 1',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => 'Test Notification 2',
            'body' => 'Message 2',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->post(route('kol.notifications.read-all'));

        $response->assertRedirect();

        $this->assertDatabaseMissing('notifications', [
            'user_id' => $user->id,
            'is_read' => false,
        ]);
    }

    public function test_user_cannot_read_other_users_notification(): void
    {
        $user1 = $this->createUserWithRole('kol');
        $user2 = $this->createUserWithRole('kol');

        $notification = Notification::create([
            'user_id' => $user1->id,
            'type' => 'system',
            'title' => 'Test Notification',
            'body' => 'This is a test notification.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user2)->patch(route('kol.notifications.read', $notification));

        $response->assertForbidden();
    }

    public function test_brand_user_can_view_notifications_list(): void
    {
        $user = $this->createUserWithRole('brand');

        Notification::create([
            'user_id' => $user->id,
            'type' => 'product_verification',
            'title' => 'Produk Disetujui',
            'body' => 'Produk Serum Anda telah disetujui.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->get(route('brand.notifications.index'));

        $response->assertOk();
        $response->assertSee('Produk Disetujui');
        $response->assertSee('Produk Serum Anda telah disetujui.');
    }

    public function test_brand_user_can_mark_notification_as_read_via_get(): void
    {
        $user = $this->createUserWithRole('brand');

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'product_verification',
            'title' => 'Produk Disetujui',
            'body' => 'Produk Serum Anda telah disetujui.',
            'target_url' => route('brand.products.index'),
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->get(route('brand.notifications.read', $notification));

        $response->assertRedirect(route('brand.products.index'));
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst($role)]));

        return $user;
    }
}
