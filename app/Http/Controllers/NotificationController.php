<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user();
        $notifications = $user
            ? $user->notifications()->latest()->paginate(20)
            : Notification::latest()->paginate(20);

        $role = $user?->hasRole('superadmin') ? 'superadmin' : 'kol';

        if ($request->wantsJson()) {
            return response()->json($notifications);
        }

        if (view()->exists('notifications.index')) {
            return view('notifications.index', compact('notifications', 'role'));
        }

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(
        Request $request,
        Notification $notification
    ): RedirectResponse|JsonResponse {
        if ($request->user() && $notification->user_id !== $request->user()->id) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Notifikasi ditandai sudah dibaca.']);
        }

        if ($notification->target_url) {
            return redirect($notification->target_url);
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->user()) {
            $request->user()->unreadNotifications()->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        } else {
            Notification::unread()->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Semua notifikasi ditandai sudah dibaca.']);
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
