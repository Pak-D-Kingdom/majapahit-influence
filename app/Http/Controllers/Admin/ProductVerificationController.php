<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductVerificationController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index()
    {
        $products = Product::with('brand')
            ->where('verification_status', 'pending')
            ->latest()
            ->paginate(10);

        return view('superadmin.product-verifications.index', compact('products'));
    }

    public function verify(VerifyProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $this->productService->verifyProduct(
            $product,
            $validated['status'],
            $validated['rejection_reason'] ?? null,
            auth()->user()
        );

        $statusLabel = $validated['status'] === 'approved' ? 'disetujui' : 'ditolak';

        return redirect()->route('superadmin.product-verifications.index')
            ->with('success', "Produk berhasil {$statusLabel}.");
    }
}
