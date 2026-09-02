<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View { return view('notifications.index', ['notifications' => request()->user()->notifications()->latest()->paginate(15)->withQueryString(), 'role' => request()->user()->isSuperadmin() ? 'superadmin' : 'kol']); }
    public function read(Notification $notification): RedirectResponse { $this->authorize('view', $notification); $notification->update(['is_read' => true, 'read_at' => now()]); return redirect($notification->target_url ?: url()->previous()); }
    public function readAll(): RedirectResponse { request()->user()->notifications()->where('is_read', false)->update(['is_read' => true, 'read_at' => now()]); return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.'); }
}
