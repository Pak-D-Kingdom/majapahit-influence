@extends('layouts.auth')

@section('title', 'Daftar sebagai KOL - Majapahit Influence')

@section('left_content')
    <div class="space-y-6">
        <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
            Bergabung dengan <br> 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">
                Ekosistem Kreatif
            </span> <br>
            Terbesar.
        </h1>
        <p class="text-gray-300 text-lg leading-relaxed max-w-md">
            Dapatkan kesempatan bekerja sama dengan brand ternama, tingkatkan exposure Anda, dan raih penghasilan lebih.
        </p>
        
        <div class="flex items-center gap-4 mt-8 pt-8 border-t border-white/10">
            <div class="flex -space-x-3">
                <img class="w-10 h-10 rounded-full border-2 border-zinc-900 object-cover" src="https://i.pravatar.cc/100?img=1" alt="Avatar">
                <img class="w-10 h-10 rounded-full border-2 border-zinc-900 object-cover" src="https://i.pravatar.cc/100?img=2" alt="Avatar">
                <img class="w-10 h-10 rounded-full border-2 border-zinc-900 object-cover" src="https://i.pravatar.cc/100?img=3" alt="Avatar">
                <div class="w-10 h-10 rounded-full border-2 border-zinc-900 bg-zinc-800 flex items-center justify-center text-xs font-bold text-white">+5k</div>
            </div>
            <div class="text-sm font-medium text-gray-400">
                KOL sudah bergabung
            </div>
        </div>
    </div>
@endsection

@section('content')
<div x-data="kolRegistrationForm()" class="w-full">
    
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Pendaftaran KOL</h2>
        <p class="text-gray-500 mt-2">Lengkapi data Anda di bawah ini untuk memulai.</p>
    </div>

    @if ($errors->any())
    <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-100 flex gap-3">
        <i class="bi bi-exclamation-circle-fill text-red-500 mt-0.5"></i>
        <div>
            <h3 class="text-sm font-bold text-red-800">Terdapat kesalahan pada input Anda:</h3>
            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Progress Steps -->
    <div class="mb-10 relative">
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-100">
            <div :style="`width: ${((step - 1) / 2) * 100}%`" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-orange-500 to-red-600 transition-all duration-500 ease-in-out"></div>
        </div>
        <div class="flex justify-between text-xs font-semibold text-gray-400 px-1">
            <span :class="{'text-gray-900': step >= 1}">Informasi Pribadi</span>
            <span :class="{'text-gray-900': step >= 2}">Sosial Media</span>
            <span :class="{'text-gray-900': step >= 3}">Detail Niche</span>
        </div>
    </div>

    <form id="registrationForm" action="{{ route('public.kol.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- STEP 1: Personal Info -->
        <div x-show="step === 1" x-transition:enter="step-enter-active" x-transition:enter-start="step-enter" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="space-y-2">
                    <label for="full_name" class="block text-sm font-semibold text-gray-900">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors shadow-sm"
                        placeholder="Cth: John Doe">
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-900">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors shadow-sm"
                        placeholder="nama@email.com">
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-semibold text-gray-900">No. WhatsApp <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">+62</span>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors shadow-sm"
                            placeholder="8123456789">
                    </div>
                </div>

                <!-- City -->
                <div class="space-y-2">
                    <label for="city" class="block text-sm font-semibold text-gray-900">Kota Domisili</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors shadow-sm"
                        placeholder="Cth: Jakarta Selatan">
                </div>
            </div>
            
            <div class="pt-6">
                <button type="button" @click="nextStep()" class="w-full sm:w-auto px-8 py-3.5 bg-gray-900 hover:bg-black text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    Lanjut ke Sosial Media <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: Social Media -->
        <div x-show="step === 2" x-transition:enter="step-enter-active" x-transition:enter-start="step-enter" class="space-y-6" style="display: none;">
            
            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5 mb-6">
                <div class="flex gap-3">
                    <i class="bi bi-info-circle-fill text-blue-500 mt-0.5"></i>
                    <p class="text-sm text-blue-900 leading-relaxed">
                        Tambahkan akun sosial media utama Anda. Anda bisa menambahkan lebih dari satu platform. Semakin detail, semakin besar peluang Anda.
                    </p>
                </div>
            </div>

            <!-- Social Media Repeater -->
            <div class="space-y-5">
                <template x-for="(sm, index) in socialMedia" :key="index">
                    <div class="p-5 bg-white border border-gray-100 rounded-2xl shadow-sm relative group hover:border-orange-200 transition-colors">
                        
                        <button type="button" @click="removeSocialMedia(index)" x-show="socialMedia.length > 1" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors p-1">
                            <i class="bi bi-trash3-fill"></i>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Platform -->
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Platform</label>
                                <select :name="`social_media[${index}][platform]`" x-model="sm.platform" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors font-medium">
                                    <option value="">Pilih Platform</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="tiktok">TikTok</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="twitter">X (Twitter)</option>
                                </select>
                            </div>

                            <!-- Followers -->
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Followers</label>
                                <input type="number" :name="`social_media[${index}][followers_count]`" x-model="sm.followers" required min="0"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors font-medium"
                                    placeholder="Cth: 15000">
                            </div>

                            <!-- Username -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 font-medium">@</span>
                                    <input type="text" :name="`social_media[${index}][username]`" x-model="sm.username" required
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors font-medium"
                                        placeholder="usernameanda">
                                </div>
                            </div>
                            
                            <!-- URL -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Link Profil URL</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="bi bi-link-45deg"></i></span>
                                    <input type="url" :name="`social_media[${index}][profile_url]`" x-model="sm.url" required
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors font-medium text-sm"
                                        placeholder="https://instagram.com/usernameanda">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <button type="button" @click="addSocialMedia()" class="inline-flex items-center gap-2 text-sm font-bold text-orange-600 hover:text-orange-700 bg-orange-50 hover:bg-orange-100 px-5 py-2.5 rounded-lg transition-colors">
                <i class="bi bi-plus-lg"></i> Tambah Platform Lain
            </button>
            
            <div class="pt-8 flex flex-col-reverse sm:flex-row gap-3">
                <button type="button" @click="prevStep()" class="w-full sm:w-auto px-6 py-3.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>
                <button type="button" @click="nextStep()" class="w-full sm:w-auto px-8 py-3.5 bg-gray-900 hover:bg-black text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    Lanjut ke Niche <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: Niche & Final -->
        <div x-show="step === 3" x-transition:enter="step-enter-active" x-transition:enter-start="step-enter" class="space-y-8" style="display: none;">
            
            <!-- Niches -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-900">Pilih Niche / Kategori Anda <span class="text-red-500">*</span></label>
                <p class="text-xs text-gray-500 -mt-2">Pilih minimal 1 kategori yang paling merepresentasikan konten Anda.</p>
                
                <div class="flex flex-wrap gap-3 mt-3">
                    @foreach($niches as $niche)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="niches[]" value="{{ $niche->name }}" class="peer sr-only" 
                            {{ (is_array(old('niches')) && in_array($niche->name, old('niches'))) ? 'checked' : '' }}>
                        <div class="px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 font-medium text-sm transition-all peer-checked:bg-orange-50 peer-checked:text-orange-700 peer-checked:border-orange-300 hover:border-orange-300 peer-checked:shadow-sm">
                            {{ $niche->name }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Expected Rate -->
            <div class="space-y-2">
                <label for="expected_rate" class="block text-sm font-semibold text-gray-900">Ekspektasi Rate (Opsional)</label>
                <input type="text" name="expected_rate" id="expected_rate" value="{{ old('expected_rate') }}"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors shadow-sm"
                    placeholder="Cth: Rp 500.000 / Reels">
            </div>

            <!-- Join Reason -->
            <div class="space-y-2">
                <label for="join_reason" class="block text-sm font-semibold text-gray-900">Mengapa Anda ingin bergabung? <span class="text-red-500">*</span></label>
                <textarea name="join_reason" id="join_reason" rows="3" required minlength="20"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors shadow-sm resize-none"
                    placeholder="Ceritakan singkat motivasi Anda...">{{ old('join_reason') }}</textarea>
                <p class="text-xs text-gray-400">Minimal 20 karakter.</p>
            </div>

            <!-- Portfolio Upload -->
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-gray-900">Upload Portfolio / Rate Card <span class="text-red-500">*</span></label>
                
                <div class="relative group">
                    <input type="file" name="portfolio[]" id="portfolio" multiple accept=".pdf,.jpg,.jpeg,.png" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="updateFileNames">
                    <div class="border-2 border-dashed border-gray-300 group-hover:border-orange-400 rounded-xl p-8 text-center transition-colors bg-gray-50/50">
                        <div class="w-12 h-12 bg-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-3 text-orange-500">
                            <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-700">Klik untuk upload atau drag & drop file</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max. 5MB, maks 5 file)</p>
                    </div>
                </div>
                
                <div x-show="files.length > 0" class="mt-4 bg-white rounded-xl border border-gray-100 p-2 shadow-sm" style="display: none;">
                    <ul class="text-sm text-gray-600 divide-y divide-gray-50">
                        <template x-for="file in files">
                            <li class="py-2 px-3 flex items-center gap-3">
                                <i class="bi bi-file-earmark-check text-green-500"></i>
                                <span x-text="file" class="font-medium truncate"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- Agreement -->
            <div class="mt-8 bg-gray-50 p-5 rounded-xl border border-gray-200">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="agreement" required class="mt-1 w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <span class="text-sm text-gray-700 leading-relaxed font-medium">
                        Saya menyatakan bahwa data yang saya berikan adalah benar, dan saya menyetujui <a href="#" class="text-orange-600 hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-orange-600 hover:underline">Kebijakan Privasi</a> Majapahit Influence.
                    </span>
                </label>
            </div>
            
            <div class="pt-6 flex flex-col-reverse sm:flex-row gap-3">
                <button type="button" @click="prevStep()" class="w-full sm:w-auto px-6 py-3.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold rounded-xl transition-all shadow-[0_10px_20px_-10px_rgba(234,88,12,0.5)] hover:shadow-[0_15px_25px_-10px_rgba(234,88,12,0.6)] hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    Kirim Pendaftaran <i class="bi bi-send-fill text-sm"></i>
                </button>
            </div>
        </div>

    </form>
</div>

<script>
function kolRegistrationForm() {
    return {
        step: 1,
        files: [],
        socialMedia: [
            { platform: '', followers: '', username: '', url: '' }
        ],
        nextStep() {
            // Basic validation for steps
            if (this.step === 1) {
                const name = document.getElementById('full_name').value;
                const email = document.getElementById('email').value;
                const phone = document.getElementById('phone').value;
                if (!name || !email || !phone) {
                    alert('Harap isi Nama, Email, dan No. HP (wajib).');
                    return;
                }
            }
            if (this.step === 2) {
                // simple array valid
                const sm = this.socialMedia[0];
                if (!sm.platform || !sm.followers || !sm.username || !sm.url) {
                    alert('Lengkapi minimal 1 akun sosial media.');
                    return;
                }
            }
            if (this.step < 3) this.step++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        prevStep() {
            if (this.step > 1) this.step--;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        addSocialMedia() {
            this.socialMedia.push({ platform: '', followers: '', username: '', url: '' });
        },
        removeSocialMedia(index) {
            this.socialMedia.splice(index, 1);
        },
        updateFileNames(e) {
            this.files = Array.from(e.target.files).map(file => file.name);
        }
    }
}
</script>
@endsection
