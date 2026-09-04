<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveRegistrationRequest;
use App\Http\Requests\Admin\RejectRegistrationRequest;
use App\Models\KolRegistration;
use App\Models\Tier;
use App\Services\KolRegistrationService;
use Illuminate\Http\Request;

class RegistrationReviewController extends Controller
{
    protected KolRegistrationService $service;

    public function __construct(KolRegistrationService $service)
    {
        $this->service = $service;
    }

    /**
     * Tampilkan daftar pendaftaran yang masuk.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending_review');

        $registrations = KolRegistration::with('files')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json(compact('registrations', 'status'));
    }

    /**
     * Tampilkan detail pendaftaran.
     */
    public function show(KolRegistration $registration)
    {
        $registration->load('files');
        $tiers = Tier::all();

        return response()->json(compact('registration', 'tiers'));
    }

    /**
     * Approve pendaftaran.
     */
    public function approve(ApproveRegistrationRequest $request, KolRegistration $registration)
    {
        if ($registration->status !== 'pending_review') {
            return response()->json(['error' => 'Status pendaftaran ini sudah tidak pending.'], 400);
        }

        try {
            $superadmin = auth()->user() ?? \App\Models\User::firstOrCreate(['email' => 'superadmin@majapahit.com'], ['name' => 'Superadmin', 'password' => bcrypt('password')]);
            $user = $this->service->approve($registration, $superadmin, $request->validated());
            return response()->json(['message' => "Pendaftaran {$registration->full_name} berhasil di-approve."]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyetujui: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Reject pendaftaran.
     */
    public function reject(RejectRegistrationRequest $request, KolRegistration $registration)
    {
        if ($registration->status !== 'pending_review') {
            return response()->json(['error' => 'Status pendaftaran ini sudah tidak pending.'], 400);
        }

        try {
            $superadmin = auth()->user() ?? \App\Models\User::firstOrCreate(['email' => 'superadmin@majapahit.com'], ['name' => 'Superadmin', 'password' => bcrypt('password')]);
            $this->service->reject($registration, $superadmin, $request->input('rejection_reason'));
            return response()->json(['message' => 'Pendaftaran berhasil di-reject dan dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal me-reject: ' . $e->getMessage()], 422);
        }
    }
}
