# Manual Testing DEV 1 — Web Session Authentication

Dokumen ini digunakan untuk testing manual authentication, role middleware, authorization, session security, dan audit trail.

Project menggunakan web/session authentication. Testing ini tidak menggunakan API token, Sanctum, JWT, atau header Bearer.

## Persiapan

1. Jalankan aplikasi Laravel.
2. Set variable Postman:

```text
base_url = http://localhost:8000
superadmin_email = admin@majapahit.com
superadmin_password = password
kol_email = kol@majapahit.com
kol_password = password
```

3. Aktifkan **Automatically follow redirects**.
4. Pastikan ** cookie jar** Postman aktif.
5. Gunakan database yang sudah menjalankan migration dan seeder.

## Catatan CSRF

Semua request `POST` web membutuhkan CSRF token.

Cara manual:

1. Buka `GET {{base_url}}/login`.
2. Ambil cookie `XSRF-TOKEN` atau token hidden `_token` dari response HTML.
3. Kirim `_token` sebagai form-data/x-www-form-urlencoded pada request POST.
4. Pastikan cookie `laravel_session` tetap tersimpan.

Jika aplikasi menggunakan middleware CSRF standar, request POST tanpa token dapat menghasilkan `419 Page Expired`.

## Skenario 1 — Halaman login

### Request

```http
GET {{base_url}}/login
```

### Expected

- Status `200`.
- Halaman login tampil.
- Terdapat field email dan password.
- Cookie session tersimpan.

## Skenario 2 — Login superadmin berhasil

### Request

```http
POST {{base_url}}/login
Content-Type: application/x-www-form-urlencoded
```

Body:

```text
_token={{csrf_token}}
email={{superadmin_email}}
password={{superadmin_password}}
remember=0
```

### Expected

- Status `302`.
- Redirect ke `/admin/dashboard`.
- Cookie `laravel_session` tersedia.
- Audit log memiliki action `auth.login`.

## Skenario 3 — Login KOL berhasil

### Request

```http
POST {{base_url}}/login
```

Body:

```text
_token={{csrf_token}}
email={{kol_email}}
password={{kol_password}}
remember=0
```

### Expected

- Status `302`.
- Redirect ke `/kol/dashboard`.
- Audit log memiliki action `auth.login`.

## Skenario 4 — Password salah

### Request

```http
POST {{base_url}}/login
```

Body:

```text
_token={{csrf_token}}
email={{superadmin_email}}
password=password-salah
```

### Expected

- User tetap guest.
- Kembali ke halaman login.
- Terdapat validation error email.
- Audit log memiliki action `auth.login_failed`.
- Password tidak muncul di audit log.

## Skenario 5 — Rate limit login

1. Kirim login dengan password salah sebanyak lima kali menggunakan email dan IP yang sama.
2. Kirim percobaan keenam sebelum satu menit berlalu.

### Expected

- Percobaan berikutnya ditolak.
- Muncul pesan terlalu banyak percobaan login.
- Setelah login berhasil, rate limiter dihapus.

## Skenario 6 — Akses tanpa login

### Request

Pastikan cookie `laravel_session` dihapus atau gunakan Postman session baru.

```http
GET {{base_url}}/admin/dashboard
```

### Expected

- Status `302` ke `/login`.
- Tidak ada isi dashboard yang dapat diakses.

## Skenario 7 — KOL mengakses area superadmin

1. Login sebagai KOL.
2. Kirim request:

```http
GET {{base_url}}/admin/dashboard
```

### Expected

- Status `403`.
- Dashboard superadmin tidak ditampilkan.

## Skenario 8 — Superadmin mengakses area KOL

1. Login sebagai superadmin.
2. Kirim request:

```http
GET {{base_url}}/kol/dashboard
```

### Expected

- Status `403`.
- Dashboard KOL tidak ditampilkan.

## Skenario 9 — User inactive

1. Set `users.is_active` menjadi `false` untuk salah satu user.
2. Login menggunakan user tersebut, atau gunakan session yang sudah dimiliki.
3. Akses dashboard sesuai role.

### Expected

- Login ditolak atau session langsung dihancurkan.
- Status akses protected route `403`.
- Cookie session tidak lagi dapat digunakan.
- Audit login gagal memiliki reason `inactive_account`.

## Skenario 10 — Logout

### Request

```http
POST {{base_url}}/logout
```

Body:

```text
_token={{csrf_token}}
```

### Expected

- Status `302` ke `/login`.
- Session di-invalidate.
- Request ke dashboard menggunakan cookie lama tidak lagi terautentikasi.
- Audit log memiliki action `auth.logout`.

## Skenario 11 — Password reset

1. Kirim request forgot password.
2. Karena mailer lokal menggunakan log, ambil reset URL/token dari log aplikasi.
3. Buka halaman reset password.
4. Kirim password baru dengan confirmation yang sama.

### Expected

- Password berubah.
- Redirect ke login.
- Audit log memiliki action `auth.password_reset`.
- Password dan token tidak tersimpan di audit log.
- Session lama user dicabut.

## Skenario 12 — Set password awal KOL

1. Buat token password untuk user KOL yang sesuai.
2. Buka URL set password.
3. Kirim password baru dan confirmation.

### Expected

- Akun menjadi aktif.
- User otomatis login.
- Redirect ke `/kol/dashboard`.
- Audit log memiliki action `auth.password_changed`.
- Session diregenerasi setelah login.

## Checklist audit trail

Periksa tabel `audit_logs` setelah setiap skenario:

| Aktivitas | Action yang diharapkan |
|---|---|
| Login berhasil | `auth.login` |
| Password salah | `auth.login_failed` |
| Akun inactive | `auth.login_failed` |
| Logout | `auth.logout` |
| Reset password | `auth.password_reset` |
| Set password awal | `auth.password_changed` |

Pastikan:

- `user_id` berisi actor yang benar jika tersedia.
- `entity_type` bernilai `User` untuk aktivitas user.
- IP address tercatat.
- User agent tercatat.
- Password, token, dan secret tidak tercatat.

## Hasil testing

Catat hasil di bawah ini:

| Skenario | Status | Catatan |
|---|---|---|
| Login superadmin | PASS / FAIL | |
| Login KOL | PASS / FAIL | |
| Password salah | PASS / FAIL | |
| Rate limit | PASS / FAIL | |
| Guest access | PASS / FAIL | |
| Cross-role access | PASS / FAIL | |
| Inactive user | PASS / FAIL | |
| Logout | PASS / FAIL | |
| Password reset | PASS / FAIL | |
| Audit trail | PASS / FAIL | |
