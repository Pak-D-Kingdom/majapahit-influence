<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CatalogController extends Controller
{
    /**
     * Display the Evermos-style E-Commerce / Product Catalog.
     */
    public function index(Request $request): View|Response
    {
        $categories = ProductCategory::where('is_active', true)
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        $query = Product::where('is_active', true)
            ->with(['brand', 'category']);

        // Filter by Category
        if ($request->filled('category')) {
            $catSlug = $request->query('category');
            $query->whereHas('category', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug)->orWhere('id', $catSlug);
            });
        }

        // Filter by Promotion Pathway
        if ($request->filled('pathway')) {
            $pathway = $request->query('pathway');
            if (in_array($pathway, ['direct', 'marketplace'])) {
                $query->where(function ($q) use ($pathway) {
                    $q->where('promotion_pathway', $pathway)
                        ->orWhere('promotion_pathway', 'both');
                });
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($b) use ($search) {
                        $b->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        $sort = $request->query('sort', 'newest');
        match ($sort) {
            'commission_high' => $query->orderByDesc('locked_commission_amount'),
            'commission_percent' => $query->orderByDesc('locked_commission_percent'),
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'categories' => $categories,
                'products' => $products,
            ]);
        }

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = $categories->firstWhere('slug', $request->query('category'))
                ?? $categories->firstWhere('id', $request->query('category'));
        }

        return view('catalog.index', compact('categories', 'products', 'activeCategory'));
    }

    /**
     * Display the specified Product detail.
     */
    public function show(Request $request, Product $product): View|Response
    {
        $product->load(['brand', 'category', 'contentBanks']);

        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with(['brand', 'category'])
            ->take(4)
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'product' => $product,
                'related_products' => $relatedProducts,
            ]);
        }

        return view('catalog.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display the dedicated Content Bank page for a Product.
     */
    public function contentBank(Request $request, Product $product): View|Response
    {
        $product->load(['brand', 'category', 'contentBanks']);

        if ($request->wantsJson()) {
            return response()->json([
                'product' => $product,
                'content_banks' => $product->contentBanks,
            ]);
        }

        return view('catalog.content_bank', compact('product'));
    }
}
