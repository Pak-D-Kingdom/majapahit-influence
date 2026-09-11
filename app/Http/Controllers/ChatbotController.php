<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\KolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'role' => 'required|in:kol,brand',
        ]);

        $apiKey = config('services.groq.api_key');
        $model = config('services.groq.model', 'openai/gpt-oss-120b');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'reply' => 'Layanan Prabu AI belum aktif karena GROQ_API_KEY belum dikonfigurasi di file .env. Silakan tambahkan GROQ_API_KEY pada file .env Anda.',
            ]);
        }

        $user = $request->user();
        $userRole = $request->role;
        $message = $request->message;
        $history = $request->input('history', []);

        // Gather context
        $context = '';
        $userName = $user->name;

        if ($userRole === 'kol') {
            $profile = $user->kolProfile;
            if ($profile) {
                $userName = $profile->nickname ?: $user->name;
                $endorsements = $profile->endorsements();
                $activeStatuses = ['assigned', 'in_progress', 'content_submitted', 'content_approved'];

                $activeCount = (clone $endorsements)->whereIn('status', $activeStatuses)->count();
                $pendingCount = (clone $endorsements)->whereIn('status', ['assigned', 'in_progress', 'content_rejected'])->count();
                $monthCommission = $profile->commissions()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_amount');
                $unreadNotifs = $user->notifications()->where('is_read', false)->count();

                // Calculate Leaderboard Rank for current month
                $allKols = KolProfile::query()
                    ->where('status', 'aktif')
                    ->with(['socialMedia'])
                    ->withCount([
                        'endorsements as completed_endorsements_count' => function ($q) {
                            $q->whereIn('status', ['selesai', 'completed', 'content_approved', 'approved'])
                                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                        },
                        'endorsements as total_endorsements_count' => function ($q) {
                            $q->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                        },
                    ])
                    ->withSum([
                        'commissions as total_commission' => function ($q) {
                            $q->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                        },
                    ], 'commission_amount')
                    ->get();

                $rankedKols = $allKols->map(function ($k) {
                    $completed = (int) $k->completed_endorsements_count;
                    $totalEndorsements = (int) $k->total_endorsements_count;
                    $totalFollowers = (int) $k->socialMedia->sum('followers_count');
                    $commissionsSum = (float) ($k->total_commission ?? 0);

                    $score = ($completed * 150)
                        + ($totalEndorsements * 30)
                        + min(200, (int) ($totalFollowers / 5000))
                        + min(300, (int) ($commissionsSum / 100000));

                    $k->score = $score;

                    return $k;
                })->sortByDesc('score')->values();

                $myRankIndex = $rankedKols->search(fn ($k) => $k->id === $profile->id);
                $myRank = $myRankIndex !== false ? ($myRankIndex + 1) : '-';
                $myScore = $rankedKols->firstWhere('id', $profile->id)?->score ?? 0;
                $tierName = $profile->tier?->name ?? 'Standard';

                $context = "Role: KOL / Influencer\n";
                $context .= "Nama: $userName\n";
                $context .= "Tier: $tierName\n";
                $context .= "Statistik Anda Bulan Ini:\n";
                $context .= "- Peringkat Leaderboard: Peringkat #$myRank dari {$rankedKols->count()} KOL (Skor: $myScore poin)\n";
                $context .= "- Endorsement Aktif: $activeCount\n";
                $context .= "- Tugas Pending / Perlu Tindakan: $pendingCount\n";
                $context .= '- Total Komisi Bulan Ini: Rp '.number_format($monthCommission, 0, ',', '.')."\n";
                $context .= "- Notifikasi Belum Dibaca: $unreadNotifs\n";
            }
        } else {
            $brand = $user->brand ?? Brand::where('pic_email', $user->email)->first();
            if ($brand) {
                $userName = $brand->name;
                $campaignCount = $brand->campaigns()->count();
                $endorsementCount = $brand->endorsements()->count();
                $pendingProducts = $brand->products()->where('verification_status', 'pending')->count();
                $approvedProducts = $brand->products()->where('verification_status', 'approved')->count();
                $unreadNotifs = $user->notifications()->where('is_read', false)->count();

                $context = "Role: Brand\n";
                $context .= "Nama Brand: $userName\n";
                $context .= "Statistik Anda:\n";
                $context .= "- Total Campaign: $campaignCount\n";
                $context .= "- Total Endorsement: $endorsementCount\n";
                $context .= "- Produk Aktif / Disetujui: $approvedProducts\n";
                $context .= "- Produk Menunggu Verifikasi Admin: $pendingProducts\n";
                $context .= "- Notifikasi Belum Dibaca: $unreadNotifs\n";
            }
        }

        $systemPrompt = "Anda adalah Prabu AI, asisten virtual resmi dan cerdas dari platform KERAJAAN (Influencer Marketing Platform).\nAnda membantu pengguna ($userName) memahami data mereka dan menavigasi dashboard secara ramah dan profesional.\n\n";
        $systemPrompt .= "Konteks Pengguna Saat Ini:\n$context\n\n";
        $systemPrompt .= "Instruksi Khusus:\n";
        $systemPrompt .= "1. Gunakan Bahasa Indonesia yang ramah, sopan, dan jelas. Jawab menggunakan paragraf dan poin-poin (bullet points) agar terstruktur rapi.\n";
        $systemPrompt .= "2. Jika pengguna menanyakan peringkat atau leaderboard, gunakan data 'Peringkat Leaderboard' di konteks di atas. Beritahukan peringkat, skor, dan semangati pengguna untuk menyelesaikan lebih banyak endorsement!\n";
        $systemPrompt .= "3. Jika pengguna menanyakan panduan/cara melakukan sesuatu, jelaskan langkah-langkahnya secara detail dan sebutkan fitur terkait.\n";
        $systemPrompt .= "4. Untuk tombol navigasi cepat ke halaman terkait, gunakan sintaks Quick Action: [ACTION:Teks Tombol|/url-relatif]. Letakkan di akhir penjelasan Anda. Contoh:\n";
        $systemPrompt .= "   - Leaderboard KOL: [ACTION:Buka Leaderboard|/kol/leaderboard]\n";
        $systemPrompt .= "   - Komisi KOL: [ACTION:Lihat Komisi|/kol/commissions]\n";
        $systemPrompt .= "   - Endorsement KOL: [ACTION:Daftar Endorsement|/kol/endorsements]\n";
        $systemPrompt .= "   - Campaign Brand: [ACTION:Kelola Campaign|/brand/campaigns]\n";
        $systemPrompt .= "   - Produk Brand: [ACTION:Kelola Produk|/brand/products]\n";
        $systemPrompt .= "5. Jawab secara padat, informatif, dan akurat berdasarkan konteks yang diberikan.\n";

        // Prepare messages for Groq API
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Append history (limit to last 10 interactions to save tokens)
        $history = array_slice($history, -10);
        foreach ($history as $chat) {
            if (isset($chat['role']) && isset($chat['content'])) {
                $messages[] = ['role' => $chat['role'], 'content' => $chat['content']];
            }
        }

        // Add the current user message
        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $response = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.5,
                    'max_tokens' => 1024,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $choice = $data['choices'][0]['message'] ?? [];
                $reply = $choice['content'] ?? null;

                if (empty($reply) && ! empty($choice['reasoning'])) {
                    $reply = $choice['reasoning'];
                }

                if (empty($reply)) {
                    $reply = 'Maaf, saya tidak dapat memproses jawaban saat ini. Silakan coba lagi.';
                }

                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                ]);
            } else {
                \Log::error('Groq API Error', ['status' => $response->status(), 'body' => $response->body()]);

                $errorBody = $response->json();
                $errorMsg = $errorBody['error']['message'] ?? 'Gangguan komunikasi dengan server AI.';

                return response()->json([
                    'success' => false,
                    'reply' => 'Maaf, terjadi kendala pada layanan AI: '.$errorMsg,
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Chatbot Exception', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'reply' => 'Terjadi kesalahan sistem saat menghubungi chatbot: '.$e->getMessage(),
            ], 500);
        }
    }
}
