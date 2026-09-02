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

        return view('superadmin.registrations.index', compact('registrations', 'status'));
    }

    /**
     * Tampilkan detail pendaftaran.
     */
    public function show(KolRegistration $registration)
    {
        $registration->load('files');
        $tiers = Tier::all();

        return view('superadmin.registrations.show', compact('registration', 'tiers'));
    }

    /**
     * Approve pendaftaran.
     */
    public function approve(ApproveRegistrationRequest $request, KolRegistration $registration)
    {
        if ($registration->status !== 'pending_review') {
            return back()->with('error', 'Status pendaftaran ini sudah tidak pending.');
        }

        try {
            $admin = auth()->user() ?? \App\Models\User::firstOrCreate(['email' => 'admin@admin.com'], ['name' => 'Admin', 'password' => bcrypt('password')]);
            $user = $this->service->approve($registration, $admin, $request->validated());
            return redirect()->route('admin.registrations.index')->with('success', "Pendaftaran {$registration->full_name} berhasil di-approve.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    /**
     * Reject pendaftaran.
     */
    public function reject(RejectRegistrationRequest $request, KolRegistration $registration)
    {
        if ($registration->status !== 'pending_review') {
            return back()->with('error', 'Status pendaftaran ini sudah tidak pending.');
        }

        try {
            $admin = auth()->user() ?? \App\Models\User::firstOrCreate(['email' => 'admin@admin.com'], ['name' => 'Admin', 'password' => bcrypt('password')]);
            $this->service->reject($registration, $admin, $request->input('rejection_reason'));
            return redirect()->route('admin.registrations.index')->with('success', 'Pendaftaran berhasil di-reject dan dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal me-reject: ' . $e->getMessage());
        }
    }
}
