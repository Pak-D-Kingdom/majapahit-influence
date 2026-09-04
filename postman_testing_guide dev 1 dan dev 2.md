# Majapahit Influence — Postman API Testing Guide

Panduan lengkap untuk menguji seluruh endpoint API menggunakan **Postman**.  
Dokumen ini mencakup fitur **Dev 1** dan **integrasi Dev 1 + Dev 2**.

---

## Persiapan Awal

### 1. Reset Database
```bash
php artisan migrate:fresh --seed
```

### 2. Jalankan Server
```bash
php artisan serve
```

### 3. Setup Postman Environment Variables

| Variable      | Value                      |
|---------------|----------------------------|
| `base_url`    | `http://localhost:8000`    |

### 4. Header Wajib untuk Semua Request

| Header        | Value              |
|---------------|--------------------|
| `Accept`      | `application/json` |
| `Content-Type`| `application/json` |

### 5. Akun Seeder

| Role       | Email                 | Password   |
|------------|-----------------------|------------|
| Superadmin | `admin@majapahit.com` | `password` |
| KOL        | `kol@majapahit.com`   | `password` |

---

# BAGIAN A — Dev 1: Pendaftaran & Review

## A1. Pendaftaran KOL Publik

Calon KOL yang belum memiliki akun dapat mendaftar melalui form publik.

### A1.1 Get Data Niche (untuk isi form)

| Item   | Value                      |
|--------|----------------------------|
| Method | `GET`                      |
| URL    | `{{base_url}}/daftar`      |

**Expected Response** `200 OK`:
```json
{
  "data": {
    "niches": [
      { "id": 1, "name": "Lifestyle", "is_active": true },
      ...
    ]
  }
}
```

---

### A1.2 Submit Pendaftaran

| Item   | Value                          |
|--------|--------------------------------|
| Method | `POST`                         |
| URL    | `{{base_url}}/daftar`          |

**Body** (raw JSON):
```json
{
  "full_name": "Budi Santoso",
  "email": "budi.influencer@gmail.com",
  "phone": "081234567890",
  "city": "Jakarta",
  "niches": ["Lifestyle"],
  "social_media": [
    {
      "platform": "instagram",
      "username": "budisantoso",
      "profile_url": "https://instagram.com/budisantoso",
      "followers_count": 50000
    }
  ],
  "expected_rate": "1500000",
  "join_reason": "Saya ingin bergabung karena tertarik dengan campaign yang ada di platform ini.",
  "agreement": true
}
```

**Expected Response** `201 Created`:
```json
{
  "message": "Pendaftaran berhasil",
  "registration_number": "REG-XXXXXX"
}
```

> [!WARNING]
> **Validasi penting:**
> - `phone` harus format Indonesia (`08xx`, `628xx`, `+628xx`)
> - `niches.*` harus sesuai nama yang ada di tabel `niches`
> - `join_reason` minimal 20 karakter
> - `agreement` harus bernilai `true`

---

### A1.3 Halaman Konfirmasi

| Item   | Value                                  |
|--------|----------------------------------------|
| Method | `GET`                                  |
| URL    | `{{base_url}}/daftar/konfirmasi`       |

**Expected Response** `200 OK`:
```json
{
  "message": "Silakan simpan nomor pendaftaran Anda."
}
```

---

## A2. Login Admin

Untuk bisa melakukan review, Admin harus login terlebih dahulu.

| Item   | Value                      |
|--------|----------------------------|
| Method | `POST`                     |
| URL    | `{{base_url}}/login`       |

**Body** (raw JSON):
```json
{
  "email": "admin@majapahit.com",
  "password": "password"
}
```

**Expected Response** `200 OK`:
```json
{
  "message": "Login berhasil",
  "user": {
    "id": 1,
    "name": "Superadmin Majapahit",
    "email": "admin@majapahit.com",
    ...
  },
  "role": "admin"
}
```

> [!IMPORTANT]
> Setelah login berhasil, Postman otomatis menyimpan **session cookie** (`laravel_session`). Pastikan fitur **Cookies** di Postman aktif agar request berikutnya terautentikasi.

---

## A3. Admin — Review Pendaftaran

### A3.1 List Pendaftaran

| Item   | Value                                        |
|--------|----------------------------------------------|
| Method | `GET`                                        |
| URL    | `{{base_url}}/admin/admin/pendaftaran`       |

**Query Parameters (opsional):**

| Param    | Value                                          |
|----------|-------------------------------------------------|
| `status` | `pending_review` (default), `approved`, `rejected` |

**Expected Response** `200 OK`:
```json
{
  "registrations": {
    "data": [
      {
        "id": 1,
        "full_name": "Budi Santoso",
        "email": "budi.influencer@gmail.com",
        "status": "pending_review",
        ...
      }
    ],
    ...
  },
  "status": "pending_review"
}
```

---

### A3.2 Detail Pendaftaran

| Item   | Value                                                 |
|--------|-------------------------------------------------------|
| Method | `GET`                                                 |
| URL    | `{{base_url}}/admin/admin/pendaftaran/{registration_id}` |

> Ganti `{registration_id}` dengan ID dari response list di atas (contoh: `1`).

---

### A3.3 Approve Pendaftaran

| Item   | Value                                                          |
|--------|----------------------------------------------------------------|
| Method | `POST`                                                         |
| URL    | `{{base_url}}/admin/admin/pendaftaran/{registration_id}/approve` |

**Body** (raw JSON — semua field opsional):
```json
{
  "notes": "Profil bagus, follower cukup untuk micro tier.",
  "score": 4,
  "tier_id": 1
}
```

**Expected Response** `200 OK`:
```json
{
  "message": "Pendaftaran Budi Santoso berhasil di-approve."
}
```

---

### A3.4 Reject Pendaftaran

| Item   | Value                                                         |
|--------|---------------------------------------------------------------|
| Method | `POST`                                                        |
| URL    | `{{base_url}}/admin/admin/pendaftaran/{registration_id}/reject` |

**Body** (raw JSON):
```json
{
  "rejection_reason": "Jumlah follower belum memenuhi standar minimum agensi kami."
}
```

> [!NOTE]
> `rejection_reason` wajib diisi, minimal 10 karakter.

**Expected Response** `200 OK`:
```json
{
  "message": "Pendaftaran berhasil di-reject dan dihapus."
}
```

---

## A4. Logout

| Item   | Value                      |
|--------|----------------------------|
| Method | `POST`                     |
| URL    | `{{base_url}}/logout`      |

**Expected Response** `200 OK`:
```json
{
  "message": "Anda telah berhasil logout."
}
```

---

# BAGIAN B — Integrasi Dev 1 + Dev 2

Fitur Dev 2 mencakup **manajemen KOL oleh Admin** dan **KOL mengelola profil sendiri**. Seluruh endpoint di bawah ini membutuhkan login terlebih dahulu.

---

## B1. Admin — Manajemen KOL

> **Prasyarat:** Login sebagai Admin terlebih dahulu (lihat A2).

### B1.1 List Semua KOL (dengan Filter)

| Item   | Value                                    |
|--------|------------------------------------------|
| Method | `GET`                                    |
| URL    | `{{base_url}}/admin/admin/kol`           |

**Query Parameters (semua opsional):**

| Param           | Contoh Value  | Keterangan                  |
|-----------------|---------------|-----------------------------|
| `search`        | `Dimas`       | Cari berdasarkan nama/email |
| `city`          | `Jakarta`     | Filter kota                 |
| `tier_id`       | `1`           | Filter tier                 |
| `status`        | `aktif`       | `aktif`, `nonaktif`, `blacklist` |
| `followers_min` | `1000`        | Minimum followers           |
| `followers_max` | `100000`      | Maksimum followers          |
| `per_page`      | `10`          | Jumlah per halaman          |

---

### B1.2 Get Form Create (Data Tier & Niche)

| Item   | Value                                         |
|--------|-----------------------------------------------|
| Method | `GET`                                         |
| URL    | `{{base_url}}/admin/admin/kol/create`         |

**Expected Response** `200 OK`:
```json
{
  "tiers": [ { "id": 1, "name": "Micro", ... } ],
  "niches": [ { "id": 1, "name": "Lifestyle", ... } ]
}
```

---

### B1.3 Tambah KOL Manual (oleh Admin)

| Item   | Value                                    |
|--------|------------------------------------------|
| Method | `POST`                                   |
| URL    | `{{base_url}}/admin/admin/kol`           |

**Body** (raw JSON):
```json
{
  "full_name": "Rina Cantika",
  "email": "rina.cantika@gmail.com",
  "nickname": "Rina",
  "bio": "Beauty & skincare content creator dari Bandung.",
  "city": "Bandung",
  "province": "Jawa Barat",
  "tier_id": 1,
  "niches": [1, 2],
  "social_media": [
    {
      "platform": "instagram",
      "username": "rinacantika",
      "profile_url": "https://instagram.com/rinacantika",
      "followers_count": 80000,
      "engagement_rate": 3.5
    }
  ],
  "rate_cards": [
    {
      "platform": "instagram",
      "content_type": "reels",
      "rate": 2500000
    }
  ],
  "bank_name": "BCA",
  "bank_account_number": "9876543210",
  "bank_account_name": "Rina Cantika"
}
```

**Expected Response** `201 Created`:
```json
{
  "message": "KOL berhasil ditambahkan secara manual."
}
```

---

### B1.4 Detail KOL

| Item   | Value                                           |
|--------|-------------------------------------------------|
| Method | `GET`                                           |
| URL    | `{{base_url}}/admin/admin/kol/{kol_profile_id}` |

> Gunakan ID `kol_profile` dari response list KOL (contoh: `1`).

---

### B1.5 Edit KOL (Get Form Data)

| Item   | Value                                                |
|--------|------------------------------------------------------|
| Method | `GET`                                                |
| URL    | `{{base_url}}/admin/admin/kol/{kol_profile_id}/edit` |

---

### B1.6 Update Profil KOL (oleh Admin)

| Item   | Value                                           |
|--------|-------------------------------------------------|
| Method | `PUT`                                           |
| URL    | `{{base_url}}/admin/admin/kol/{kol_profile_id}` |

**Body** (raw JSON):
```json
{
  "nickname": "Dimas Updated",
  "bio": "Fashion & lifestyle creator — updated bio.",
  "city": "Surabaya",
  "province": "Jawa Timur",
  "social_media": [
    {
      "platform": "instagram",
      "username": "dimas_updated",
      "profile_url": "https://instagram.com/dimas_updated",
      "followers_count": 95000,
      "engagement_rate": 5.0
    }
  ],
  "rate_cards": [
    {
      "platform": "instagram",
      "content_type": "reels",
      "rate": 4000000
    }
  ]
}
```

**Expected Response** `200 OK`:
```json
{
  "message": "Profil KOL berhasil diperbarui."
}
```

---

### B1.7 Update Status KOL

| Item   | Value                                                   |
|--------|---------------------------------------------------------|
| Method | `PATCH`                                                 |
| URL    | `{{base_url}}/admin/admin/kol/{kol_profile_id}/status`  |

**Body — Nonaktifkan KOL:**
```json
{
  "status": "nonaktif",
  "status_reason": "KOL sudah tidak aktif membuat konten selama 6 bulan."
}
```

**Body — Aktifkan kembali:**
```json
{
  "status": "aktif"
}
```

**Body — Blacklist:**
```json
{
  "status": "blacklist",
  "status_reason": "Melanggar perjanjian kerja sama."
}
```

> [!NOTE]
> `status_reason` wajib diisi jika status = `nonaktif` atau `blacklist`.

---

## B2. KOL — Self-Service Profil

> **Prasyarat:** Login sebagai KOL terlebih dahulu.

### B2.0 Login KOL

| Item   | Value                      |
|--------|----------------------------|
| Method | `POST`                     |
| URL    | `{{base_url}}/login`       |

**Body** (raw JSON):
```json
{
  "email": "kol@majapahit.com",
  "password": "password"
}
```

---

### B2.1 Lihat Profil Sendiri

| Item   | Value                                 |
|--------|---------------------------------------|
| Method | `GET`                                 |
| URL    | `{{base_url}}/kol/kol/profil`         |

---

### B2.2 Get Form Edit Profil

| Item   | Value                                      |
|--------|--------------------------------------------|
| Method | `GET`                                      |
| URL    | `{{base_url}}/kol/kol/profil/edit`         |

---

### B2.3 Update Profil Sendiri

| Item   | Value                                 |
|--------|---------------------------------------|
| Method | `PUT`                                 |
| URL    | `{{base_url}}/kol/kol/profil`         |

**Body** (raw JSON):
```json
{
  "nickname": "Dimas Pro",
  "bio": "Updated bio oleh KOL sendiri.",
  "city": "Jakarta Pusat",
  "province": "DKI Jakarta",
  "bank_name": "Mandiri",
  "bank_account_number": "1112223334",
  "bank_account_name": "Dimas Pratama",
  "social_media": [
    {
      "platform": "instagram",
      "username": "dimas_pro",
      "profile_url": "https://instagram.com/dimas_pro",
      "followers_count": 100000,
      "engagement_rate": 5.5
    }
  ],
  "rate_cards": [
    {
      "platform": "instagram",
      "content_type": "reels",
      "rate": 5000000
    },
    {
      "platform": "tiktok",
      "content_type": "video",
      "rate": 4500000
    }
  ]
}
```

**Expected Response** `200 OK`:
```json
{
  "message": "Profil berhasil diperbarui."
}
```

---

# BAGIAN C — Skenario Testing End-to-End

Ikuti urutan ini untuk menguji alur lengkap dari pendaftaran sampai KOL mengelola profilnya sendiri.

| Step | Action                           | Endpoint                                          | Login Sebagai |
|------|----------------------------------|----------------------------------------------------|---------------|
| 1    | Ambil data niche                 | `GET /daftar`                                      | —             |
| 2    | Submit pendaftaran KOL           | `POST /daftar`                                     | —             |
| 3    | Login Admin                      | `POST /login`                                      | —             |
| 4    | Lihat daftar pendaftaran         | `GET /admin/admin/pendaftaran`                     | Admin         |
| 5    | Lihat detail pendaftaran         | `GET /admin/admin/pendaftaran/{id}`                | Admin         |
| 6    | Approve pendaftaran              | `POST /admin/admin/pendaftaran/{id}/approve`       | Admin         |
| 7    | Lihat list KOL (cek data baru)   | `GET /admin/admin/kol`                             | Admin         |
| 8    | Lihat detail KOL                 | `GET /admin/admin/kol/{id}`                        | Admin         |
| 9    | Admin update profil KOL          | `PUT /admin/admin/kol/{id}`                        | Admin         |
| 10   | Admin ubah status KOL            | `PATCH /admin/admin/kol/{id}/status`               | Admin         |
| 11   | Logout Admin                     | `POST /logout`                                     | Admin         |
| 12   | Login sebagai KOL                | `POST /login`                                      | —             |
| 13   | KOL lihat profil sendiri         | `GET /kol/kol/profil`                              | KOL           |
| 14   | KOL update profil                | `PUT /kol/kol/profil`                              | KOL           |
| 15   | Logout KOL                       | `POST /logout`                                     | KOL           |

> [!TIP]
> Simpan testing flow ini sebagai **Postman Collection** dan gunakan fitur **Collection Runner** untuk menjalankan seluruh skenario secara otomatis dan berurutan.
