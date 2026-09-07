<?php

namespace App\Http\Controllers;

use App\Models\BrandRegistration;
use App\Models\Notification;
use App\Models\ProductCategory;
use App\Models\User;
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
    public function store(Request $request): View|Response|RedirectResponse
    {
        $validated = $request->validate([
            'brand_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'industry_category' => ['required', 'string', 'max:100'],
            'pic_name' => ['required', 'string', 'max:255'],
            'pic_title' => ['nullable', 'string', 'max:100'],
            'pic_email' => ['required', 'email', 'max:255'],
            'pic_phone' => ['required', 'string', 'max:30'],
            'social_media' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'service_need' => ['required', 'in:endorsement,maklon,both'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $registration = BrandRegistration::create($validated);

        // Notify Superadmins
        try {
            $admins = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['superadmin', 'admin']);
            })->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Pendaftaran Brand Baru: '.$registration->brand_name,
                    'message' => 'Brand '.$registration->brand_name.' (PIC: '.$registration->pic_name.') telah mengajukan kemitraan ['.$registration->service_need_label.'].',
                    'type' => 'brand_registration',
                    'action_url' => route('superadmin.brand-registrations.show', $registration->id),
                ]);
            }
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
