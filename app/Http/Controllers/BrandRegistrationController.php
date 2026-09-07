<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRegistrationRequest;
use App\Models\BrandRegistration;
use App\Models\Notification;
use App\Models\ProductCategory;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BrandRegistrationController extends Controller
{
    /**
     * Show the public Brand registration form.
     */
    public function create(Request $request): View|Response
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'categories' => $categories,
                'service_needs' => [
                    'endorsement' => 'Promosi & Endorsement Influencer',
                    'maklon' => 'Maklon / Pembuatan Produk Baru',
                    'both' => 'Maklon Produk + Promosi Influencer (All-in-One)',
                ],
            ]);
        }

        return view('brand.register', compact('categories'));
    }

    /**
     * Handle incoming Brand registration request.
     */
    public function store(BrandRegistrationRequest $request): View|Response|RedirectResponse
    {
        $registration = BrandRegistration::create($request->validated());

        // Notify Superadmins
        try {
            app(NotificationService::class)->notifySuperadmins(
                'brand_registration',
                'Pendaftaran Brand Baru: '.$registration->brand_name,
                'Brand '.$registration->brand_name.' (PIC: '.$registration->pic_name.') telah mengajukan kemitraan ['.$registration->service_need_label.'].',
                route('superadmin.brand-registrations.show', $registration->id)
            );
        } catch (\Throwable) {
            // Non-blocking notification fail
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran Brand berhasil diajukan.',
                'data' => $registration,
            ], 201);
        }

        return redirect()->route('brand.register.confirmation', $registration->id)
            ->with('success', 'Pendaftaran Brand Anda berhasil dikirim! Tim kami akan segera menghubungi Anda.');
    }

    /**
     * Show registration confirmation page.
     */
    public function confirmation(BrandRegistration $registration): View
    {
        return view('brand.confirmation', compact('registration'));
    }
}
