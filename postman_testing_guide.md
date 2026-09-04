# Prosedur Testing API Dev 2 — Postman

**Base URL**: `http://localhost:8000`

> [!IMPORTANT]
> Jalankan server dulu: `php artisan serve`
> 
> Pastikan semua header berikut di-set di setiap request:
> - `Accept: application/json`
> - `Content-Type: application/json` (kecuali yang upload file, gunakan `multipart/form-data`)

---

## Persiapan: Cek Koneksi

```
GET /
```

**Expected Response:**
```json
{ "message": "API is running" }
```

---

## Flow 1: Pendaftaran Publik KOL

### 1.1 — Ambil Data Niches (untuk form)

```
GET /daftar
```

**Expected**: JSON berisi list niches yang aktif.

---

### 1.2 — Submit Pendaftaran KOL

```
POST /daftar
Content-Type: application/json
```

**Body (JSON):**
```json
{
    "full_name": "Budi Influencer",
    "email": "budi.influencer@gmail.com",
    "phone": "081234567890",
    "city": "Jakarta",
    "niches": ["Fashion", "Beauty"],
    "social_media": [
        {
            "platform": "instagram",
            "username": "budi_fashion",
            "profile_url": "https://instagram.com/budi_fashion",
            "followers_count": 50000
        },
        {
            "platform": "tiktok",
            "username": "budi_tiktok",
            "profile_url": "https://tiktok.com/@budi_tiktok",
            "followers_count": 120000
        }
    ],
    "expected_rate": "500000",
    "join_reason": "Saya ingin bergabung karena saya memiliki passion di dunia fashion dan ingin berkolaborasi dengan brand-brand ternama.",
    "agreement": true
}
```

> [!NOTE]
> Portfolio di-comment out, jadi tidak perlu kirim file saat testing.

**Expected (201):**
```json
{
    "message": "Pendaftaran berhasil",
    "registration_number": "REG-20260903-0001"
}
```

💾 **Simpan `registration_number` dan catat ID registrasi dari database untuk langkah selanjutnya.**

---

### 1.3 — Halaman Konfirmasi

```
GET /daftar/konfirmasi
```

**Expected:**
```json
{ "message": "Silakan simpan nomor pendaftaran Anda." }
```

---

## Flow 2: Admin — Review Pendaftaran

### 2.1 — Lihat Daftar Pendaftaran

```
GET /admin/pendaftaran
```

**Optional Query Params:**
- `?status=pending_review` (default)
- `?status=approved`

**Expected**: JSON paginated list dari `kol_registrations` beserta `files`.

---

### 2.2 — Detail Pendaftaran

```
GET /admin/pendaftaran/{id}
```

Ganti `{id}` dengan ID registrasi yang didapat dari step 1.2 (cek database atau response list).

**Expected**: JSON detail registrasi + list tiers.

---

### 2.3a — Approve Pendaftaran

```
POST /admin/pendaftaran/{id}/approve
Content-Type: application/json
```

**Body:**
```json
{
    "notes": "Profil bagus, followers aktif",
    "score": 4,
    "tier_id": 2
}
```

> `tier_id` opsional — kalau tidak diisi, sistem auto-detect dari jumlah followers.

**Expected (200):**
```json
{ "message": "Pendaftaran Budi Influencer berhasil di-approve." }
```

💾 **Setelah approve:**
- Cek tabel `users` → user baru terbuat
- Cek tabel `kol_profiles` → profil KOL terbuat (status: aktif)
- Cek tabel `kol_social_media` → data sosmed termigrasi dari JSON

---

### 2.3b — Reject Pendaftaran (alternatif, test dengan registrasi lain)

Buat pendaftaran baru (ulangi 1.2 dengan email berbeda), lalu reject:

```
POST /admin/pendaftaran/{id}/reject
Content-Type: application/json
```

**Body:**
```json
{
    "rejection_reason": "Jumlah followers tidak mencukupi minimum requirement untuk bergabung."
}
```

**Expected (200):**
```json
{ "message": "Pendaftaran berhasil di-reject dan dihapus." }
```

💾 **Setelah reject:**
- Data registrasi di-hard delete dari DB
- File fisik di storage terhapus
- Audit log tetap ada (cek tabel `audit_logs`)

---

## Flow 3: Admin — Manajemen KOL

### 3.1 — Daftar KOL (dengan Compound Filter)

```
GET /admin/kol
```

**Optional Query Params (bisa dikombinasi):**

| Param | Contoh | Keterangan |
|-------|--------|------------|
| `search` | `?search=budi` | Cari nama/nickname/username |
| `niches[]` | `?niches[]=1&niches[]=2` | Filter by niche ID |
| `platforms[]` | `?platforms[]=instagram` | Filter by platform |
| `followers_min` | `?followers_min=10000` | Min followers |
| `followers_max` | `?followers_max=500000` | Max followers |
| `tier_id` | `?tier_id=2` | Filter by tier |
| `status` | `?status=aktif` | Filter by status |
| `city` | `?city=Jakarta` | Filter by kota |
| `per_page` | `?per_page=10` | Items per page (default: 25) |

**Contoh compound filter:**
```
GET /admin/kol?search=budi&status=aktif&followers_min=10000&per_page=10
```

**Expected**: JSON paginated list KOL profiles.

---

### 3.2 — Data untuk Form Tambah KOL

```
GET /admin/kol/create
```

**Expected**: JSON berisi tiers dan niches (untuk populate dropdown di form).

---

### 3.3 — Tambah KOL Manual

```
POST /admin/kol
Content-Type: application/json
```

**Body:**
```json
{
    "full_name": "Sari Content Creator",
    "email": "sari.cc@gmail.com",
    "nickname": "Sari",
    "bio": "Content creator fashion & lifestyle",
    "city": "Bandung",
    "province": "Jawa Barat",
    "tier_id": 1,
    "social_media": [
        {
            "platform": "instagram",
            "username": "sari_style",
            "profile_url": "https://instagram.com/sari_style",
            "followers_count": 8000,
            "engagement_rate": 5.2
        }
    ],
    "rate_cards": [
        {
            "platform": "instagram",
            "content_type": "Feed Post",
            "rate": 250000
        },
        {
            "platform": "instagram",
            "content_type": "Story",
            "rate": 100000
        }
    ],
    "bank_name": "BCA",
    "bank_account_number": "1234567890",
    "bank_account_name": "Sari Content Creator"
}
```

**Expected (201):**
```json
{ "message": "KOL berhasil ditambahkan secara manual." }
```

---

### 3.4 — Detail KOL

```
GET /admin/kol/{kol_profile_id}
```

**Expected**: JSON lengkap profil KOL (user, tier, niches, socialMedia, rateCards, endorsements, commissions).

---

### 3.5 — Data untuk Form Edit KOL

```
GET /admin/kol/{kol_profile_id}/edit
```

**Expected**: JSON KOL data + tiers + niches.

---

### 3.6 — Update Profil KOL

```
PUT /admin/kol/{kol_profile_id}
Content-Type: application/json
```

**Body:**
```json
{
    "nickname": "Budi Updated",
    "bio": "Fashion influencer based in Jakarta",
    "city": "Jakarta Selatan",
    "province": "DKI Jakarta",
    "social_media": [
        {
            "platform": "instagram",
            "username": "budi_fashion_updated",
            "profile_url": "https://instagram.com/budi_fashion_updated",
            "followers_count": 55000,
            "engagement_rate": 3.5
        }
    ],
    "rate_cards": [
        {
            "platform": "instagram",
            "content_type": "Feed Post",
            "rate": 500000
        }
    ]
}
```

**Expected (200):**
```json
{ "message": "Profil KOL berhasil diperbarui." }
```

---

### 3.7 — Ubah Status KOL (State Machine)

```
PATCH /admin/kol/{kol_profile_id}/status
Content-Type: application/json
```

**Test Case 1 — Aktif → Nonaktif (wajib alasan):**
```json
{
    "status": "nonaktif",
    "status_reason": "KOL tidak aktif selama 3 bulan berturut-turut"
}
```

**Test Case 2 — Nonaktif → Aktif (tanpa alasan boleh):**
```json
{
    "status": "aktif",
    "status_reason": null
}
```

**Test Case 3 — Aktif → Blacklist (IRREVERSIBLE, wajib alasan):**
```json
{
    "status": "blacklist",
    "status_reason": "Pelanggaran berat terhadap terms of service"
}
```

**Test Case 4 — Blacklist → Aktif (harus ERROR):**
```json
{
    "status": "aktif"
}
```
**Expected (422):** Error — blacklist bersifat irreversible.

---

### 3.8 — Export KOL (placeholder)

```
GET /admin/kol/export
```

**Expected:**
```json
{ "info": "Fitur export sedang dikembangkan oleh Dev 4." }
```

---

## Flow 4: KOL — Profil Sendiri

> [!WARNING]
> Flow ini butuh **login sebagai user KOL**. Karena belum ada auth API (login endpoint), Anda bisa:
> 1. Gunakan Laravel Tinker untuk mendapat session/cookie, atau
> 2. Sementara test tanpa auth (akan dapat error 401/redirect)
>
> **Cara cepat via Tinker:**
> ```bash
> php artisan tinker --execute "echo \App\Models\User::where('email','budi.influencer@gmail.com')->first()->id;"
> ```
> Lalu set session di Postman menggunakan cookie dari browser.

### 4.1 — Lihat Profil Sendiri

```
GET /kol/profil
```

**Expected**: JSON profil KOL yang sedang login.

---

### 4.2 — Data untuk Edit Profil

```
GET /kol/profil/edit
```

**Expected**: JSON profil KOL + tiers + niches.

---

### 4.3 — Update Profil Sendiri

```
PUT /kol/profil
Content-Type: application/json
```

**Body:** (sama format dengan 3.6)
```json
{
    "nickname": "Budi Pro",
    "bio": "Updated bio dari KOL sendiri",
    "city": "Jakarta",
    "province": "DKI Jakarta",
    "social_media": [
        {
            "platform": "instagram",
            "username": "budi_fashion",
            "profile_url": "https://instagram.com/budi_fashion",
            "followers_count": 55000,
            "engagement_rate": 4.0
        }
    ],
    "rate_cards": [
        {
            "platform": "instagram",
            "content_type": "Feed Post",
            "rate": 600000
        }
    ]
}
```

**Expected (200):**
```json
{ "message": "Profil berhasil diperbarui." }
```

---

## Checklist Verifikasi Database

Setelah menjalankan semua flow di atas, cek di database:

| Tabel | Yang Dicek |
|-------|------------|
| `kol_registrations` | Data pendaftaran masuk, status berubah setelah approve/reject |
| `users` | User baru terbuat setelah approve |
| `kol_profiles` | Profil terbuat dengan status aktif, data lengkap |
| `kol_social_media` | Data sosmed termigrasi dari JSON registrasi |
| `kol_rate_cards` | Rate card tersimpan saat tambah manual / update |
| `audit_logs` | Semua aksi tercatat (approve, reject, update, status change) |
| `registration_files` | Metadata file tersimpan (jika portfolio di-enable) |

> [!TIP]
> Gunakan `php artisan tinker` untuk quick check:
> ```php
> \App\Models\KolRegistration::count();
> \App\Models\KolProfile::with('socialMedia')->first();
> \App\Models\AuditLog::latest()->take(5)->get(['action','entity_type','entity_id']);
> ```
