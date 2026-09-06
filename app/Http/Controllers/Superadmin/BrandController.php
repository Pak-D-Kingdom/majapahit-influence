<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\AuditLog;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Display a listing of brands with filter & search.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Brand::query()->withCount(['campaigns']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('pic_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->input('industry'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        $brands = $query->orderBy('name')->paginate($request->input('per_page', 15));

        if ($request->wantsJson()) {
            return response()->json($brands);
        }

        return view('superadmin.brands.index', compact('brands'));
    }

    /**
     * Store a newly created brand in storage.
     */
    public function store(StoreBrandRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('brands', 'public');
        }

        $brand = Brand::create($validated);

        AuditLog::log(
            action: 'create_brand',
            entityType: 'brand',
            entityId: $brand->id,
            newValues: $brand->toArray()
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Brand berhasil ditambahkan.', 'data' => $brand], 201);
        }

        return redirect()->route('superadmin.brands.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    /**
     * Display the specified brand and its campaigns.
     */
    public function show(Request $request, Brand $brand): View|JsonResponse
    {
        $brand->load(['campaigns' => function ($q) {
            $q->withCount(['endorsements'])->latest();
        }]);

        if ($request->wantsJson()) {
            return response()->json($brand);
        }

        return view('superadmin.brands.show', compact('brand'));
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $oldValues = $brand->toArray();

        if ($request->hasFile('logo')) {
            if ($brand->logo_path && Storage::disk('public')->exists($brand->logo_path)) {
                Storage::disk('public')->delete($brand->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);

        AuditLog::log(
            action: 'update_brand',
            entityType: 'brand',
            entityId: $brand->id,
            oldValues: $oldValues,
            newValues: $brand->fresh()->toArray()
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Brand berhasil diperbarui.', 'data' => $brand]);
        }

        return redirect()->route('superadmin.brands.show', $brand)->with('success', 'Brand berhasil diperbarui.');
    }

    /**
     * Soft delete the specified brand.
     */
    public function destroy(Request $request, Brand $brand): RedirectResponse|JsonResponse
    {
        $brandId = $brand->id;
        $brand->delete();

        AuditLog::log(
            action: 'delete_brand',
            entityType: 'brand',
            entityId: $brandId
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Brand berhasil dihapus.']);
        }

        return redirect()->route('superadmin.brands.index')->with('success', 'Brand berhasil dihapus.');
    }
=======
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
>>>>>>> origin/chanan
}
