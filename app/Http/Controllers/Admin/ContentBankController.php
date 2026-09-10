<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBank;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentBankController extends Controller
{
    /**
     * Store a new content bank Google Drive link with description.
     */
    public function store(Request $request, Product $product): Response|RedirectResponse
    {
        abort_unless($request->user() && $request->user()->isSuperadmin(), 403, 'Unauthorized.');

        $validated = $request->validate([
            'external_url' => ['required', 'url', 'max:500'],
            'content_text' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $title = ! empty($validated['title']) ? $validated['title'] : 'Bank Konten Resmi Produk';

        $contentBank = ContentBank::create([
            'brand_id' => $product->brand_id,
            'product_id' => $product->id,
            'title' => $title,
            'asset_type' => 'drive_link',
            'file_path' => null,
            'external_url' => $validated['external_url'],
            'content_text' => $validated['content_text'] ?? null,
            'file_size' => 0,
            'mime_type' => null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Link Google Drive Bank Konten berhasil ditambahkan.',
                'content_bank' => $contentBank,
            ], 201);
        }

        return redirect()->route('superadmin.products.edit', $product->id)
            ->with('success', 'Link Google Drive Bank Konten berhasil disimpan.');
    }

    /**
     * Update an existing content bank Google Drive link and description.
     */
    public function update(Request $request, ContentBank $contentBank): Response|RedirectResponse
    {
        abort_unless($request->user() && $request->user()->isSuperadmin(), 403, 'Unauthorized.');

        $validated = $request->validate([
            'external_url' => ['required', 'url', 'max:500'],
            'content_text' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $updateData = [
            'external_url' => $validated['external_url'],
            'content_text' => $validated['content_text'] ?? null,
        ];

        if (! empty($validated['title'])) {
            $updateData['title'] = $validated['title'];
        }

        $contentBank->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bank Konten berhasil diperbarui.',
                'content_bank' => $contentBank,
            ]);
        }

        return redirect()->route('superadmin.products.edit', $contentBank->product_id)
            ->with('success', 'Link & Keterangan Google Drive Bank Konten berhasil diperbarui.');
    }

    /**
     * Delete a content bank asset.
     */
    public function destroy(Request $request, ContentBank $contentBank): Response|RedirectResponse
    {
        abort_unless($request->user() && $request->user()->isSuperadmin(), 403, 'Unauthorized.');

        $productId = $contentBank->product_id;
        $contentBank->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bank Konten berhasil dihapus.',
            ]);
        }

        return redirect()->route('superadmin.products.edit', $productId)
            ->with('success', 'Bank Konten berhasil dihapus dari produk ini.');
    }
}
