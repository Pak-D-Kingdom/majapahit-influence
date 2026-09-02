<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\AuditLogService;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::query()->withCount('campaigns')->when(request('search'), fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))->when(request('status') !== null && request('status') !== '', fn ($q) => $q->where('is_active', request('status') === 'active'))->latest()->paginate(15)->withQueryString();
        return view('superadmin.brands.index', compact('brands'));
    }

    public function create(): View { return view('superadmin.brands.form', ['brand' => new Brand(['is_active' => true]), 'mode' => 'create']); }

    public function store(BrandRequest $request): RedirectResponse
    {
        $brand = DB::transaction(function () use ($request): Brand { $data = $request->validated(); $brand = Brand::create(collect($data)->except('logo')->all()); if ($request->hasFile('logo')) $brand->update(['logo_path' => $request->file('logo')->store('brands')]); return $brand; });
        app(AuditLogService::class)->record('brand_created', 'brands', $brand->id, null, $brand->only(['name', 'industry', 'is_active']), $request->user());
        return redirect()->route('superadmin.brands.show', $brand)->with('success', 'Brand berhasil ditambahkan.');
    }

    public function show(Brand $brand): View { return view('superadmin.brands.show', ['brand' => $brand->load(['campaigns' => fn ($q) => $q->latest()])]); }
    public function edit(Brand $brand): View { return view('superadmin.brands.form', ['brand' => $brand, 'mode' => 'edit']); }

    public function update(BrandRequest $request, Brand $brand): RedirectResponse
    {
        $oldValues = $brand->only(['name', 'industry', 'is_active']);
        $data = $request->validated();
        $brand->update(collect($data)->except('logo')->all());
        if ($request->hasFile('logo')) $brand->update(['logo_path' => $request->file('logo')->store('brands')]);
        app(AuditLogService::class)->record('brand_updated', 'brands', $brand->id, $oldValues, $brand->fresh()->only(['name', 'industry', 'is_active']), $request->user());
        return redirect()->route('superadmin.brands.show', $brand)->with('success', 'Brand berhasil diperbarui.');
    }
}
