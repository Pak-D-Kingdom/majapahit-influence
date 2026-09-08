@extends('layouts.app')

@section('title', 'Pendaftaran Brand & Kemitraan Maklon — Majapahit Influence')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-between py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto w-full">
        
        {{-- Header Navigation & Brand Mark --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 mb-4 group">
                <div class="w-10 h-10 rounded-xl bg-amber-600 flex items-center justify-center text-white font-black text-lg shadow-md group-hover:scale-105 transition-transform">
                    MI
                </div>
                <div class="text-left leading-tight">
                    <span class="block text-[11px] font-bold tracking-widest text-amber-700">MAJAPAHIT</span>
                    <strong class="block text-base font-extrabold text-gray-900 tracking-tight">INFLUENCE</strong>
                </div>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Form Pendaftaran Kemitraan Brand</h1>
            <p class="text-sm text-gray-600 mt-2 max-w-lg mx-auto">
                Promosikan produk Anda lewat ribuan kreator bertarget atau buat produk custom impian Anda dengan layanan Maklon Pak De Group.
            </p>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 sm:p-10">
            
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <div class="font-bold mb-1 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i> Mohon lengkapi data dengan benar:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('brand.register.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Section 1: Kebutuhan Layanan --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-700">
                        1. Pilihan Kebutuhan Layanan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid sm:grid-cols-3 gap-3" id="serviceNeedContainer">
                        @php
                            $selectedNeed = old('service_need', request('need', 'both'));
                        @endphp

                        <label class="service-need-card relative flex flex-col justify-between p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-amber-400 {{ $selectedNeed === 'endorsement' ? 'border-amber-600 bg-amber-50/60 ring-2 ring-amber-500/20' : 'border-gray-200 bg-white' }} has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50/60 has-[:checked]:ring-2 has-[:checked]:ring-amber-500/20">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-megaphone-fill text-amber-600"></i> Promosi Produk
                                    </span>
                                    <input type="radio" name="service_need" value="endorsement" class="w-4 h-4 text-amber-600 accent-amber-600 border-gray-300 focus:ring-amber-500 cursor-pointer" {{ $selectedNeed === 'endorsement' ? 'checked' : '' }} required>
                                </div>
                                <span class="text-[11px] text-gray-500 mt-2 block leading-relaxed">Produk sudah siap & ingin dipromosikan KOL.</span>
                            </div>
                        </label>

                        <label class="service-need-card relative flex flex-col justify-between p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-amber-400 {{ $selectedNeed === 'maklon' ? 'border-amber-600 bg-amber-50/60 ring-2 ring-amber-500/20' : 'border-gray-200 bg-white' }} has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50/60 has-[:checked]:ring-2 has-[:checked]:ring-amber-500/20">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-gear-wide-connected text-amber-600"></i> Layanan Maklon
                                    </span>
                                    <input type="radio" name="service_need" value="maklon" class="w-4 h-4 text-amber-600 accent-amber-600 border-gray-300 focus:ring-amber-500 cursor-pointer" {{ $selectedNeed === 'maklon' ? 'checked' : '' }}>
                                </div>
                                <span class="text-[11px] text-gray-500 mt-2 block leading-relaxed">Ingin formulasi & produksi produk baru.</span>
                            </div>
                        </label>

                        <label class="service-need-card relative flex flex-col justify-between p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-amber-400 {{ $selectedNeed === 'both' ? 'border-amber-600 bg-amber-50/60 ring-2 ring-amber-500/20' : 'border-gray-200 bg-white' }} has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50/60 has-[:checked]:ring-2 has-[:checked]:ring-amber-500/20">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-stars text-amber-600"></i> Keduanya (All-in-One)
                                    </span>
                                    <input type="radio" name="service_need" value="both" class="w-4 h-4 text-amber-600 accent-amber-600 border-gray-300 focus:ring-amber-500 cursor-pointer" {{ $selectedNeed === 'both' ? 'checked' : '' }}>
                                </div>
                                <span class="text-[11px] text-gray-500 mt-2 block leading-relaxed">Maklon produk + langsung dipasarkan KOL.</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Section 2: Data Brand / Perusahaan --}}
                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-700">2. Profil Brand & Usaha</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="brand_name" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Brand <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="brand_name" name="brand_name" value="{{ old('brand_name') }}" placeholder="Contoh: GlowUp Naturals" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                        </div>

                        <div>
                            <label for="company_name" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Perusahaan / Badan Usaha (Opsional)
                            </label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT Glow Mandiri Sejahtera" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="industry_category" class="block text-xs font-bold text-gray-700 mb-1">
                            Kategori Industri Produk <span class="text-red-500">*</span>
                        </label>
                        <select id="industry_category" name="industry_category" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white" required>
                            <option value="">-- Pilih Kategori Industri --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->name }}" {{ old('industry_category') == $cat->name ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                            <option value="Lainnya" {{ old('industry_category') == 'Lainnya' ? 'selected' : '' }}>Lainnya / Belum Ditentukan</option>
                        </select>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="social_media" class="block text-xs font-bold text-gray-700 mb-1">
                                Akun Instagram / TikTok Brand (Opsional)
                            </label>
                            <input type="text" id="social_media" name="social_media" value="{{ old('social_media') }}" placeholder="@brand.official" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>

                        <div>
                            <label for="website" class="block text-xs font-bold text-gray-700 mb-1">
                                Website / Link Toko Online (Opsional)
                            </label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Kontak PIC (Penanggung Jawab) --}}
                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-700">3. Kontak Penanggung Jawab (PIC)</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_name" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama PIC <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" placeholder="Contoh: Rina Novitasari" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                        </div>

                        <div>
                            <label for="pic_title" class="block text-xs font-bold text-gray-700 mb-1">
                                Jabatan PIC
                            </label>
                            <input type="text" id="pic_title" name="pic_title" value="{{ old('pic_title') }}" placeholder="Contoh: Owner / Marketing Manager" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_email" class="block text-xs font-bold text-gray-700 mb-1">
                                Email Bisnis <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="pic_email" name="pic_email" value="{{ old('pic_email') }}" placeholder="rina@brand.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                        </div>

                        <div>
                            <label for="pic_phone" class="block text-xs font-bold text-gray-700 mb-1">
                                No. WhatsApp PIC <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="pic_phone" name="pic_phone" value="{{ old('pic_phone') }}" placeholder="081234567890" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Akun Portal Brand --}}
                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-700">4. Akun Portal Brand</h3>
                    <p class="text-[11px] text-gray-500 mb-3">Buat password untuk login ke portal Brand setelah pendaftaran Anda disetujui.</p>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-700 mb-1">
                                Password Login <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required minlength="8">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-1">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required minlength="8">
                        </div>
                    </div>
                </div>

                {{-- Section 5: Catatan & Rencana --}}
                <div class="pt-4 border-t border-gray-100 space-y-2">
                    <label for="notes" class="block text-xs font-bold text-gray-700">
                        Deskripsi Singkat Rencana Promosi / Kebutuhan Produk (Opsional)
                    </label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Ceritakan target pasar Anda, rencana peluncuran produk, atau estimasi jumlah kuantitas maklon yang diinginkan..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">{{ old('notes') }}</textarea>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm shadow-xl shadow-amber-600/20 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                        <i class="bi bi-send-check-fill"></i> Kirim Pengajuan Kemitraan Brand
                    </button>
                    <p class="text-center text-[11px] text-gray-500 mt-3">
                        Dengan mengirimkan form ini, data Anda aman dan tim kemitraan kami akan menghubungi Anda via WhatsApp dalam 1x24 jam kerja.
                    </p>
                </div>

            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-xs font-bold text-gray-600 hover:text-amber-600">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda Utama
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="service_need"]');
        const cards = document.querySelectorAll('.service-need-card');

        function updateRadioCards() {
            radios.forEach((radio) => {
                const card = radio.closest('.service-need-card');
                if (!card) return;
                if (radio.checked) {
                    card.classList.remove('border-gray-200', 'bg-white');
                    card.classList.add('border-amber-600', 'bg-amber-50/60', 'ring-2', 'ring-amber-500/20');
                } else {
                    card.classList.remove('border-amber-600', 'bg-amber-50/60', 'ring-2', 'ring-amber-500/20');
                    card.classList.add('border-gray-200', 'bg-white');
                }
            });
        }

        radios.forEach((radio) => {
            radio.addEventListener('change', updateRadioCards);
        });

        cards.forEach((card) => {
            card.addEventListener('click', function(e) {
                // If clicked directly on the input, change event will handle it
                if (e.target.tagName.toLowerCase() === 'input') {
                    return;
                }
                const radio = this.querySelector('input[name="service_need"]');
                if (radio && !radio.checked) {
                    radio.checked = true;
                    // Trigger change event to ensure any listener fires
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });

        updateRadioCards();
    });
</script>
@endpush
@endsection
