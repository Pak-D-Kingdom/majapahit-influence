# Implementation Plan — Dev 4: Komisi, Pencairan & Export Laporan

## Ringkasan Tugas

Dev 4 bertanggung jawab atas pencatatan komisi KOL yang dihasilkan dari Endorsement yang telah diselesaikan. Termasuk juga sistem pengajuan pencairan (withdrawal) oleh KOL, proses approval pencairan oleh Admin, serta fitur export laporan (CSV/Excel).

## PRD Requirement yang Di-cover

- **7.b.4** — Skema Komisi
- **7.b.5** — Proses Approval Pencairan Komisi
- **7.a.6** — Superadmin: Manajemen Komisi
- **7.a.11** — KOL: Riwayat & Status Komisi
- **7.b.12** — Export Laporan Periodik

---

## Daftar File yang Harus Dibuat / Dimodifikasi

### A. Form Requests (Validasi)

#### 1. `app/Http/Requests/Admin/ApproveDisbursementRequest.php`
- **Rules**: `status` (required, in:approved,rejected), `notes` (nullable, string).

#### 2. `app/Http/Requests/Admin/ProcessDisbursementRequest.php`
Untuk menandai bahwa dana sudah ditransfer.
- **Rules**: `transfer_date` (required, date), `transfer_proof` (required, image/pdf, max:5120), `notes` (nullable, string).

---

### B. Service Classes

#### 1. `app/Services/CommissionService.php`
- `calculateAndCreate(Endorsement $endorsement)`:
  - Dipanggil oleh Dev 3 saat endorsement selesai.
  - Ambil `fee` dari endorsement.
  - Ambil persentase komisi dari profil KOL (atau fallback ke tier default).
  - Hitung `komisi_kol = fee * (persentase / 100)`.
  - Simpan record `Commission` dengan status `pending`.
- `requestDisbursement(array $commissionIds, User $requester)`:
  - Ubah status komisi yang dipilih menjadi `pending_review`.
  - Buat notifikasi untuk Admin.
  - Panggil `AuditLog::log()`.
- `approveDisbursement(array $commissionIds, string $status, ?string $notes, User $admin)`:
  - Jika approved, ubah status ke `approved`.
  - Jika rejected, kembalikan ke `pending` dan simpan catatan.
- `markAsDisbursed(Commission $commission, array $data, array $file, User $admin)`:
  - Upload bukti transfer.
  - Ubah status ke `dicairkan`.
  - Trigger notifikasi ke KOL (Dev 5).

#### 2. `app/Services/ExportService.php` (Gunakan Maatwebsite/Laravel-Excel)
- Buat class export: `php artisan make:export KolExport`, `CommissionExport`.
- `exportCommissions($filters)`: Query dan format data komisi ke dalam Excel.
- `exportKolProfiles($filters)`: Query data KOL berdasarkan filter.

---

### C. Controllers

#### 1. `app/Http/Controllers/Admin/CommissionController.php`
- `index()` → Tampilkan semua data komisi dengan compound filter.
- `approve(Request $request)` → Batch approve.
- `process(Commission $commission, ProcessDisbursementRequest $request)` → Proses upload bukti.
- `export(Request $request)` → Download file Excel komisi.

#### 2. `app/Http/Controllers/Kol/CommissionController.php`
- `index()` → Tampilkan histori komisi milik `auth()->user()->kolProfile`.
- `requestDisbursement(Request $request)` → Mengirim pengajuan pencairan (array of commission IDs).

#### 3. `app/Http/Controllers/Admin/ReportController.php`
- Mengatur export custom (KOL, Endorsement, dsb).

---

### D. Routes

Tambahkan di `routes/superadmin.php`:
```php
Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');
Route::post('commissions/approve', [CommissionController::class, 'approve'])->name('commissions.approve');
Route::post('commissions/{commission}/process', [CommissionController::class, 'process'])->name('commissions.process');
Route::get('commissions/export', [CommissionController::class, 'export'])->name('commissions.export');
```

Tambahkan di `routes/kol.php`:
```php
Route::get('commissions', [\App\Http\Controllers\Kol\CommissionController::class, 'index'])->name('commissions.index');
Route::post('commissions/request', [\App\Http\Controllers\Kol\CommissionController::class, 'requestDisbursement'])->name('commissions.request');
```

---

### E. Blade Views
- `resources/views/superadmin/commissions/index.blade.php`, `show.blade.php`
- `resources/views/kol/commissions/index.blade.php`

---

## Titik Integrasi dengan Dev Lain
- **Dev 3**: Beri tahu Dev 3 bahwa mereka harus memanggil `CommissionService::calculateAndCreate($endorsement)` saat status endorsement diubah menjadi "Selesai".
- **Dev 2**: Jika ada fitur export KOL dari halaman admin KOL, integrasikan endpoint `ExportService` ke route yang disiapkan Dev 2.
- **Dev 5**: Panggil notifikasi saat status komisi berubah.
