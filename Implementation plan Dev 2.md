# Implementation Plan — Dev 2: Manajemen Profil & Akuisisi KOL

## Ringkasan Tugas

Dev 2 bertanggung jawab atas seluruh logika backend yang berhubungan dengan **entitas KOL** — mulai dari pendaftaran publik (tanpa login), proses review/approval oleh Admin, manajemen data profil KOL, hingga pencarian & filter.

## PRD Requirement yang Di-cover

- **7.b.2** — Lifecycle Status KOL (State Machine)
- **7.b.3** — Proses Approval Pendaftaran KOL
- **7.b.10** — Pencarian & Filter KOL (Compound Filter)
- **7.b.7** — Upload & Penyimpanan File (khusus portofolio pendaftaran & foto profil)
- **7.b.9** — Audit Trail (untuk aksi-aksi di domain KOL)

---

## Kondisi Proyek Saat Ini (Yang Sudah Ada)

### Model yang sudah tersedia dan bisa langsung dipakai:

| Model              | File                                                                                               | Helper/Method Penting                                                                                         |
| ------------------ | -------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- |
| `KolProfile`       | [KolProfile.php](file:///c:/laragon/www/majapahit-influence/app/Models/KolProfile.php)             | `getEffectiveCommissionPctAttribute()`, `scopeActive()`, relasi ke `niches()`, `socialMedia()`, `rateCards()` |
| `KolRegistration`  | [KolRegistration.php](file:///c:/laragon/www/majapahit-influence/app/Models/KolRegistration.php)   | `generateRegistrationNumber()` — sudah implementasi format `REG-YYYYMMDD-XXXX`                                |
| `KolSocialMedia`   | [KolSocialMedia.php](file:///c:/laragon/www/majapahit-influence/app/Models/KolSocialMedia.php)     | `$table = 'kol_social_media'` (custom table name)                                                             |
| `KolRateCard`      | [KolRateCard.php](file:///c:/laragon/www/majapahit-influence/app/Models/KolRateCard.php)           | Unique constraint: `(kol_profile_id, platform, content_type)`                                                 |
| `RegistrationFile` | [RegistrationFile.php](file:///c:/laragon/www/majapahit-influence/app/Models/RegistrationFile.php) | FK `registration_id` → `kol_registrations`                                                                    |
| `Niche`            | [Niche.php](file:///c:/laragon/www/majapahit-influence/app/Models/Niche.php)                       | Lookup table, sudah di-seed (14 niche)                                                                        |
| `Tier`             | [Tier.php](file:///c:/laragon/www/majapahit-influence/app/Models/Tier.php)                         | Sudah di-seed (Nano/Micro/Macro/Mega)                                                                         |
| `AuditLog`         | [AuditLog.php](file:///c:/laragon/www/majapahit-influence/app/Models/AuditLog.php)                 | **`AuditLog::log(action, entityType, entityId, oldValues, newValues)`** — helper siap pakai                   |
| `User`             | [User.php](file:///c:/laragon/www/majapahit-influence/app/Models/User.php)                         | `hasRole()`, `isAdmin()`, `isKol()`, `assignRole()`                                                           |

### Data penting di migration:

- `kol_registrations.social_media` → **JSON column** (bukan relasi terpisah). Saat pendaftaran, data sosmed disimpan sebagai JSON. Saat di-approve, data ini dipindahkan ke tabel `kol_social_media`.
- `kol_registrations.niches` → **JSON column** (array of niche strings).
- `kol_profiles.status` → Enum string: `pending`, `aktif`, `nonaktif`, `blacklist`.
- `kol_profiles` menggunakan **SoftDeletes**.

### Route files:

- `routes/superadmin.php` — sudah ada, **masih kosong**. Untuk route Admin.
- `routes/kol.php` — sudah ada, **masih kosong**. Untuk route KOL.

> [!WARNING]
> Kedua file route ini **belum di-register** di [bootstrap/app.php](file:///c:/laragon/www/majapahit-influence/bootstrap/app.php). Dev 1 harus menambahkannya terlebih dahulu. Jika Dev 1 belum selesai, Dev 2 bisa sementara mendaftarkan route-nya di `routes/web.php` lalu pindahkan nanti.

### View layouts:

- `resources/views/superadmin/layouts/` — sudah ada folder (`app.blade.php`, `navbar.blade.php`, `sidebar.blade.php`) tapi **masih kosong**.
- `resources/views/kol/layouts/` — sudah ada folder tapi **masih kosong**.

---

## Daftar File yang Harus Dibuat

### A. Form Requests (Validasi Server-Side)

#### 1. `app/Http/Requests/Public/StoreKolRegistrationRequest.php`

```
php artisan make:request Public/StoreKolRegistrationRequest --no-interaction
```

**Rules:**

```php
public function rules(): array
{
    return [
        'full_name'     => ['required', 'string', 'max:255'],
        'email'         => ['required', 'email', 'max:255'],
        'phone'         => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/'],
        'city'          => ['nullable', 'string', 'max:100'],
        'niches'        => ['required', 'array', 'min:1'],
        'niches.*'      => ['string', 'exists:niches,name'],
        'social_media'              => ['required', 'array', 'min:1'],
        'social_media.*.platform'   => ['required', 'string', 'in:instagram,tiktok,youtube,twitter'],
        'social_media.*.username'   => ['required', 'string', 'max:255'],
        'social_media.*.profile_url'=> ['required', 'url', 'max:500'],
        'social_media.*.followers_count' => ['required', 'integer', 'min:0'],
        'expected_rate' => ['nullable', 'string'],
        'join_reason'   => ['required', 'string', 'min:20'],
        'portfolio'     => ['required', 'array', 'max:5'],
        'portfolio.*'   => ['file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],  // maks 5MB
        'agreement'     => ['required', 'accepted'],
    ];
}
```

#### 2. `app/Http/Requests/Admin/ApproveRegistrationRequest.php`

```
php artisan make:request Admin/ApproveRegistrationRequest --no-interaction
```

**Rules:**

```php
public function rules(): array
{
    return [
        'notes'  => ['nullable', 'string', 'max:1000'],
        'score'  => ['nullable', 'integer', 'between:1,5'],
        'tier_id'=> ['nullable', 'exists:tiers,id'],
    ];
}
```

#### 3. `app/Http/Requests/Admin/RejectRegistrationRequest.php`

```
php artisan make:request Admin/RejectRegistrationRequest --no-interaction
```

**Rules:**

```php
public function rules(): array
{
    return [
        'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
    ];
}
```

#### 4. `app/Http/Requests/Admin/StoreKolManualRequest.php`

```
php artisan make:request Admin/StoreKolManualRequest --no-interaction
```

**Rules:** Sama seperti form edit profil, tapi semua field wajib (nama, email, min 1 socmed, tier, dsb).

#### 5. `app/Http/Requests/Admin/UpdateKolStatusRequest.php`

```
php artisan make:request Admin/UpdateKolStatusRequest --no-interaction
```

**Rules:**

```php
public function rules(): array
{
    return [
        'status'        => ['required', 'in:aktif,nonaktif,blacklist'],
        'status_reason' => ['required_if:status,nonaktif,blacklist', 'nullable', 'string', 'max:1000'],
    ];
}
```

#### 6. `app/Http/Requests/Kol/UpdateProfileRequest.php`

```
php artisan make:request Kol/UpdateProfileRequest --no-interaction
```

**Rules:**

```php
public function rules(): array
{
    return [
        'nickname'      => ['required', 'string', 'max:100'],
        'bio'           => ['nullable', 'string'],
        'city'          => ['nullable', 'string', 'max:100'],
        'province'      => ['nullable', 'string', 'max:100'],
        'photo'         => ['nullable', 'image', 'max:2048'], // maks 2MB
        'social_media'                   => ['required', 'array', 'min:1'],
        'social_media.*.platform'        => ['required', 'string'],
        'social_media.*.username'        => ['required', 'string', 'max:255'],
        'social_media.*.profile_url'     => ['required', 'url'],
        'social_media.*.followers_count' => ['required', 'integer', 'min:0'],
        'social_media.*.engagement_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        'rate_cards'                => ['required', 'array', 'min:1'],
        'rate_cards.*.platform'     => ['required', 'string'],
        'rate_cards.*.content_type' => ['required', 'string'],
        'rate_cards.*.rate'         => ['required', 'numeric', 'min:0'],
        'bank_name'           => ['nullable', 'string', 'max:100'],
        'bank_account_number' => ['nullable', 'string', 'max:50'],
        'bank_account_name'   => ['nullable', 'string', 'max:255'],
    ];
}
```

---

### B. Service Classes (Business Logic)

#### 1. `app/Services/KolRegistrationService.php`

Method-method yang harus diimplementasikan:

**`store(array $data, array $files): KolRegistration`**

- Generate nomor registrasi: pakai `KolRegistration::generateRegistrationNumber()` (sudah ada)
- Simpan data ke `kol_registrations`
- Upload file portofolio ke `storage/app/registrations/{id}/` → simpan metadata ke `registration_files`
- Return model `KolRegistration`

**`approve(KolRegistration $registration, User $admin, array $data): User`**

- Gunakan `DB::transaction()` karena melibatkan banyak tabel:
    1. Update `kol_registrations`: status → `approved`, approved_by, approved_at, notes
    2. Buat `User` baru: name = full_name, email, password = `Str::random(12)` (hashed)
    3. Assign role KOL: `$user->assignRole('kol')` (method sudah ada di User model)
    4. Buat `KolProfile`: user_id, nickname, city, tier_id (tentukan dari data followers), status = `aktif`, joined_at = now()
    5. Migrasi `social_media` JSON → insert ke tabel `kol_social_media` (loop array JSON)
    6. Pindahkan file dari `registrations/{reg_id}/` ke `profiles/{profile_id}/`
    7. Catat audit: `AuditLog::log('kol_registration_approved', 'kol_registration', $registration->id, null, [...])`
    8. _(Opsional, koordinasi dengan Dev 5)_ Trigger notifikasi & email kredensial
- Return `User` yang baru dibuat

**`reject(KolRegistration $registration, User $admin, string $reason): void`**

- Gunakan `DB::transaction()`:
    1. Catat di audit log **SEBELUM delete** (karena setelah delete datanya hilang): `AuditLog::log('kol_registration_rejected', 'kol_registration', $registration->id, ['name' => ..., 'email' => ..., 'reason' => $reason], null)`
    2. Hapus file fisik dari storage: `Storage::deleteDirectory("registrations/{$registration->id}")`
    3. Hard delete: `$registration->files()->delete()` lalu `$registration->delete()`

**`determineTier(int $maxFollowers): ?Tier`**

- Query `Tier` berdasarkan `max_followers >= $maxFollowers` dan `min_followers <= $maxFollowers`
- Return Tier yang sesuai, atau null

---

#### 2. `app/Services/KolProfileService.php`

**`updateProfile(KolProfile $profile, array $data): KolProfile`**

- Update data dasar profil (nickname, bio, city, province, bank info)
- Handle upload foto profil baru (hapus yang lama jika ada)
- Sync social media: hapus yang lama, insert yang baru → `$profile->socialMedia()->delete()` lalu batch create
- Sync rate cards: hapus yang lama, insert yang baru → `$profile->rateCards()->delete()` lalu batch create
- Catat perubahan di audit log (old_values vs new_values)

**`changeStatus(KolProfile $profile, string $newStatus, ?string $reason, User $admin): void`**

- Validasi state machine:
    ```
    Transisi yang diizinkan:
    - aktif    → nonaktif  (wajib alasan)
    - aktif    → blacklist (wajib alasan, IRREVERSIBLE)
    - nonaktif → aktif     (boleh tanpa alasan)
    - nonaktif → blacklist (wajib alasan, IRREVERSIBLE)
    ```
- Jika transisi tidak valid → throw `\InvalidArgumentException`
- Simpan old status, update profil, catat audit log

**`filter(array $filters): Builder`**

- Return Eloquent Builder dengan conditional where clauses:

    ```php
    $query = KolProfile::with(['user', 'tier', 'niches', 'socialMedia']);

    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function ($q) use ($search) {
            $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
              ->orWhere('nickname', 'like', "%{$search}%")
              ->orWhereHas('socialMedia', fn ($s) => $s->where('username', 'like', "%{$search}%"));
        });
    }
    if (!empty($filters['niches'])) {
        $query->whereHas('niches', fn ($q) => $q->whereIn('niches.id', $filters['niches']));
    }
    if (!empty($filters['platforms'])) {
        $query->whereHas('socialMedia', fn ($q) => $q->whereIn('platform', $filters['platforms']));
    }
    if (!empty($filters['followers_min'])) {
        $query->whereHas('socialMedia', fn ($q) => $q->where('followers_count', '>=', $filters['followers_min']));
    }
    if (!empty($filters['followers_max'])) {
        $query->whereHas('socialMedia', fn ($q) => $q->where('followers_count', '<=', $filters['followers_max']));
    }
    if (!empty($filters['tier_id'])) {
        $query->where('tier_id', $filters['tier_id']);
    }
    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }
    if (!empty($filters['city'])) {
        $query->where('city', 'like', "%{$filters['city']}%");
    }

    return $query;
    ```

---

### C. Controllers

#### 1. `app/Http/Controllers/PublicRegistrationController.php`

```
php artisan make:controller PublicRegistrationController --no-interaction
```

| Method                               | Route                    | Deskripsi                                                                           |
| ------------------------------------ | ------------------------ | ----------------------------------------------------------------------------------- |
| `create()`                           | `GET /daftar`            | Tampilkan form pendaftaran. Pass `$niches = Niche::where('is_active', true)->get()` |
| `store(StoreKolRegistrationRequest)` | `POST /daftar`           | Panggil `KolRegistrationService::store()`. Redirect ke konfirmasi                   |
| `confirmation()`                     | `GET /daftar/konfirmasi` | Tampilkan halaman konfirmasi (ambil nomor registrasi dari session flash)            |

#### 2. `app/Http/Controllers/Admin/RegistrationReviewController.php`

```
php artisan make:controller Admin/RegistrationReviewController --no-interaction
```

| Method                                     | Route                                  | Deskripsi                                                    |
| ------------------------------------------ | -------------------------------------- | ------------------------------------------------------------ |
| `index()`                                  | `GET /admin/pendaftaran`               | Daftar pendaftaran. Filter: status, niche, tanggal. Paginate |
| `show($id)`                                | `GET /admin/pendaftaran/{id}`          | Detail pendaftaran + file preview + form approve/reject      |
| `approve($id, ApproveRegistrationRequest)` | `POST /admin/pendaftaran/{id}/approve` | Panggil `KolRegistrationService::approve()`                  |
| `reject($id, RejectRegistrationRequest)`   | `POST /admin/pendaftaran/{id}/reject`  | Panggil `KolRegistrationService::reject()`                   |

#### 3. `app/Http/Controllers/Admin/KolManagementController.php`

```
php artisan make:controller Admin/KolManagementController --no-interaction
```

| Method                                      | Route                          | Deskripsi                                                                                    |
| ------------------------------------------- | ------------------------------ | -------------------------------------------------------------------------------------------- |
| `index()`                                   | `GET /admin/kol`               | Daftar KOL + compound filter. Panggil `KolProfileService::filter()`. Paginate (10/25/50/100) |
| `create()`                                  | `GET /admin/kol/tambah`        | Form tambah KOL manual                                                                       |
| `store(StoreKolManualRequest)`              | `POST /admin/kol`              | Simpan KOL manual (buat User + Profile + SocialMedia + RateCard)                             |
| `show($id)`                                 | `GET /admin/kol/{id}`          | Detail KOL dengan tab: Profil, Endorsement, Komisi, Dokumen, Log                             |
| `edit($id)`                                 | `GET /admin/kol/{id}/edit`     | Form edit KOL                                                                                |
| `update($id, UpdateKolProfileRequest)`      | `PUT /admin/kol/{id}`          | Update profil via `KolProfileService::updateProfile()`                                       |
| `updateStatus($id, UpdateKolStatusRequest)` | `PATCH /admin/kol/{id}/status` | Ubah status via `KolProfileService::changeStatus()`                                          |
| `export()`                                  | `GET /admin/kol/export`        | Export data KOL yang ter-filter ke CSV _(koordinasi dengan Dev 4)_                           |

#### 4. `app/Http/Controllers/Kol/ProfileController.php`

```
php artisan make:controller Kol/ProfileController --no-interaction
```

| Method                         | Route                  | Deskripsi                                              |
| ------------------------------ | ---------------------- | ------------------------------------------------------ |
| `show()`                       | `GET /kol/profil`      | Tampilkan profil sendiri: `auth()->user()->kolProfile` |
| `edit()`                       | `GET /kol/profil/edit` | Form edit profil sendiri (pre-filled)                  |
| `update(UpdateProfileRequest)` | `PUT /kol/profil`      | Update profil via `KolProfileService::updateProfile()` |

---

### D. Routes

Tambahkan di file `routes/web.php` (atau di `routes/superadmin.php` dan `routes/kol.php` jika Dev 1 sudah me-register-nya):

```php
// === Public Routes ===
Route::get('/daftar', [PublicRegistrationController::class, 'create'])->name('public.register');
Route::post('/daftar', [PublicRegistrationController::class, 'store'])->name('public.register.store');
Route::get('/daftar/konfirmasi', [PublicRegistrationController::class, 'confirmation'])->name('public.register.confirmation');

// === Admin Routes (di routes/superadmin.php) ===
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Review Pendaftaran
    Route::get('/pendaftaran', [RegistrationReviewController::class, 'index'])->name('registrations.index');
    Route::get('/pendaftaran/{registration}', [RegistrationReviewController::class, 'show'])->name('registrations.show');
    Route::post('/pendaftaran/{registration}/approve', [RegistrationReviewController::class, 'approve'])->name('registrations.approve');
    Route::post('/pendaftaran/{registration}/reject', [RegistrationReviewController::class, 'reject'])->name('registrations.reject');

    // Manajemen KOL
    Route::get('/kol/export', [KolManagementController::class, 'export'])->name('kol.export');
    Route::resource('/kol', KolManagementController::class)->except(['destroy']);
    Route::patch('/kol/{kol}/status', [KolManagementController::class, 'updateStatus'])->name('kol.update-status');
});

// === KOL Routes (di routes/kol.php) ===
Route::middleware(['auth', 'role:kol'])->prefix('kol')->name('kol.')->group(function () {
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});
```

---

### E. Blade Views yang Perlu Dibuat

| #   | File                                                         | Deskripsi                                          |
| --- | ------------------------------------------------------------ | -------------------------------------------------- |
| 1   | `resources/views/public/registration/create.blade.php`       | Form pendaftaran publik                            |
| 2   | `resources/views/public/registration/confirmation.blade.php` | Halaman konfirmasi                                 |
| 3   | `resources/views/superadmin/registrations/index.blade.php`   | Tabel daftar pendaftaran                           |
| 4   | `resources/views/superadmin/registrations/show.blade.php`    | Detail pendaftaran + review                        |
| 5   | `resources/views/superadmin/kol/index.blade.php`             | Tabel daftar KOL + compound filter                 |
| 6   | `resources/views/superadmin/kol/create.blade.php`            | Form tambah KOL manual                             |
| 7   | `resources/views/superadmin/kol/show.blade.php`              | Detail KOL (tab: Profil, Endorsement, Komisi, Log) |
| 8   | `resources/views/superadmin/kol/edit.blade.php`              | Form edit KOL                                      |
| 9   | `resources/views/kol/profile/show.blade.php`                 | Profil KOL (view sendiri)                          |
| 10  | `resources/views/kol/profile/edit.blade.php`                 | Edit profil KOL                                    |

---

## Urutan Pengerjaan (Rekomendasi)

```
Minggu 1:
├── 1. Form Requests (semua validasi)
├── 2. KolRegistrationService (store, approve, reject)
├── 3. PublicRegistrationController + routes + views pendaftaran
└── 4. RegistrationReviewController + routes + views review

Minggu 2:
├── 5. KolProfileService (filter, updateProfile, changeStatus)
├── 6. KolManagementController + routes + views daftar & detail KOL
├── 7. Kol\ProfileController + routes + views profil KOL
└── 8. Testing & bug fixing
```

---

## Titik Integrasi dengan Dev Lain

| Kebutuhan                                      | Dev yang Dituju | Solusi Sementara                                                 |
| ---------------------------------------------- | --------------- | ---------------------------------------------------------------- |
| Middleware `role:admin` dan `role:kol`         | **Dev 1**       | Jika belum ada, buat middleware sederhana sementara              |
| Kirim email konfirmasi & kredensial            | **Dev 5**       | Untuk sementara, log password ke `storage/logs/laravel.log` saja |
| Trigger notifikasi "Pendaftaran baru" ke Admin | **Dev 5**       | Tambahkan `// TODO: NotificationService::send()` di kode         |
| Helper upload file terstandarisasi             | **Dev 5**       | Buat logic upload langsung di Service, nanti refactor ke helper  |

---

## Verification Plan

### Manual Testing

1. ✅ Buka `/daftar` → isi form → submit → cek halaman konfirmasi tampil nomor registrasi
2. ✅ Cek database: `kol_registrations` terisi, `registration_files` terisi, file ada di storage
3. ✅ Login Admin → buka `/admin/pendaftaran` → klik detail → klik Approve
4. ✅ Cek database: `users` baru terbuat, `kol_profiles` terbuat, `kol_social_media` terisi dari JSON
5. ✅ Login sebagai KOL baru → buka `/kol/profil` → data tampil benar
6. ✅ Login Admin → klik Reject pada pendaftaran lain → cek data terhapus (hard delete), file terhapus, audit log tetap ada
7. ✅ Buka `/admin/kol` → test filter: pilih niche + range followers → hasil muncul < 1 detik
8. ✅ Ubah status KOL: Aktif → Blacklist → pastikan alasan wajib diisi, audit log tercatat

### Automated Tests

```bash
php artisan make:test KolRegistrationTest --phpunit --no-interaction
php artisan make:test KolProfileManagementTest --phpunit --no-interaction
php artisan make:test KolFilterTest --phpunit --no-interaction
```
