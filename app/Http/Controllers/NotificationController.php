<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
=======
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
>>>>>>> origin/chanan
use Illuminate\View\View;

class NotificationController extends Controller
{
<<<<<<< HEAD
    /**
     * Display all notifications for the authenticated user.
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(
        Request $request,
        DatabaseNotification $notification
    ): RedirectResponse {
        abort_unless(
            $notification->notifiable_id === $request->user()->getAuthIdentifier()
            && $notification->notifiable_type === $request->user()->getMorphClass(),
            403
        );

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()
            ->unreadNotifications
            ->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
=======
    public function index(): View { return view('notifications.index', ['notifications' => request()->user()->notifications()->latest()->paginate(15)->withQueryString(), 'role' => request()->user()->isSuperadmin() ? 'superadmin' : 'kol']); }
    public function read(Notification $notification): RedirectResponse { $this->authorize('view', $notification); $notification->update(['is_read' => true, 'read_at' => now()]); return redirect($notification->target_url ?: url()->previous()); }
    public function readAll(): RedirectResponse { request()->user()->notifications()->where('is_read', false)->update(['is_read' => true, 'read_at' => now()]); return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.'); }
}
>>>>>>> origin/chanan
