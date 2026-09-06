<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\KolRegistration;
use App\Models\Niche;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('registration.create', ['niches' => Niche::where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(RegistrationRequest $request): RedirectResponse
    {
        $registration = DB::transaction(function () use ($request): KolRegistration {
            $data = $request->validated();
            $registration = KolRegistration::create(['registration_number' => KolRegistration::generateRegistrationNumber(), 'full_name' => $data['full_name'], 'email' => $data['email'], 'phone' => $data['phone'], 'city' => $data['city'] ?? null, 'niches' => $data['niches'], 'social_media' => ['platform' => $data['platform'], 'username' => $data['username'], 'profile_url' => $data['profile_url'] ?? null, 'followers_count' => $data['followers_count']], 'expected_rate' => $data['expected_rate'] ?? null, 'join_reason' => $data['join_reason'] ?? null]);
            foreach ($request->file('portfolio', []) as $file) $registration->files()->create(['file_path' => $file->store('registrations'), 'file_name' => $file->getClientOriginalName(), 'file_size' => $file->getSize(), 'mime_type' => $file->getMimeType()]);
            return $registration;
        });
        app(NotificationService::class)->notifySuperadmins('registration_submitted', 'Pendaftaran KOL baru', $registration->full_name.' mengirim pendaftaran KOL baru.', route('superadmin.registrations.show', $registration));
        return redirect()->route('registration.confirmation', $registration->registration_number);
    }

    public function confirmation(string $registration): View
    {
        return view('registration.confirmation', ['registration' => $registration]);
    }
}
