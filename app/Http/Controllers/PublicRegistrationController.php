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
     * Tampilkan data awal pendaftaran jika diperlukan (API).
     */
    public function create()
    {
        $niches = Niche::where('is_active', true)->get();
        return response()->json(['data' => compact('niches')]);
    }

    /**
     * Proses submit pendaftaran.
     */
    public function store(StoreKolRegistrationRequest $request)
    {
        $data = $request->validated();
        $files = $request->file('portfolio') ?? [];

        $registration = $this->service->store($data, $files);

        return response()->json([
            'message' => 'Pendaftaran berhasil',
            'registration_number' => $registration->registration_number
        ], 201);
    }

    /**
     * Tampilkan halaman konfirmasi sukses daftar (opsional untuk API).
     */
    public function confirmation()
    {
        return response()->json(['message' => 'Silakan simpan nomor pendaftaran Anda.']);
    }
}
