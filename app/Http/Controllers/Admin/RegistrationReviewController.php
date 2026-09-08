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

        if ($request->wantsJson()) {
            return response()->json(compact('registrations', 'status'));
        }

        return view('superadmin.registrations.index', compact('registrations', 'status'));
    }

    /**
     * Tampilkan detail pendaftaran.
     */
    public function show(Request $request, KolRegistration $registration)
    {
        $registration->load('files');
        $tiers = Tier::all();

        if ($request->wantsJson()) {
            return response()->json(compact('registration', 'tiers'));
        }

        return view('superadmin.registrations.show', compact('registration', 'tiers'));
    }

    /**
     * Handle review form submission (approve or reject)
     */
    public function review(Request $request, KolRegistration $registration)
    {
        $action = $request->input('action', 'approve');

        if ($action === 'reject') {
            $validated = $request->validate([
                'rejection_reason' => ['required', 'string', 'max:1000'],
            ]);

            return $this->processReject($registration, $validated['rejection_reason'], $request);
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
            'tier_id' => ['nullable', 'exists:tiers,id'],
            'score' => ['nullable', 'integer', 'between:1,5'],
        ]);

        return $this->processApprove($registration, $validated, $request);
    }

    /**
     * Approve pendaftaran.
     */
    public function approve(ApproveRegistrationRequest $request, KolRegistration $registration)
    {
        return $this->processApprove($registration, $request->validated(), $request);
    }

    /**
     * Reject pendaftaran.
     */
    public function reject(RejectRegistrationRequest $request, KolRegistration $registration)
    {
        return $this->processReject($registration, $request->input('rejection_reason'), $request);
    }

    protected function processApprove(KolRegistration $registration, array $data, Request $request)
    {
        if ($registration->status !== 'pending_review' && $registration->status !== 'pending') {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Status pendaftaran ini sudah tidak pending.'], 400);
            }

            return back()->withErrors(['error' => 'Status pendaftaran ini sudah tidak pending.']);
        }

        try {
            $superadmin = $request->user();
            abort_unless($superadmin, 401, 'Unauthenticated.');
            abort_unless($superadmin->isSuperadmin(), 403, 'Unauthorized.');
            $user = $this->service->approve($registration, $superadmin, $data);

            if ($request->wantsJson()) {
                return response()->json(['message' => "Pendaftaran {$registration->full_name} berhasil di-approve."]);
            }

            return redirect()->route('superadmin.registrations.index')->with('success', "Pendaftaran {$registration->full_name} berhasil di-approve.");
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Gagal menyetujui: '.$e->getMessage()], 422);
            }

            return back()->withErrors(['error' => 'Gagal menyetujui: '.$e->getMessage()]);
        }
    }

    protected function processReject(KolRegistration $registration, ?string $reason, Request $request)
    {
        if ($registration->status !== 'pending_review' && $registration->status !== 'pending') {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Status pendaftaran ini sudah tidak pending.'], 400);
            }

            return back()->withErrors(['error' => 'Status pendaftaran ini sudah tidak pending.']);
        }

        try {
            $superadmin = $request->user();
            abort_unless($superadmin, 401, 'Unauthenticated.');
            abort_unless($superadmin->isSuperadmin(), 403, 'Unauthorized.');
            $this->service->reject($registration, $superadmin, $reason);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Pendaftaran berhasil di-reject dan dihapus.']);
            }

            return redirect()->route('superadmin.registrations.index')->with('success', 'Pendaftaran berhasil di-reject.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Gagal me-reject: '.$e->getMessage()], 422);
            }

            return back()->withErrors(['error' => 'Gagal me-reject: '.$e->getMessage()]);
        }
    }
}
