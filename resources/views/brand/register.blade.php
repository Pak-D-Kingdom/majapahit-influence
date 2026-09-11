@extends('layouts.app')

@section('title', 'Pendaftaran Brand & Kemitraan | KERAJAAN')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50/50 via-white to-slate-50 flex flex-col justify-between py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto w-full">
        
        {{-- Header Navigation & Brand Mark --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 mb-4 group transition-transform hover:scale-105">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3-nobg.png') }}" alt="KERAJAAN" class="h-12 w-auto object-contain">
                <span class="text-[11px] font-bold tracking-[0.22em] text-[#0c3685] uppercase">
                    KERAJAAN &bull; CREATOR COMMERCE
                </span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-[#0c3685] tracking-tight">Form Pendaftaran Kemitraan Brand</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-2 max-w-lg mx-auto leading-relaxed">
                Promosikan produk Anda lewat ribuan kreator terverifikasi atau buat produk kustom impian Anda bersama ekosistem KERAJAAN.
            </p>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-blue-950/5 border border-blue-100/80 p-6 sm:p-10 backdrop-blur-sm">
            
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    <div class="font-bold mb-1 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-sm"></i> Mohon lengkapi data dengan benar:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('brand.register.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Section 1: Kebutuhan Layanan --}}
                <div class="space-y-3">
                    <label class="block text-xs font-black uppercase tracking-wider text-[#0c3685]">
                        1. Pilihan Kebutuhan Layanan <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid sm:grid-cols-3 gap-3" id="serviceNeedContainer">
                        @php
                            $selectedNeed = old('service_need', request('need', 'both'));
                        @endphp

                        <label class="service-need-card relative flex flex-col justify-between p-4 border-2 rounded-2xl cursor-pointer transition-all {{ $selectedNeed === 'endorsement' ? 'border-[#0b64d4] bg-blue-50/70 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white hover:border-blue-300' }} has-[:checked]:border-[#0b64d4] has-[:checked]:bg-blue-50/70 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#0c3685] flex items-center gap-1.5">
                                        <i class="bi bi-megaphone-fill text-[#0b64d4]"></i> Promosi Produk
                                    </span>
                                    <input type="radio" name="service_need" value="endorsement" class="w-4 h-4 text-[#0b64d4] accent-[#0b64d4] border-slate-300 focus:ring-[#0b64d4] cursor-pointer" {{ $selectedNeed === 'endorsement' ? 'checked' : '' }} required>
                                </div>
                                <span class="text-[11px] text-slate-500 mt-2 block leading-relaxed">Produk sudah siap & ingin dipromosikan KOL.</span>
                            </div>
                        </label>

                        <label class="service-need-card relative flex flex-col justify-between p-4 border-2 rounded-2xl cursor-pointer transition-all {{ $selectedNeed === 'maklon' ? 'border-[#0b64d4] bg-blue-50/70 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white hover:border-blue-300' }} has-[:checked]:border-[#0b64d4] has-[:checked]:bg-blue-50/70 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#0c3685] flex items-center gap-1.5">
                                        <i class="bi bi-gear-wide-connected text-[#0b64d4]"></i> Layanan Maklon
                                    </span>
                                    <input type="radio" name="service_need" value="maklon" class="w-4 h-4 text-[#0b64d4] accent-[#0b64d4] border-slate-300 focus:ring-[#0b64d4] cursor-pointer" {{ $selectedNeed === 'maklon' ? 'checked' : '' }}>
                                </div>
                                <span class="text-[11px] text-slate-500 mt-2 block leading-relaxed">Ingin formulasi & produksi produk baru.</span>
                            </div>
                        </label>

                        <label class="service-need-card relative flex flex-col justify-between p-4 border-2 rounded-2xl cursor-pointer transition-all {{ $selectedNeed === 'both' ? 'border-[#0b64d4] bg-blue-50/70 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white hover:border-blue-300' }} has-[:checked]:border-[#0b64d4] has-[:checked]:bg-blue-50/70 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#0c3685] flex items-center gap-1.5">
                                        <i class="bi bi-stars text-[#0b64d4]"></i> Keduanya (All-in-One)
                                    </span>
                                    <input type="radio" name="service_need" value="both" class="w-4 h-4 text-[#0b64d4] accent-[#0b64d4] border-slate-300 focus:ring-[#0b64d4] cursor-pointer" {{ $selectedNeed === 'both' ? 'checked' : '' }}>
                                </div>
                                <span class="text-[11px] text-slate-500 mt-2 block leading-relaxed">Maklon produk + langsung dipasarkan KOL.</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Section 2: Data Brand / Perusahaan --}}
                <div class="pt-5 border-t border-slate-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#0c3685]">2. Profil Brand & Usaha</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="brand_name" class="block text-xs font-bold text-slate-700 mb-1">
                                Nama Brand <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="brand_name" name="brand_name" value="{{ old('brand_name') }}" placeholder="Contoh: GlowUp Naturals" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white" required>
                        </div>

                        <div>
                            <label for="company_name" class="block text-xs font-bold text-slate-700 mb-1">
                                Nama Perusahaan / Badan Usaha (Opsional)
                            </label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT Glow Mandiri Sejahtera" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                    </div>

                    <div>
                        <label for="industry_category" class="block text-xs font-bold text-slate-700 mb-1">
                            Kategori Industri Produk <span class="text-rose-500">*</span>
                        </label>
                        <select id="industry_category" name="industry_category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white cursor-pointer" required>
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
                            <label for="social_media" class="block text-xs font-bold text-slate-700 mb-1">
                                Akun Instagram / TikTok Brand (Opsional)
                            </label>
                            <input type="text" id="social_media" name="social_media" value="{{ old('social_media') }}" placeholder="@brand.official" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>

                        <div>
                            <label for="website" class="block text-xs font-bold text-slate-700 mb-1">
                                Website / Link Toko Online (Opsional)
                            </label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Kontak PIC (Penanggung Jawab) --}}
                <div class="pt-5 border-t border-slate-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#0c3685]">3. Kontak Penanggung Jawab (PIC)</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_name" class="block text-xs font-bold text-slate-700 mb-1">
                                Nama PIC <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" placeholder="Contoh: Rina Novitasari" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white" required>
                        </div>

                        <div>
                            <label for="pic_title" class="block text-xs font-bold text-slate-700 mb-1">
                                Jabatan PIC
                            </label>
                            <input type="text" id="pic_title" name="pic_title" value="{{ old('pic_title') }}" placeholder="Contoh: Owner / Marketing Manager" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_email" class="block text-xs font-bold text-slate-700 mb-1">
                                Email Bisnis <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="pic_email" name="pic_email" value="{{ old('pic_email') }}" placeholder="rina@brand.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white" required>
                        </div>

                        <div>
                            <label for="pic_phone" class="block text-xs font-bold text-slate-700 mb-1">
                                No. WhatsApp PIC <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" id="pic_phone" name="pic_phone" value="{{ old('pic_phone') }}" placeholder="081234567890" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white" required>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Akun Portal Brand --}}
                <div class="pt-5 border-t border-slate-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#0c3685]">4. Akun Portal Brand</h3>
                    <p class="text-[11px] text-slate-500 mb-3">Buat kata sandi untuk login ke portal Brand setelah pendaftaran Anda disetujui.</p>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                                Password Login <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white" required minlength="8">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                                Konfirmasi Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white" required minlength="8">
                        </div>
                    </div>
                </div>

                {{-- Section 5: Catatan & Rencana --}}
                <div class="pt-5 border-t border-slate-100 space-y-2">
                    <label for="notes" class="block text-xs font-bold text-slate-700">
                        Deskripsi Singkat Rencana Promosi / Kebutuhan Produk (Opsional)
                    </label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Ceritakan target pasar Anda, rencana peluncuran produk, atau estimasi kebutuhan maklon..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">{{ old('notes') }}</textarea>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#0b64d4] to-[#1698f6] hover:from-[#0c3685] hover:to-[#0b64d4] text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md shadow-blue-600/20 flex items-center justify-center gap-2">
                        <span>Kirim Pengajuan Kemitraan Brand</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                    <p class="text-center text-[11px] text-slate-500 mt-3">
                        Dengan mengirimkan form ini, data Anda tersimpan aman dan tim kemitraan KERAJAAN akan meninjau pengajuan Anda.
                    </p>
                </div>

            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#0b64d4] transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
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
                    card.classList.remove('border-slate-200', 'bg-white');
                    card.classList.add('border-[#0b64d4]', 'bg-blue-50/70', 'ring-2', 'ring-blue-500/20');
                } else {
                    card.classList.remove('border-[#0b64d4]', 'bg-blue-50/70', 'ring-2', 'ring-blue-500/20');
                    card.classList.add('border-slate-200', 'bg-white');
                }
            });
        }

        radios.forEach((radio) => {
            radio.addEventListener('change', updateRadioCards);
        });

        cards.forEach((card) => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName.toLowerCase() === 'input') {
                    return;
                }
                const radio = this.querySelector('input[name="service_need"]');
                if (radio && !radio.checked) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });

        updateRadioCards();
    });
</script>
@endpush
@endsection
