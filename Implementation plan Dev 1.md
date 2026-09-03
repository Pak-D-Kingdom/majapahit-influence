# Implementation Plan — Dev 1: Auth, Role Middleware, dan Audit Trail

## Ringkasan Tugas

Dev 1 bertanggung jawab atas fondasi keamanan platform, meliputi autentikasi (login, reset password, set password pertama kali), otorisasi (role & permission middleware), serta sistem pencatatan jejak audit (Audit Trail) yang akan digunakan oleh seluruh modul.

## PRD Requirement yang Di-cover

- **7.b.1** — Autentikasi & Otorisasi
- **7.b.9** — Audit Trail

---

## Daftar File yang Harus Dibuat / Dimodifikasi

### A. Konfigurasi Auth & Roles

#### 1. Setup Spatie Permission (Jika digunakan) / Role Management
Pastikan `spatie/laravel-permission` sudah di-install dan di-publish.
- Modifikasi `User` model:
  ```php
  use Spatie\Permission\Traits\HasRoles;
  
  class User extends Authenticatable {
      use HasRoles;
      // ...
      
      public function isAdmin(): bool {
          return $this->hasRole('admin') || $this->hasRole('superadmin');
      }
      
      public function isKol(): bool {
          return $this->hasRole('kol');
      }
  }
  ```

#### 2. Middleware Custom (Opsional/Jika tidak pakai Spatie langsung)
Buat `app/Http/Middleware/RoleMiddleware.php`:
```bash
php artisan make:middleware RoleMiddleware
```
- Daftarkan di `bootstrap/app.php`:
  ```php
  $middleware->alias([
      'role' => \App\Http\Middleware\RoleMiddleware::class,
  ]);
  ```

---

### B. Form Requests (Validasi Auth)

#### 1. `app/Http/Requests/Auth/LoginRequest.php`
```bash
php artisan make:request Auth/LoginRequest
```
- **Rules**: `email` (required, email), `password` (required).

#### 2. `app/Http/Requests/Auth/SetPasswordRequest.php`
Untuk KOL yang baru di-approve dan perlu set password pertama kali.
- **Rules**: `password` (required, confirmed, min:8), `token` (required).

---

### C. Services & Helpers

#### 1. `app/Services/AuditLogService.php` (Atau di Model `AuditLog`)
Sesuai rancangan Dev 2, metode `AuditLog::log()` digunakan di mana-mana.
- Buat file `app/Models/AuditLog.php`:
  ```php
  class AuditLog extends Model {
      protected $guarded = [];
      protected $casts = [
          'old_values' => 'array',
          'new_values' => 'array',
      ];
      
      public static function log(string $action, string $entityType, $entityId, ?array $oldValues, ?array $newValues, ?User $actor = null): void
      {
          self::create([
              'user_id' => $actor ? $actor->id : auth()->id(),
              'action' => $action,
              'entity_type' => $entityType,
              'entity_id' => $entityId,
              'old_values' => $oldValues,
              'new_values' => $newValues,
              'ip_address' => request()->ip(),
              'user_agent' => request()->userAgent(),
          ]);
      }
  }
  ```

---

### D. Controllers

#### 1. `app/Http/Controllers/Auth/AuthController.php`
```bash
php artisan make:controller Auth/AuthController
```
- `showLoginForm()` → GET `/login`
- `login(LoginRequest)` → POST `/login`. Gunakan `Auth::attempt()`. Redirect sesuai role:
  - Admin → `/admin/dashboard` (A1)
  - KOL → `/kol/dashboard` (K1)
- `logout()` → POST `/logout`

#### 2. `app/Http/Controllers/Auth/SetPasswordController.php`
- `showSetPasswordForm($token)` → GET `/kol/set-password/{token}`
- `updatePassword(SetPasswordRequest)` → POST `/kol/set-password`

---

### E. Routes

Tambahkan di `routes/web.php`:
```php
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/kol/set-password/{token}', [SetPasswordController::class, 'showSetPasswordForm'])->name('password.set');
Route::post('/kol/set-password', [SetPasswordController::class, 'updatePassword'])->name('password.set.post');
```

---

### F. Blade Views
1. `resources/views/auth/login.blade.php`
2. `resources/views/auth/set-password.blade.php`

---

## Titik Integrasi dengan Dev Lain
- **Dev 2**: Dev 2 akan memanggil `$user->assignRole('kol')` saat approve KOL. Pastikan relasi dan trait Spatie/Role terpasang di model User.
- **Dev 2, 3, 4**: Semua dev akan memanggil `AuditLog::log()`. Pastikan struktur parameter konsisten dengan yang dirancang Dev 2.
- **Semua Dev**: Middleware `role:admin` dan `role:kol` akan menempel di rute-rute `superadmin.php` dan `kol.php`. Pastikan middleware ini terdaftar dan berfungsi dengan baik.
