<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BrandRegistrationReviewController extends Controller
{
    /**
     * Display a listing of brand registrations.
     */
    public function index(Request $request): View|Response
    {
        $status = $request->query('status');

        $query = BrandRegistration::query()->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $registrations = $query->paginate(15)->withQueryString();

        $counts = [
            'total' => BrandRegistration::count(),
            'pending' => BrandRegistration::where('status', 'pending')->count(),
            'approved' => BrandRegistration::where('status', 'approved')->count(),
            'rejected' => BrandRegistration::where('status', 'rejected')->count(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'counts' => $counts,
                'registrations' => $registrations,
            ]);
        }

        return view('superadmin.brand_registrations.index', compact('registrations', 'counts', 'status'));
    }

    /**
     * Display the specified brand registration.
     */
    public function show(Request $request, BrandRegistration $brandRegistration): View|Response
    {
        $brandRegistration->load('reviewer');

        if ($request->wantsJson()) {
            return response()->json(['registration' => $brandRegistration]);
        }

        return view('superadmin.brand_registrations.show', ['registration' => $brandRegistration]);
    }

    /**
     * Approve the brand registration and convert to official Brand.
     */
    public function approve(Request $request, BrandRegistration $brandRegistration): Response|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $user->isSuperadmin(), 403, 'Unauthorized.');

        $brandRegistration->update([
            'status' => 'approved',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'admin_notes' => $request->input('admin_notes', 'Disetujui untuk kemitraan.'),
        ]);

        // Automatically create official Brand record if not already exists
        $brand = Brand::firstOrCreate(
            ['name' => $brandRegistration->brand_name],
            [
                'industry' => $brandRegistration->industry_category,
                'pic_name' => $brandRegistration->pic_name,
                'pic_title' => $brandRegistration->pic_title,
                'pic_email' => $brandRegistration->pic_email,
                'pic_phone' => $brandRegistration->pic_phone,
                'notes' => 'Pendaftaran via web: '.$brandRegistration->service_need_label.'. '.$brandRegistration->notes,
                'is_active' => true,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran Brand berhasil disetujui & Brand resmi telah dibuat.',
                'brand' => $brand,
                'registration' => $brandRegistration,
            ]);
        }

        return redirect()->route('superadmin.brand-registrations.index')
            ->with('success', "Pendaftaran Brand '{$brandRegistration->brand_name}' berhasil disetujui! Entitas Brand baru telah aktif.");
    }

    /**
     * Reject the brand registration.
     */
    public function reject(Request $request, BrandRegistration $brandRegistration): Response|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $user->isSuperadmin(), 403, 'Unauthorized.');

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $brandRegistration->update([
            'status' => 'rejected',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran Brand telah ditolak.',
                'registration' => $brandRegistration,
            ]);
        }

        return redirect()->route('superadmin.brand-registrations.index')
            ->with('success', "Pendaftaran Brand '{$brandRegistration->brand_name}' telah ditolak.");
    }
}
