<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ContentBank;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProductManagementController extends Controller
{
    /**
     * Display a listing of products in Superadmin.
     */
    public function index(Request $request): View|Response
    {
        $products = Product::with(['brand', 'category', 'contentBanks'])
            ->latest()
            ->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['products' => $products]);
        }

        return view('superadmin.products.index', compact('products'));
    }

    /**
     * Show form to create a new product.
     */
    public function create(Request $request): View|Response
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json(['brands' => $brands, 'categories' => $categories]);
        }

        return view('superadmin.products.create', compact('brands', 'categories'));
    }

    /**
     * Store a new product with locked commission.
     */
    public function store(Request $request): Response|RedirectResponse
    {
        abort_unless($request->user() && $request->user()->isSuperadmin(), 403, 'Unauthorized.');

        $validated = $request->validate([
            'brand_id' => ['required', 'exists:brands,id'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'locked_commission_percent' => ['required', 'numeric', 'min:1', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'stock' => ['required', 'integer', 'min:0'],
            'promotion_pathway' => ['required', 'in:direct,marketplace,both'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($validated['image']);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(5);
        $validated['locked_commission_amount'] = round(($validated['price'] * $validated['locked_commission_percent']) / 100, 2);
        $validated['is_active'] = true;

        $product = Product::create($validated);

        // Optional Bank Konten entry (Google Drive Link with description)
        if ($request->filled('drive_folder_url')) {
            ContentBank::create([
                'brand_id' => $product->brand_id,
                'product_id' => $product->id,
                'title' => 'Bank Konten Resmi Produk',
                'asset_type' => 'drive_link',
                'external_url' => $request->input('drive_folder_url'),
                'content_text' => $request->input('drive_description') ?: 'Folder aset promosi resmi dari Brand di Google Drive (video B-roll, foto produk HD, dan materi copywriting).',
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke Katalog.',
                'product' => $product,
            ], 201);
        }

        return redirect()->route('superadmin.products.index')
            ->with('success', "Produk '{$product->name}' berhasil ditambahkan ke Katalog!");
    }

    /**
     * Show form to edit an existing product.
     */
    public function edit(Request $request, Product $product): View|Response
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $product->load(['contentBanks']);

        if ($request->wantsJson()) {
            return response()->json(['product' => $product, 'brands' => $brands, 'categories' => $categories]);
        }

        return view('superadmin.products.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Update product details.
     */
    public function update(Request $request, Product $product): Response|RedirectResponse
    {
        abort_unless($request->user() && $request->user()->isSuperadmin(), 403, 'Unauthorized.');

        $validated = $request->validate([
            'brand_id' => ['required', 'exists:brands,id'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'locked_commission_percent' => ['required', 'numeric', 'min:1', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'stock' => ['required', 'integer', 'min:0'],
            'promotion_pathway' => ['required', 'in:direct,marketplace,both'],
            'is_active' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path && ! str_starts_with($product->image_path, 'http') && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($validated['image']);

        $validated['locked_commission_amount'] = round(($validated['price'] * $validated['locked_commission_percent']) / 100, 2);
        $product->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui.',
                'product' => $product,
            ]);
        }

        return redirect()->route('superadmin.products.index')
            ->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    /**
     * Delete product.
     */
    public function destroy(Request $request, Product $product): Response|RedirectResponse
    {
        abort_unless($request->user() && $request->user()->isSuperadmin(), 403, 'Unauthorized.');

        $product->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus.']);
        }

        return redirect()->route('superadmin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Toggle public e-commerce publication status of a product.
     * Requires Google Drive bank content link to be present before publishing.
     */
    public function togglePublish(Request $request, Product $product): Response|RedirectResponse
    {
        abort_unless($request->user() && $request->user()->isSuperadmin(), 403, 'Unauthorized.');

        if (! $product->is_active) {
            // Check if product has at least one valid Google Drive link
            $hasDriveContent = $product->contentBanks()
                ->where(function ($q) {
                    $q->whereNotNull('external_url')->where('external_url', '!=', '');
                })
                ->exists();

            if (! $hasDriveContent) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk belum memiliki link Google Drive Bank Konten. Silakan lengkapi Bank Konten terlebih dahulu sebelum mempublikasikan ke E-Commerce.',
                    ], 422);
                }

                return redirect()->route('superadmin.products.edit', $product->id)
                    ->with('error', 'Produk belum memiliki link Google Drive Bank Konten. Silakan tambahkan link Google Drive di bagian Bank Konten terlebih dahulu agar materi promosi tersedia untuk kreator sebelum produk dipublikasikan ke E-Commerce.');
            }

            $product->update([
                'is_active' => true,
                'verification_status' => 'approved',
            ]);

            $message = "Produk '{$product->name}' berhasil dipublikasikan ke E-Commerce / Katalog Publik!";
        } else {
            $product->update(['is_active' => false]);
            $message = "Produk '{$product->name}' berhasil ditarik (Unpublished) dari E-Commerce.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $product->is_active,
            ]);
        }

        return back()->with('success', $message);
    }
}
