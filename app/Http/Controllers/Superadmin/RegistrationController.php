<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\RegistrationReviewRequest;
use App\Models\AuditLog;
use App\Models\KolProfile;
use App\Models\KolRegistration;
use App\Models\KolSocialMedia;
use App\Models\Niche;
use App\Models\Role;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\KolWelcomeMail;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(): View
    {
        return view('superadmin.registrations.index', ['registrations' => KolRegistration::latest()->when(request('status'), fn ($q, $status) => $q->where('status', $status))->paginate(15)->withQueryString()]);
    }

    public function show(KolRegistration $registration): View
    {
        return view('superadmin.registrations.show', ['registration' => $registration->load(['files', 'reviewer', 'approver'])]);
    }

    public function review(RegistrationReviewRequest $request, KolRegistration $registration): RedirectResponse
    {
        abort_unless($registration->status === 'pending_review', 422, 'Pendaftaran ini sudah diproses.');
        $action = $request->input('action');
        if ($action === 'reject') {
            DB::transaction(function () use ($request, $registration): void {
                AuditLog::log('registration_rejected', 'kol_registrations', $registration->id, null, ['reason' => $request->validated('rejection_reason')], $request->user());
                $registration->delete();
            });
            return redirect()->route('superadmin.registrations.index')->with('success', 'Pendaftaran ditolak dan dihapus.');
        }

        DB::transaction(function () use ($request, $registration): void {
            $data = $registration->social_media ?? [];
            $user = User::create(['name' => $registration->full_name, 'email' => $registration->email, 'password' => Hash::make(str()->random(32)), 'is_active' => true]);
            $user->assignRole(Role::where('name', 'kol')->firstOrFail());
            $followers = (int) ($data['followers_count'] ?? 0);
            $tier = Tier::where('min_followers', '<=', $followers)->where(fn ($q) => $q->whereNull('max_followers')->orWhere('max_followers', '>=', $followers))->orderByDesc('min_followers')->first();
            $profile = $user->kolProfile()->create(['nickname' => $registration->full_name, 'city' => $registration->city, 'tier_id' => $tier?->id, 'status' => 'aktif', 'joined_at' => now()]);
            $profile->niches()->sync(Niche::whereIn('name', $registration->niches ?? [])->pluck('id'));
            KolSocialMedia::create(['kol_profile_id' => $profile->id, 'platform' => $data['platform'] ?? 'instagram', 'username' => $data['username'] ?? '-', 'profile_url' => $data['profile_url'] ?? null, 'followers_count' => $followers]);
            $registration->update(['status' => 'approved', 'approved_by' => $request->user()->id, 'approved_at' => now(), 'reviewed_by' => $request->user()->id, 'reviewed_at' => now(), 'notes' => $request->validated('notes')]);
            $token = Password::createToken($user);
            Mail::to($user)->queue((new KolWelcomeMail($user, $token))->afterCommit());
            AuditLog::log('registration_approved', 'kol_registrations', $registration->id, null, ['user_id' => $user->id], $request->user());
        });
        return redirect()->route('superadmin.registrations.index')->with('success', 'Pendaftaran disetujui dan akun KOL dibuat.');
    }
}
