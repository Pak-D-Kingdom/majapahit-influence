<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreProductRequest;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    private function getBrand(): Brand
    {
        $user = auth()->user();
        $brand = $user->brand ?? Brand::where('pic_email', $user->email)->first();

        if ($brand && ! $brand->user_id) {
            $brand->update(['user_id' => $user->id]);
        }

        if (! $brand) {
            $brand = Brand::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'pic_name' => $user->name,
                'pic_email' => $user->email,
                'is_active' => true,
            ]);
        }

        return $brand;
    }

    public function index()
    {
        $products = $this->getBrand()->products()->latest()->paginate(10);

        return view('brand.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->get();

        return view('brand.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $brand = $this->getBrand();
        $image = $request->file('image');

        $this->productService->createProduct($brand, $request->validated(), $image);

        return redirect()->route('brand.products.index')->with('success', 'Produk berhasil ditambahkan dan sedang menunggu verifikasi.');
    }

    public function show(Product $product)
    {
        if ($product->brand_id !== $this->getBrand()->id) {
            abort(403);
        }

        return view('brand.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if ($product->brand_id !== $this->getBrand()->id) {
            abort(403);
        }
        $categories = ProductCategory::where('is_active', true)->get();

        return view('brand.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->brand_id !== $this->getBrand()->id) {
            abort(403);
        }

        if ($product->verification_status === 'pending_delete') {
            return back()->with('error', 'Produk ini sedang dalam proses pengajuan penghapusan dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'locked_commission_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'sku' => ['nullable', 'string', 'max:100'],
        ]);

        $image = $request->file('image');
        $this->productService->requestUpdate($product, $validated, $image);

        return redirect()->route('brand.products.index')
            ->with('success', 'Pengajuan perubahan data produk telah dikirim dan menunggu persetujuan Superadmin.');
    }

    public function destroy(Request $request, Product $product)
    {
        if ($product->brand_id !== $this->getBrand()->id) {
            abort(403);
        }

        $reason = $request->input('deletion_reason', 'Pengajuan penghapusan oleh Brand.');
        $this->productService->requestDeletion($product, $reason);

        return redirect()->route('brand.products.index')
            ->with('success', 'Pengajuan penghapusan produk telah dikirim dan menunggu persetujuan Superadmin.');
    }
}
