# Authentication, Authorization, dan Audit Integration

Dokumen ini adalah kontrak integrasi untuk DEV 2–5 dan frontend.

## Role resmi

Project hanya memiliki dua role:

- `superadmin`: akses administratif penuh.
- `kol`: akses terbatas pada profil dan data yang dimilikinya.

Jangan gunakan role `admin`.

## Middleware route

Gunakan middleware `auth` untuk route yang membutuhkan login dan `role` untuk membatasi area berdasarkan role:

```php
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    // Area superadmin
});

Route::middleware(['auth', 'role:kol'])->group(function () {
    // Area KOL
});
```

Middleware role menerima lebih dari satu role bila diperlukan:

```php
->middleware('role:superadmin,kol')
```

## Policy authorization

Middleware hanya membatasi area besar. Endpoint yang menerima ID/entity tetap wajib menggunakan policy:

```php
$this->authorize('view', $kolProfile);
```

Aturan umum:

- `superadmin` dapat mengakses seluruh entity.
- `kol` hanya dapat mengakses `KolProfile` dengan `user_id` miliknya.
- `kol` hanya dapat melihat campaign yang memiliki endorsement untuk profilnya.
- `kol` hanya dapat melihat atau mengubah endorsement dan commission miliknya.
- Hanya `superadmin` yang boleh membuat/menghapus campaign dan melakukan approval administratif.

Jangan hanya memeriksa ID dari request. Selalu gunakan policy atau query yang memfilter ownership.

## AuditLogger

Gunakan service terpusat berikut:

```php
use App\Services\AuditLogger;

app(AuditLogger::class)->record(
    action: 'kol.approved',
    subject: $kolProfile,
    oldValues: ['status' => 'pending'],
    newValues: ['status' => 'aktif'],
    actor: auth()->user(),
);
```

Parameter:

- `action`: nama action dalam format `module.event`.
- `subject`: model/entity yang terkena dampak.
- `oldValues`: nilai sebelum perubahan.
- `newValues`: nilai sesudah perubahan.
- `actor`: user yang melakukan aksi; biasanya `auth()->user()`.

Password, token, secret, API key, nomor rekening, NPWP, dan data sensitif lain tidak boleh dicatat. Service akan melakukan redaction otomatis, tetapi caller tetap harus menghindari pengiriman data tersebut.

## Action audit standar

Gunakan nama berikut jika sesuai:

```text
auth.login
auth.login_failed
auth.logout
auth.password_reset
auth.password_changed
user.activated
user.deactivated
user.role_assigned
user.role_removed
kol.created
kol.updated
kol.approved
kol.rejected
campaign.created
campaign.updated
campaign.deleted
campaign.kol_assigned
endorsement.status_changed
endorsement.proof_uploaded
commission.calculated
commission.approved
payout.approved
report.exported
notification.sent
```

## Format response authorization

Untuk request browser biasa:

- guest diarahkan ke route `login` dengan status `302`.
- user tidak memiliki role atau policy ditolak mendapat `403`.

Untuk request API/JSON:

```json
{
  "message": "Unauthenticated."
}
```

Status `401` berarti belum login. Status `403` berarti sudah login tetapi tidak memiliki akses.

## Kontrak integrasi frontend

Frontend harus menangani:

- redirect atau response setelah login berdasarkan role.
- `401` dengan mengarahkan user ke login.
- `403` dengan menampilkan pesan akses ditolak.
- akun inactive dengan mengakhiri state login lokal.
- validation error sebagai error field.

Role user dapat diperiksa melalui relasi `roles`; jangan menentukan akses hanya berdasarkan URL atau tampilan menu. Backend tetap menjadi sumber kebenaran authorization.

## Checklist endpoint DEV lain

Sebelum endpoint dianggap selesai, pastikan:

- route memiliki middleware `auth`.
- route area memiliki middleware role yang benar.
- controller memanggil policy untuk entity target.
- perubahan penting dicatat melalui `AuditLogger`.
- old/new values tidak berisi data rahasia.
- tersedia test untuk akses `superadmin`, akses KOL miliknya, dan akses KOL terhadap data user lain.
