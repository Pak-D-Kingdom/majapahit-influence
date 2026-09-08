<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrandRegistration;
use App\Models\ContentBank;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $categories = [
            [
                'name' => 'Beauty & Skincare',
                'slug' => 'beauty-skincare',
                'icon' => 'bi-stars',
                'description' => 'Serum, sunscreen, moisturizer, dan produk kecantikan bersertifikasi BPOM.',
            ],
            [
                'name' => 'Herbal & Kesehatan',
                'slug' => 'herbal-kesehatan',
                'icon' => 'bi-heart-pulse',
                'description' => 'Madu murni, suplemen alami, minuman herbal, dan produk kebugaran.',
            ],
            [
                'name' => 'Food & Beverage (F&B)',
                'slug' => 'food-beverage',
                'icon' => 'bi-cup-hot',
                'description' => 'Camilan viral, kopi artisan, sambal kemasan, dan frozen food.',
            ],
            [
                'name' => 'Fashion & Apparel',
                'slug' => 'fashion-apparel',
                'icon' => 'bi-bag',
                'description' => 'Hijab, pakaian muslim, outer, tas lokal, dan aksesoris harian.',
            ],
            [
                'name' => 'Mom & Baby',
                'slug' => 'mom-baby',
                'icon' => 'bi-emoji-smile',
                'description' => 'Perawatan bayi, popok ramah lingkungan, dan perlengkapan ibu menyusui.',
            ],
            [
                'name' => 'Gadget & Home Living',
                'slug' => 'gadget-home-living',
                'icon' => 'bi-house-check',
                'description' => 'Smart home, diffuser aromaterapi, dan aksesoris gadget kekinian.',
            ],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = ProductCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 2. Ensure Brands Exist
        $brandSkincare = Brand::firstOrCreate(
            ['name' => 'GlowUp Naturals'],
            [
                'industry' => 'Beauty & Skincare',
                'pic_name' => 'Rina Novitasari',
                'pic_title' => 'Brand Manager',
                'pic_email' => 'rina@glowupnaturals.com',
                'pic_phone' => '081234567801',
                'notes' => 'Mitra Maklon Pak De Group Batch 1',
                'is_active' => true,
            ]
        );

        $brandHerbal = Brand::firstOrCreate(
            ['name' => 'Madu Nusantara Makmur'],
            [
                'industry' => 'Herbal & Kesehatan',
                'pic_name' => 'Hendra Pratama',
                'pic_title' => 'Founder',
                'pic_email' => 'hendra@madunusantara.com',
                'pic_phone' => '081234567802',
                'notes' => 'Brand Herbal Unggulan Pak De Group',
                'is_active' => true,
            ]
        );

        $brandFnB = Brand::firstOrCreate(
            ['name' => 'Sambal Majapahit Juara'],
            [
                'industry' => 'Food & Beverage',
                'pic_name' => 'Dewi Sartika',
                'pic_title' => 'Marketing Head',
                'pic_email' => 'dewi@sambaljuara.com',
                'pic_phone' => '081234567803',
                'notes' => 'Produk F&B Viral',
                'is_active' => true,
            ]
        );

        $brandFashion = Brand::firstOrCreate(
            ['name' => 'Majapahit Hijab & Silk'],
            [
                'industry' => 'Fashion & Apparel',
                'pic_name' => 'Annisa Rahma',
                'pic_title' => 'Creative Director',
                'pic_email' => 'annisa@majapahithijab.com',
                'pic_phone' => '081234567804',
                'notes' => 'Koleksi Hijab Premium',
                'is_active' => true,
            ]
        );

        // 3. Products with 40% Locked Commission
        $products = [
            [
                'brand_id' => $brandSkincare->id,
                'category_id' => $catModels['beauty-skincare']->id,
                'name' => 'GlowUp Niacinamide 10% Brightening Serum',
                'slug' => 'glowup-niacinamide-10-serum',
                'sku' => 'GLOW-SRM-01',
                'short_description' => 'Serum pencerah kulit wajah dengan Niacinamide 10% dan Centella Asiatica. Teruji klinis dan BPOM.',
                'description' => 'Serum perawatan intensif untuk menyamarkan noda hitam, mencerahkan warna kulit kusam, dan memperkuat skin barrier. Cocok untuk semua jenis kulit termasuk kulit sensitif.',
                'price' => 120000.00,
                'locked_commission_percent' => 40.00,
                'locked_commission_amount' => 48000.00,
                'image_path' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=600&auto=format&fit=crop&q=80',
                'stock' => 500,
                'promotion_pathway' => 'both',
                'is_active' => true,
            ],
            [
                'brand_id' => $brandSkincare->id,
                'category_id' => $catModels['beauty-skincare']->id,
                'name' => 'GlowUp UV Shield Sunscreen SPF 50+ PA++++',
                'slug' => 'glowup-uv-shield-sunscreen',
                'sku' => 'GLOW-SUN-02',
                'short_description' => 'Sunscreen seringan air, no whitecast, menyerap cepat dan mengontrol minyak 8 jam.',
                'description' => 'Perlindungan maksimal dari radiasi UVA, UVB, dan Blue Light dengan sensasi sejuk. Mengandung Hyaluronic Acid untuk hidrasi sepanjang hari.',
                'price' => 89000.00,
                'locked_commission_percent' => 40.00,
                'locked_commission_amount' => 35600.00,
                'image_path' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&auto=format&fit=crop&q=80',
                'stock' => 350,
                'promotion_pathway' => 'marketplace',
                'is_active' => true,
            ],
            [
                'brand_id' => $brandHerbal->id,
                'category_id' => $catModels['herbal-kesehatan']->id,
                'name' => 'Madu Hutan Liar Murni Nusantara 500g',
                'slug' => 'madu-hutan-liar-murni-500g',
                'sku' => 'MDN-HTN-500',
                'short_description' => '100% Madu mentah (raw honey) asli dari pedalaman hutan Nusantara, kaya antioksidan alami.',
                'description' => 'Madu murni tanpa pemanis buatan atau proses pasteurisasi berlebih, menjaga enzim aktif dan nutrisi untuk daya tahan tubuh dan stamina harian.',
                'price' => 150000.00,
                'locked_commission_percent' => 40.00,
                'locked_commission_amount' => 60000.00,
                'image_path' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&auto=format&fit=crop&q=80',
                'stock' => 200,
                'promotion_pathway' => 'both',
                'is_active' => true,
            ],
            [
                'brand_id' => $brandFnB->id,
                'category_id' => $catModels['food-beverage']->id,
                'name' => 'Sambal Cumi Asin Majapahit Level Pedas Juara 200g',
                'slug' => 'sambal-cumi-asin-majapahit-200g',
                'sku' => 'SB-CMI-200',
                'short_description' => 'Sambal cumi melimpah dengan racikan rempah khas Nusantara. Gurih pedas bikin nagih!',
                'description' => 'Dibuat dari cumi segar pilihan dan cabai rawit merah segar, dimasak higienis dan tahan hingga 6 bulan. Siap saji langsung dengan nasi hangat.',
                'price' => 45000.00,
                'locked_commission_percent' => 40.00,
                'locked_commission_amount' => 18000.00,
                'image_path' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&auto=format&fit=crop&q=80',
                'stock' => 800,
                'promotion_pathway' => 'marketplace',
                'is_active' => true,
            ],
            [
                'brand_id' => $brandFashion->id,
                'category_id' => $catModels['fashion-apparel']->id,
                'name' => 'Majapahit Silk Voile Square Scarf Edition',
                'slug' => 'majapahit-silk-voile-scarf',
                'sku' => 'MJ-SCARF-01',
                'short_description' => 'Hijab silk premium dengan motif klasik modern, tegak di dahi dan lembut adem.',
                'description' => 'Material Ultra-Fine Voile Silk dengan laser-cut finishing rapi. Warna elegan cocok untuk acara kasual maupun formal.',
                'price' => 175000.00,
                'locked_commission_percent' => 40.00,
                'locked_commission_amount' => 70000.00,
                'image_path' => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=600&auto=format&fit=crop&q=80',
                'stock' => 150,
                'promotion_pathway' => 'direct',
                'is_active' => true,
            ],
        ];

        foreach ($products as $pData) {
            $prod = Product::updateOrCreate(['slug' => $pData['slug']], $pData);

            // 4. Seed Content Bank (Google Drive Master Folder) for each product
            ContentBank::firstOrCreate(
                ['product_id' => $prod->id, 'title' => 'Folder Master Google Drive - '.$prod->name],
                [
                    'brand_id' => $prod->brand_id,
                    'asset_type' => 'drive_link',
                    'external_url' => 'https://drive.google.com/drive/folders/sample-'.$prod->slug,
                    'content_text' => "1. Video mentah B-Roll & unboxing 4K (Vertical 9:16)\n2. Foto produk HD Studio & PNG transparan\n3. Naskah script copywriting & talking points FYP TikTok\n4. Dokumen klaim manfaat resmi & sertifikasi BPOM/Halal",
                ]
            );
        }

        // 5. Seed Sample Brand Registrations
        BrandRegistration::firstOrCreate(
            ['pic_email' => 'contact@skinzenith.id'],
            [
                'brand_name' => 'Skin Zenith Naturals',
                'company_name' => 'PT Zenith Herbal Indonesia',
                'industry_category' => 'Beauty & Skincare',
                'pic_name' => 'Budi Santoso',
                'pic_title' => 'CEO & Founder',
                'pic_phone' => '081987654321',
                'social_media' => '@skinzenith.official',
                'website' => 'https://skinzenith.id',
                'service_need' => 'both',
                'notes' => 'Ingin konsultasi maklon toner chamomile baru sebanyak 5.000 pcs dan butuh 30 KOL TikTok untuk peluncuran awal.',
                'status' => 'pending',
            ]
        );

        BrandRegistration::firstOrCreate(
            ['pic_email' => 'halo@keripikmajapahit.com'],
            [
                'brand_name' => 'Keripik Singkong Renyah Nusantara',
                'company_name' => 'CV Sumber Rejeki Rasa',
                'industry_category' => 'Food & Beverage',
                'pic_name' => 'Siti Nurhaliza',
                'pic_title' => 'Owner',
                'pic_phone' => '081223344556',
                'social_media' => '@keripiksingkong.juara',
                'website' => 'https://keripikmajapahit.com',
                'service_need' => 'endorsement',
                'notes' => 'Produk sudah ready stock 10.000 pouch. Ingin ditaruh di katalog e-commerce marketplace Majapahit untuk dipromosikan affiliate influencer.',
                'status' => 'approved',
            ]
        );
    }
}
