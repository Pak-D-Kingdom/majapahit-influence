# Roadmap & Panduan Prompt Eksekusi — DEV 1
**Modul**: Autentikasi, Role Middleware, dan Audit Trail  
**Platform**: Manajemen Agensi KOL "Majapahit"  

Dokumen ini membagi pengerjaan Dev 1 menjadi **6 Tahap Berurutan**. Setiap tahap dilengkapi dengan detail file yang disentuh dan **Prompt Siap Pakai** yang bisa Anda salin dan kirimkan kepada saya untuk dieksekusi satu per satu.

---

## Ringkasan Tahapan

| Tahap | Fokus Pekerjaan | Output Utama |
|---|---|---|
| **Tahap 1** | Role Middleware & Route Grouping | `RoleMiddleware.php`, registrasi di `bootstrap/app.php`, routing `/admin` & `/kol` |
| **Tahap 2** | Form Requests (Validasi) | `LoginRequest`, `ForgotPasswordRequest`, `ResetPasswordRequest`, `SetPasswordRequest` |
| **Tahap 3** | Auth Controllers & Business Logic | `AuthController`, `SetPasswordController`, `ForgotPasswordController` + pencatatan `AuditLog` |
| **Tahap 4** | Web Routes & Placeholder Dashboard | Rute Auth di `routes/web.php`, endpoint placeholder dashboard di `superadmin.php` & `kol.php` |
| **Tahap 5** | Blade Views (UI/UX Majapahit Theme) | `layouts/auth.blade.php`, `login.blade.php`, `forgot-password.blade.php`, `reset-password.blade.php`, `set-password.blade.php` |
| **Tahap 6** | Testing & Verifikasi End-to-End | Verifikasi login admin & KOL, pengujian proteksi middleware (403), pengecekan tabel `audit_logs` |

---

## Detail Tahap & Prompt Siap Pakai

### 🔹 TAHAP 1: Role Middleware & Route Grouping
**Tujuan**: Membuat guard otorisasi role (`admin` & `kol`) dan menyambungkan route file modular di Laravel 11.
- **File**:
  - `app/Http/Middleware/RoleMiddleware.php`
  - `bootstrap/app.php`

> **Prompt Tahap 1 (Copy & Paste):**
> ```text
> Jalankan Tahap 1: Buat RoleMiddleware di app/Http/Middleware/RoleMiddleware.php yang mendukung parameter multiple role (misal role:admin atau role:kol), memeriksa status is_active user (jika nonaktif langsung tolak/logout), dan mengembalikan response 403 Forbidden jika tidak berhak. Daftarkan alias 'role' di bootstrap/app.php dan hubungkan route group routes/superadmin.php (prefix /admin, name admin., middleware auth & role:admin) dan routes/kol.php (prefix /kol, name kol., middleware auth & role:kol).
> ```

---

### 🔹 TAHAP 2: Form Requests (Validasi Auth)
**Tujuan**: Memastikan semua input auth tervalidasi secara ketat dan aman (dengan rate limiting login).
- **File**:
  - `app/Http/Requests/Auth/LoginRequest.php`
  - `app/Http/Requests/Auth/ForgotPasswordRequest.php`
  - `app/Http/Requests/Auth/ResetPasswordRequest.php`
  - `app/Http/Requests/Auth/SetPasswordRequest.php`

> **Prompt Tahap 2 (Copy & Paste):**
> ```text
> Jalankan Tahap 2: Buat Form Requests untuk modul autentikasi:
> 1. app/Http/Requests/Auth/LoginRequest.php (validasi email, password, remember me boolean, serta logic rate limiting/throttle max 5 attempts per menit).
> 2. app/Http/Requests/Auth/ForgotPasswordRequest.php (validasi email terdaftar).
> 3. app/Http/Requests/Auth/ResetPasswordRequest.php (validasi token, email, password min 8 & confirmed).
> 4. app/Http/Requests/Auth/SetPasswordRequest.php (validasi token, email, password min 8 & confirmed untuk onboarding KOL).
> ```

---

### 🔹 TAHAP 3: Auth Controllers & Business Logic
**Tujuan**: Menangani alur login, logout, redirect dinamis sesuai role, pencatatan login ke `AuditLog`, serta alur reset dan set password menggunakan token bawaan Laravel.
- **File**:
  - `app/Http/Controllers/Auth/AuthController.php`
  - `app/Http/Controllers/Auth/ForgotPasswordController.php`
  - `app/Http/Controllers/Auth/SetPasswordController.php`

> **Prompt Tahap 3 (Copy & Paste):**
> ```text
> Jalankan Tahap 3: Buat Controller Autentikasi:
> 1. app/Http/Controllers/Auth/AuthController.php:
>    - showLoginForm(): tampilkan view login.
>    - login(): validasi request, verifikasi is_active, update last_login_at, catat AuditLog::log('login', 'User', $user->id, ...), lalu redirect dinamis: jika admin ke /admin/dashboard, jika kol ke /kol/dashboard.
>    - logout(): catat AuditLog::log('logout', ...), invalidate session, regenerate token, redirect ke /login.
> 2. app/Http/Controllers/Auth/ForgotPasswordController.php:
>    - showLinkRequestForm() & sendResetLinkEmail() menggunakan Password::broker().
> 3. app/Http/Controllers/Auth/SetPasswordController.php:
>    - showSetPasswordForm($token) untuk KOL pertama kali onboarding.
>    - updatePassword(): validasi token, update password user, update status profil jika perlu, catat AuditLog, dan login otomatis menuju /kol/dashboard.
> ```

---

### 🔹 TAHAP 4: Web Routes & Placeholder Dashboard
**Tujuan**: Mendaftarkan endpoint auth di `routes/web.php` dan menyediakan dashboard sementara di `superadmin.php` dan `kol.php` agar redirect login dapat diverifikasi langsung tanpa error 404.
- **File**:
  - `routes/web.php`
  - `routes/superadmin.php`
  - `routes/kol.php`

> **Prompt Tahap 4 (Copy & Paste):**
> ```text
> Jalankan Tahap 4: Daftarkan seluruh rute auth di routes/web.php (login, logout, forgot-password, reset-password, kol/set-password). Selain itu, buat placeholder route dashboard sederhana di routes/superadmin.php (GET /admin/dashboard, name admin.dashboard) dan routes/kol.php (GET /kol/dashboard, name kol.dashboard) agar alur redirect login bisa langsung diuji tanpa 404.
> ```

---

### 🔹 TAHAP 5: Blade Views (UI/UX Majapahit Aesthetic)
**Tujuan**: Membuat tampilan form otentikasi yang modern, elegan, responsif, dengan sentuhan warna Majapahit (Terracotta, Warm Gold, Deep Slate) tanpa bergantung pada framework CSS eksternal yang rumit.
- **File**:
  - `resources/views/layouts/auth.blade.php` (Shared Master Layout)
  - `resources/views/auth/login.blade.php`
  - `resources/views/auth/forgot-password.blade.php`
  - `resources/views/auth/reset-password.blade.php`
  - `resources/views/auth/set-password.blade.php`

> **Prompt Tahap 5 (Copy & Paste):**
> ```text
> Jalankan Tahap 5: Buat seluruh tampilan Blade untuk modul Auth dengan estetika visual khas Majapahit (modern terracotta, warm gold accents, clean dark/light typography, responsive, dan interaktif):
> 1. resources/views/layouts/auth.blade.php: Base layout elegan.
> 2. resources/views/auth/login.blade.php: Form login dengan toggle password, remember me, pesan error validasi, dan alert feedback.
> 3. resources/views/auth/forgot-password.blade.php: Form kirim link reset password.
> 4. resources/views/auth/reset-password.blade.php: Form ubah password baru.
> 5. resources/views/auth/set-password.blade.php: Form aktivasi & set password pertama kali untuk KOL yang baru di-approve.
> ```

---

### 🔹 TAHAP 6: Testing & Verifikasi End-to-End
**Tujuan**: Menguji seluruh alur autentikasi dan otorisasi menggunakan database lokal, memastikan keamanan role, dan memvalidasi log audit.
- **Pengujian**:
  - Login Admin (`admin@majapahit.com` / `password`) → harus masuk ke `/admin/dashboard`.
  - Login KOL (`kol@majapahit.com` / `password`) → harus masuk ke `/kol/dashboard`.
  - Hak Akses: KOL mencoba akses `/admin/*` → harus 403 Forbidden.
  - Pengecekan tabel `audit_logs` → harus ada entri aksi `login` & `logout`.

> **Prompt Tahap 6 (Copy & Paste):**
> ```text
> Jalankan Tahap 6: Lakukan verifikasi dan testing fungsional modul Dev 1: jalankan artisan optimize:clear, uji alur login akun admin dan KOL, verifikasi pembatasan akses RoleMiddleware (403 Forbidden jika cross-role), dan pastikan pencatatan ke tabel audit_logs berjalan dengan benar. Tuliskan ringkasan hasil pengujian ke walkthrough.md.
> ```

---
*Gunakan prompt di atas secara bertahap mulai dari **Tahap 1**. Anda cukup mengetikkan prompt Tahap 1 untuk memulai.*
