<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
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
}
