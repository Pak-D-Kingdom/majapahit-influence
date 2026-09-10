<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductVerificationController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $type = $request->input('type', 'all');

        $query = Product::with(['brand', 'category']);

        if ($type === 'create') {
            $query->where('verification_status', 'pending');
        } elseif ($type === 'update') {
            $query->where('verification_status', 'pending_update');
        } elseif ($type === 'delete') {
            $query->where('verification_status', 'pending_delete');
        } else {
            $query->whereIn('verification_status', ['pending', 'pending_update', 'pending_delete']);
        }

        $products = $query->latest('updated_at')->paginate(10)->withQueryString();

        $createCount = Product::where('verification_status', 'pending')->count();
        $updateCount = Product::where('verification_status', 'pending_update')->count();
        $deleteCount = Product::where('verification_status', 'pending_delete')->count();
        $allCount = $createCount + $updateCount + $deleteCount;

        return view('superadmin.product-verifications.index', compact(
            'products',
            'type',
            'createCount',
            'updateCount',
            'deleteCount',
            'allCount'
        ));
    }

    public function verify(VerifyProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $actionType = $product->verification_status;

        $this->productService->verifyProduct(
            $product,
            $validated['status'],
            $validated['rejection_reason'] ?? null,
            auth()->user()
        );

        $statusLabel = $validated['status'] === 'approved' ? 'disetujui' : 'ditolak';
        $actionName = match ($actionType) {
            'pending_update' => 'Pengajuan edit produk',
            'pending_delete' => 'Pengajuan hapus produk',
            default => 'Produk baru',
        };

        $redirectParams = [];
        if ($request->filled('filter_type') && $request->input('filter_type') !== 'all') {
            $redirectParams['type'] = $request->input('filter_type');
        }

        return redirect()->route('superadmin.product-verifications.index', $redirectParams)
            ->with('success', "{$actionName} berhasil {$statusLabel}.");
    }
}
