<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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

                $context = "Role: KOL\n";
                $context .= "Nama: $userName\n";
                $context .= "Statistik Anda:\n";
                $context .= "- Endorsement Aktif: $activeCount\n";
                $context .= "- Tugas Pending: $pendingCount\n";
                $context .= '- Komisi Bulan Ini: Rp '.number_format($monthCommission, 0, ',', '.')."\n";
                $context .= "- Notifikasi Belum Dibaca: $unreadNotifs\n";
            }
        } else {
            $brand = $user->brand ?? Brand::where('pic_email', $user->email)->first();
            if ($brand) {
                $userName = $brand->name;
                $campaignCount = $brand->campaigns()->count();
                $endorsementCount = $brand->endorsements()->count();
                $pendingProducts = $brand->products()->where('verification_status', 'pending')->count();
                $unreadNotifs = $user->notifications()->where('is_read', false)->count();

                $context = "Role: Brand\n";
                $context .= "Nama Brand: $userName\n";
                $context .= "Statistik Anda:\n";
                $context .= "- Total Campaign: $campaignCount\n";
                $context .= "- Total Endorsement: $endorsementCount\n";
                $context .= "- Produk Pending (Verifikasi): $pendingProducts\n";
                $context .= "- Notifikasi Belum Dibaca: $unreadNotifs\n";
            }
        }

        $systemPrompt = "Anda adalah Prabu AI, asisten virtual resmi dari platform KERAJAAN. Anda membantu pengguna ($userName) menavigasi dashboard mereka.\n\n";
        $systemPrompt .= "Konteks Pengguna Saat Ini:\n$context\n\n";
        $systemPrompt .= "Instruksi Khusus:\n";
        $systemPrompt .= "1. Gunakan Bahasa Indonesia yang ramah dan profesional. Jawab secara rapi menggunakan paragraf dan poin-poin (bullet points/numbered lists) agar mudah dibaca.\n";
        $systemPrompt .= "2. Jika pengguna menanyakan panduan atau cara melakukan sesuatu (misal: cara upload bukti, cara cek komisi), JANGAN HANYA memberikan tombol navigasi. Anda WAJIB menjelaskan langkah-langkahnya secara detail, sebutkan fitur apa saja yang ada di halaman tersebut, lalu berikan tombol navigasi di AKHIR jawaban.\n";
        $systemPrompt .= "3. Jika pengguna menanyakan data atau statistik mereka, gunakan informasi dari 'Konteks Pengguna Saat Ini'. Jika data tidak ada di konteks, katakan Anda tidak memiliki akses ke data tersebut saat ini.\n";
        $systemPrompt .= "4. Jika Anda ingin merekomendasikan halaman untuk dikunjungi, gunakan sintaks Quick Action: [ACTION:Teks Tombol|/url-relatif]. Contoh: [ACTION:Buka Halaman Komisi|/kol/commissions]. Jangan gunakan format markdown link biasa untuk navigasi utama dashboard.\n";
        $systemPrompt .= "5. Jawablah sesuai konteks platform influencer marketing KERAJAAN.\n";

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

        $apiKey = config('services.groq.api_key');
        $model = config('services.groq.model', 'llama-3.3-70b-versatile');

        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.5,
                    'max_tokens' => 800,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat memproses jawaban saat ini.';

                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                ]);
            } else {
                \Log::error('Groq API Error', ['status' => $response->status(), 'body' => $response->body()]);

                return response()->json([
                    'success' => false,
                    'reply' => 'Maaf, layanan chatbot sedang mengalami gangguan komunikasi dengan server AI.',
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Chatbot Exception', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'reply' => 'Terjadi kesalahan sistem saat menghubungi chatbot.',
            ], 500);
        }
    }
}
