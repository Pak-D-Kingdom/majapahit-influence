<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\KolRegistration;
use App\Models\Niche;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(Request $request): View|JsonResponse
    {
        $niches = Niche::where('is_active', true)->orderBy('name')->get();

        if ($request->expectsJson()) {
            return response()->json(['data' => compact('niches')]);
        }

        return view('registration.create', ['niches' => $niches]);
    }

    public function store(RegistrationRequest $request): RedirectResponse|JsonResponse
    {
        $registration = DB::transaction(function () use ($request): KolRegistration {
            $data = $request->validated();

            $socialMedia = [];
            if (! empty($data['social_media']) && is_array($data['social_media'])) {
                $selectedPlatforms = (array) ($request->input('platforms') ?? array_keys($data['social_media']));
                foreach ($data['social_media'] as $platformKey => $account) {
                    if (is_array($account) && ! empty($account['username']) && (empty($selectedPlatforms) || in_array($platformKey, $selectedPlatforms))) {
                        $socialMedia[] = [
                            'platform' => (string) ($account['platform'] ?? $platformKey),
                            'username' => (string) $account['username'],
                            'profile_url' => $account['profile_url'] ?? null,
                            'followers_count' => (int) ($account['followers_count'] ?? 0),
                        ];
                    }
                }
            }

            if (empty($socialMedia)) {
                $socialMedia = [
                    'platform' => $data['platform'] ?? ($data['social_media']['platform'] ?? 'instagram'),
                    'username' => $data['username'] ?? ($data['social_media']['username'] ?? ''),
                    'profile_url' => $data['profile_url'] ?? ($data['social_media']['profile_url'] ?? null),
                    'followers_count' => (int) ($data['followers_count'] ?? ($data['social_media']['followers_count'] ?? 0)),
                ];
            }

            $registration = KolRegistration::create([
                'registration_number' => KolRegistration::generateRegistrationNumber(),
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'password' => ! empty($data['password']) ? Hash::make($data['password']) : null,
                'phone' => $data['phone'],
                'city' => $data['city'] ?? null,
                'niches' => $data['niches'] ?? [],
                'social_media' => $socialMedia,
                'expected_rate' => $data['expected_rate'] ?? null,
                'join_reason' => $data['join_reason'] ?? null,
            ]);

            foreach ($request->file('portfolio', []) as $file) {
                $registration->files()->create([
                    'file_path' => $file->store('registrations'),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }

            return $registration;
        });

        if (class_exists(NotificationService::class)) {
            app(NotificationService::class)->notifySuperadmins(
                'registration_submitted',
                'Pendaftaran KOL baru',
                $registration->full_name.' mengirim pendaftaran KOL baru.',
                route('superadmin.registrations.show', $registration)
            );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pendaftaran berhasil',
                'registration_number' => $registration->registration_number,
                'data' => $registration,
            ], 201);
        }

        return redirect()->route('public.register.confirmation', $registration->registration_number);
    }

    public function confirmation(Request $request, ?string $registration = null): View|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Silakan simpan nomor pendaftaran Anda.',
                'registration_number' => $registration,
            ]);
        }

        return view('registration.confirmation', ['registration' => $registration]);
    }
}
