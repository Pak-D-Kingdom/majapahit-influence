<?php

namespace App\Http\Controllers;

use App\Http\Requests\Public\StoreKolRegistrationRequest;
use App\Models\Niche;
use App\Services\KolRegistrationService;
use Illuminate\Http\Request;

class PublicRegistrationController extends Controller
{
    protected KolRegistrationService $service;

    public function __construct(KolRegistrationService $service)
    {
        $this->service = $service;
    }

    /**
     * Tampilkan form pendaftaran.
     */
    public function create()
    {
        $niches = Niche::where('is_active', true)->get();
        return view('public.registration.create', compact('niches'));
    }

    /**
     * Proses submit pendaftaran.
     */
    public function store(StoreKolRegistrationRequest $request)
    {
        $data = $request->validated();
        $files = $request->file('portfolio') ?? [];

        $registration = $this->service->store($data, $files);

        return redirect()->route('public.kol.confirmation')
            ->with('registration_number', $registration->registration_number);
    }

    /**
     * Tampilkan halaman konfirmasi sukses daftar.
     */
    public function confirmation()
    {
        if (!session('registration_number')) {
            return redirect()->route('public.kol.register');
        }

        return view('public.registration.confirmation');
    }
}
