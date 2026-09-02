# PRD — Platform Manajemen Agensi KOL "Majapahit"

**Versi**: 1.1  
**Tanggal**: 2 September 2026  
**Penulis**: Product Management  
**Status**: Draft — Updated berdasarkan feedback stakeholder  
**Tech Stack**: Laravel + Blade (SSR) · MySQL  
**Tim Target**: 5+ developer · Timeline 1-3 bulan

---

## Daftar Isi

1. [Executive Summary](#1-executive-summary)
2. [Problem Statement](#2-problem-statement)
3. [Goals & Success Metrics](#3-goals--success-metrics)
4. [User Personas](#4-user-personas)
5. [Scope & Out of Scope](#5-scope--out-of-scope)
6. [User Roles & Permission Matrix](#6-user-roles--permission-matrix)
7. [Functional Requirements](#7-functional-requirements)
8. [Key User Flows](#8-key-user-flows)
9. [Screens/Page Inventory (Frontend)](#9-screenspage-inventory-frontend)
10. [Data Entities & Business Rules (Backend)](#10-data-entities--business-rules-backend)
11. [Non-Functional Requirements](#11-non-functional-requirements)
12. [Assumptions & Open Questions](#12-assumptions--open-questions)
13. [Future Considerations](#13-future-considerations)

---

## 1. Executive Summary

**Majapahit** adalah platform manajemen agensi KOL (Key Opinion Leader) dan influencer berbasis web yang dirancang untuk mendigitalisasi seluruh proses operasional agensi — mulai dari akuisisi KOL baru, pengelolaan data profil & rate card, manajemen campaign/endorsement dengan brand, hingga perhitungan dan pelacakan komisi.

Platform ini melayani **dua role utama**:

- **Superadmin** (tim internal agensi) — mengelola seluruh operasional: pendataan KOL, review & approval pendaftaran, assignment endorsement, pencatatan komisi, dan monitoring progress.
- **KOL/Influencer** — melihat transparansi penuh atas progress kerja sama, riwayat & status komisi, jadwal endorsement mendatang, serta upload bukti konten.

Platform dibangun dengan arsitektur **Laravel + Blade (server-side rendered)** dan database **MySQL**, dirancang untuk menampung skala **enterprise (1.000+ KOL)** dengan proses **approval langsung (oleh Admin)** untuk pendaftaran dan pencairan komisi.

Model bisnis utama: **agensi menerima fee/pembayaran dari brand, kemudian membagi komisi kepada KOL** berdasarkan skema yang transparan dan terstruktur.

---

## 2. Problem Statement

### Kondisi Saat Ini (As-Is)

| Masalah                                                        | Dampak                                                                                 |
| -------------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| Data KOL tersebar di spreadsheet, WhatsApp, dan catatan manual | Sulit mencari KOL yang sesuai untuk campaign tertentu; data tidak konsisten            |
| Proses akuisisi KOL baru tidak terstruktur                     | Calon KOL tidak tahu status pendaftarannya; banyak follow-up manual                    |
| Perhitungan komisi dilakukan manual (Excel)                    | Rawan kesalahan hitung; KOL tidak bisa mengecek komisi secara real-time                |
| Tidak ada tracking progress endorsement yang terpusat          | Admin kesulitan memonitor banyak endorsement paralel; KOL tidak tahu jadwal berikutnya |
| Bukti konten (screenshot posting, link) dikirim via chat       | Sulit dilacak, mudah hilang, tidak ada arsip terstruktur                               |
| Tidak ada mekanisme approval formal                            | Keputusan bergantung pada satu orang; bottleneck dan kurang akuntabel                  |

### Kondisi yang Diinginkan (To-Be)

- Satu platform terpusat untuk seluruh lifecycle KOL: akuisisi → onboarding → assignment → monitoring → pembayaran komisi
- Proses approval bertingkat yang formal dan tercatat
- Transparansi penuh bagi KOL terhadap progress kerja sama dan komisi mereka
- Kemampuan mengelola 1.000+ KOL secara efisien dengan pencarian, filter, dan dashboard

---

## 3. Goals & Success Metrics

### Goals

| #   | Goal                          | Deskripsi                                                                                                          |
| --- | ----------------------------- | ------------------------------------------------------------------------------------------------------------------ |
| G1  | Digitalisasi akuisisi KOL     | Proses pendaftaran → review → approval/reject berjalan di platform dengan audit trail                              |
| G2  | Transparansi komisi           | KOL dapat melihat rincian komisi, status pembayaran, dan riwayat kapan saja                                        |
| G3  | Monitoring progress real-time | Superadmin memiliki dashboard untuk memonitor semua endorsement aktif; KOL tahu jadwal & status endorsement mereka |
| G4  | Efisiensi operasional         | Mengurangi pekerjaan manual (spreadsheet, chat follow-up) secara signifikan                                        |
| G5  | Skalabilitas                  | Platform mampu menampung 1.000+ KOL aktif tanpa degradasi performa                                                 |

### Success Metrics (KPI)

| Metrik                                                            | Target         | Periode     |
| ----------------------------------------------------------------- | -------------- | ----------- |
| Waktu rata-rata proses approval pendaftaran KOL                   | ≤ 3 hari kerja | Per bulan   |
| Persentase KOL yang login minimal 1×/minggu                       | ≥ 60%          | Per bulan   |
| Akurasi perhitungan komisi (error rate)                           | 0%             | Per kuartal |
| Waktu rata-rata pencarian KOL sesuai kriteria                     | ≤ 30 detik     | Per bulan   |
| Jumlah endorsement yang progress-nya tercatat lengkap di platform | ≥ 95%          | Per bulan   |
| Uptime platform                                                   | ≥ 99.5%        | Per bulan   |

---

## 4. User Personas

### Persona 1: Superadmin — "Rina, Talent Manager"

| Atribut            | Detail                                                                                                                                                       |
| ------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Peran**          | Talent Manager di agensi Majapahit                                                                                                                           |
| **Usia**           | 28 tahun                                                                                                                                                     |
| **Latar Belakang** | Berpengalaman 4 tahun di industri influencer marketing; terbiasa dengan spreadsheet dan tools kolaborasi                                                     |
| **Tujuan Utama**   | Mengelola 500+ KOL secara efisien; memastikan semua endorsement berjalan sesuai jadwal; memproses komisi tepat waktu                                         |
| **Pain Points**    | Data tersebar di banyak tempat; perhitungan komisi manual rawan error; sulit melacak progress banyak endorsement bersamaan; follow-up via chat memakan waktu |
| **Harapan**        | Dashboard terpusat dengan ringkasan cepat; filter & pencarian KOL yang powerful; approval process yang jelas; notifikasi otomatis untuk deadline             |
| **Tech Savviness** | Menengah — terbiasa menggunakan web app, Google Sheets, dan tools manajemen proyek                                                                           |

### Persona 2: KOL — "Dimas, Lifestyle Influencer"

| Atribut            | Detail                                                                                                                                |
| ------------------ | ------------------------------------------------------------------------------------------------------------------------------------- |
| **Peran**          | Lifestyle influencer dengan 150K followers di Instagram & TikTok                                                                      |
| **Usia**           | 24 tahun                                                                                                                              |
| **Latar Belakang** | Content creator full-time selama 2 tahun; bergabung dengan agensi untuk mendapatkan deal endorsement yang lebih besar dan terstruktur |
| **Tujuan Utama**   | Mengetahui jadwal endorsement berikutnya; memastikan komisi dibayar tepat waktu dan sesuai; upload bukti konten dengan mudah          |
| **Pain Points**    | Tidak tahu kapan komisi cair; bingung endorsement mana yang sudah selesai/belum; harus bertanya ke admin via chat untuk info dasar    |
| **Harapan**        | Portal self-service yang jelas; notifikasi tugas baru; riwayat komisi yang transparan; proses upload bukti yang simpel                |
| **Tech Savviness** | Tinggi untuk sosial media, menengah untuk web app bisnis                                                                              |

---

## 5. Scope & Out of Scope

### 5.1 In-Scope

#### Superadmin

| Fitur                          | Deskripsi                                                                                                       |
| ------------------------------ | --------------------------------------------------------------------------------------------------------------- |
| Manajemen Data KOL             | CRUD profil KOL: nama, kategori/niche, platform sosmed, jumlah followers, engagement rate, rate card, kontak    |
| Akuisisi KOL Baru              | Formulir pendaftaran publik → approval/reject langsung oleh Admin dengan catatan          |
| Manajemen Komisi               | Pencatatan komisi per endorsement, skema komisi per KOL/tier, riwayat pembayaran, approval pencairan langsung oleh Admin |
| Manajemen Brand/Klien          | Data brand, PIC brand, campaign aktif, riwayat campaign                                                         |
| Manajemen Campaign/Endorsement | Buat campaign → assign KOL → set deadline → tracking progress → verifikasi bukti konten → selesai               |
| Monitoring & Dashboard         | Dashboard ringkas: jumlah KOL aktif, endorsement berjalan, pending approval, komisi belum cair                  |
| Pencarian & Filter KOL         | Filter berdasarkan niche, platform, jumlah followers, engagement rate, lokasi, tier, status                     |
| Notifikasi                     | Notifikasi in-app untuk: pendaftaran baru, deadline endorsement, permintaan pencairan komisi                    |
| Audit Trail                    | Log aktivitas untuk semua aksi kritis (approval, reject, perubahan status, perubahan komisi)                    |

#### KOL/Influencer

| Fitur                        | Deskripsi                                                                                |
| ---------------------------- | ---------------------------------------------------------------------------------------- |
| Dashboard KOL                | Ringkasan: endorsement aktif, komisi bulan ini, tugas pending, notifikasi                |
| Progress Endorsement         | Daftar endorsement aktif dengan status (assigned/in-progress/submitted/approved/selesai) |
| Jadwal Endorsement Mendatang | Timeline endorsement yang akan datang beserta detail brand & brief                       |
| Riwayat & Status Komisi      | Riwayat komisi lengkap dengan status (pending/diproses/dicairkan), tanggal, dan nominal  |
| Upload Bukti Konten          | Upload screenshot/link posting per endorsement (wajib)                                   |
| Update Profil & Rate Card    | Edit profil, platform sosmed, rate card                                                  |
| Riwayat Endorsement          | Arsip semua endorsement yang pernah dikerjakan                                           |
| Notifikasi                   | Notifikasi in-app untuk: tugas baru, status komisi berubah, perubahan jadwal             |

### 5.2 Out of Scope

| Item                                                         | Alasan                                                        |
| ------------------------------------------------------------ | ------------------------------------------------------------- |
| Integrasi payment gateway otomatis                           | Kompleksitas tinggi; pencatatan manual sudah cukup untuk MVP  |
| Integrasi API media sosial (auto-fetch followers/engagement) | Ketergantungan pada API pihak ketiga; data diinput manual     |
| Aplikasi mobile native                                       | Blade responsive sudah memadai; mobile app = phase berikutnya |
| Fitur multi-tenant                                           | Hanya untuk agensi Majapahit; tidak disewakan ke agensi lain  |
| Tanda tangan digital/e-signature                             | Kontrak ditangani di luar platform untuk saat ini             |

---

## 6. User Roles & Permission Matrix

### 6.1 Definisi Role

Platform memiliki **2 role**:

| Role         | Deskripsi                                                                                                                                   |
| ------------ | ------------------------------------------------------------------------------------------------------------------------------------------- |
| **Admin**    | Operasional harian + keputusan final: input data, assign endorsement, approve/reject pendaftaran KOL & pencairan komisi, kelola user & settings |
| **KOL**      | Influencer yang terdaftar: melihat progress endorsement, komisi, upload bukti konten, update profil                                         |

### 6.2 Permission Matrix

| Aksi                               | Admin | KOL                 |
| ---------------------------------- | ----- | ------------------- |
| **Dashboard ringkas**              | ✅    | ✅ (versi KOL)      |
| **Lihat daftar KOL**               | ✅    | ❌                  |
| **Tambah/edit data KOL**           | ✅    | Profil sendiri saja |
| **Approve/reject pendaftaran KOL** | ✅    | ❌                  |
| **Lihat daftar brand/klien**       | ✅    | ❌                  |
| **Tambah/edit brand/klien**        | ✅    | ❌                  |
| **Buat campaign**                  | ✅    | ❌                  |
| **Assign KOL ke endorsement**      | ✅    | ❌                  |
| **Update progress endorsement**    | ✅    | ❌                  |
| **Verifikasi bukti konten**        | ✅    | ❌                  |
| **Upload bukti konten**            | ❌    | ✅                  |
| **Lihat komisi (semua KOL)**       | ✅    | ❌                  |
| **Lihat komisi (sendiri)**         | ❌    | ✅                  |
| **Input/edit data komisi**         | ✅    | ❌                  |
| **Approve pencairan komisi**       | ✅    | ❌                  |
| **Ajukan pencairan komisi**        | ✅    | ✅ (sendiri)        |
| **Lihat audit trail**              | ✅    | ❌                  |
| **Kelola user & role**             | ✅    | ❌                  |
| **Export laporan**                  | ✅    | ❌                  |
| **Konfigurasi settings**           | ✅    | ❌                  |

---

## 7. Functional Requirements

### 7.a Frontend/UI Requirements

#### 7.a.1 — Superadmin: Dashboard

> **Sebagai** Superadmin, **saya ingin** melihat dashboard ringkas di halaman utama setelah login, **sehingga** saya bisa mendapatkan gambaran cepat tentang kondisi operasional agensi.

**Acceptance Criteria:**

- [ ] Dashboard menampilkan kartu ringkasan (summary cards) berisi:
  - Jumlah KOL aktif
  - Jumlah endorsement sedang berjalan
  - Jumlah pendaftaran KOL pending review
  - Jumlah permintaan pencairan komisi pending
  - Total komisi belum cair bulan ini (Rp)
- [ ] Terdapat grafik/chart: tren jumlah endorsement per bulan (6 bulan terakhir)
- [ ] Terdapat tabel "Endorsement Mendekati Deadline" (7 hari ke depan) dengan kolom: nama KOL, brand, deadline, status
- [ ] Terdapat tabel "Pendaftaran KOL Terbaru" (5 terbaru) dengan quick-action link ke halaman review
- [ ] Terdapat daftar notifikasi terbaru (5 terbaru) di sidebar/panel
- [ ] Summary cards bisa diklik untuk navigasi ke halaman detail terkait

---

#### 7.a.2 — Superadmin: Manajemen Data KOL

> **Sebagai** Superadmin (Admin), **saya ingin** melihat daftar semua KOL dengan filter & pencarian yang lengkap, **sehingga** saya bisa menemukan KOL yang sesuai untuk campaign tertentu dengan cepat.

**Acceptance Criteria — Halaman Daftar KOL:**

- [ ] Tabel daftar KOL menampilkan: foto profil (thumbnail), nama, username utama, niche/kategori, platform utama, jumlah followers, engagement rate, tier, status (aktif/nonaktif/pending/blacklist), tanggal bergabung
- [ ] Filter tersedia untuk: niche (multi-select), platform (multi-select), range followers (min-max), range engagement rate (min-max), tier, status, lokasi/kota
- [ ] Search bar untuk pencarian berdasarkan nama atau username
- [ ] Sorting tersedia untuk: nama (A-Z/Z-A), followers (terbesar/terkecil), engagement rate, tanggal bergabung
- [ ] Pagination dengan opsi jumlah item per halaman (10/25/50/100)
- [ ] Tombol "Export CSV" untuk mengunduh data KOL yang ter-filter
- [ ] Tombol "Tambah KOL" untuk menambahkan KOL secara manual (bypass formulir publik)

**Acceptance Criteria — Halaman Detail/Edit KOL:**

- [ ] Form profil KOL mencakup field:
  - Informasi dasar: nama lengkap, nama panggilan, email, nomor telepon, tanggal lahir, jenis kelamin, kota/domisili, foto profil
  - Sosial media: daftar platform (Instagram, TikTok, YouTube, Twitter/X, dll) — tiap platform: username, URL profil, jumlah followers, engagement rate
  - Profesional: niche/kategori (bisa lebih dari satu), tier (Nano/Micro/Macro/Mega), rate card (per platform & per tipe konten: feed post, story, reels, video, dll), catatan khusus
  - Administrasi: nomor rekening bank, nama bank, atas nama rekening, NPWP (opsional)
- [ ] Tab navigasi untuk mengakses: Profil | Endorsement | Komisi | Dokumen | Log Aktivitas
- [ ] Tombol "Ubah Status" dengan dropdown: Aktif, Nonaktif, Blacklist (+ field alasan wajib diisi untuk Nonaktif/Blacklist)

---

#### 7.a.3 — Superadmin: Akuisisi KOL Baru (Pendaftaran & Approval)

> **Sebagai** Superadmin (Admin), **saya ingin** melihat daftar pendaftaran KOL baru beserta detail profil, **sehingga** saya bisa memproses approval/reject secara terstruktur.

**Acceptance Criteria — Halaman Publik Pendaftaran KOL:**

- [ ] Formulir pendaftaran publik (tanpa login) berisi field:
  - Nama lengkap, email, nomor telepon, kota/domisili
  - Platform sosmed utama + username + URL profil + jumlah followers (self-reported)
  - Niche/kategori (dropdown multi-select)
  - Rate card yang diharapkan (opsional)
  - Upload portofolio/screenshot konten terbaik (maks 5 file, maks 5MB/file)
  - Alasan ingin bergabung (textarea)
  - Checkbox persetujuan syarat & ketentuan
- [ ] Setelah submit: halaman konfirmasi dengan nomor registrasi & informasi bahwa akan diproses dalam X hari kerja
- [ ] Validasi real-time pada field email (format), nomor telepon (format Indonesia), URL profil sosmed

**Acceptance Criteria — Halaman Review Pendaftaran (Internal):**

- [ ] Tabel daftar pendaftaran: nomor registrasi, nama, niche, platform, followers, tanggal daftar, status (pending review / approved / rejected)
- [ ] Filter: status, niche, platform, tanggal daftar (range)
- [ ] Halaman detail pendaftaran menampilkan semua data yang diisi + file portofolio yang bisa di-preview
- [ ] **Admin** dapat:
  - Memberikan skor/rating (1-5) untuk beberapa kriteria internal (opsional) dan menulis catatan
  - Klik "Approve" (dengan catatan opsional) → status berubah menjadi "Approved", akun KOL otomatis dibuat
  - Klik "Reject" (dengan alasan wajib) → data pendaftaran dihapus (hard delete), hanya audit log dipertahankan
- [ ] Timeline/log aktivitas review ditampilkan di halaman detail (siapa melakukan apa, kapan)

---

#### 7.a.4 — Superadmin: Manajemen Brand/Klien

> **Sebagai** Superadmin (Admin), **saya ingin** mengelola data brand/klien yang bekerja sama, **sehingga** saya bisa melacak campaign dan endorsement per brand.

**Acceptance Criteria:**

- [ ] Tabel daftar brand: logo, nama brand, industri/kategori, PIC (nama + kontak), jumlah campaign aktif, total endorsement, status (aktif/nonaktif)
- [ ] Form tambah/edit brand: nama brand, industri, alamat, PIC (nama, jabatan, email, telepon), catatan, upload logo
- [ ] Halaman detail brand menampilkan: info brand + tab Campaign (daftar campaign aktif & riwayat)
- [ ] Search & filter: nama brand, industri, status

---

#### 7.a.5 — Superadmin: Manajemen Campaign & Endorsement

> **Sebagai** Superadmin (Admin), **saya ingin** membuat campaign, assign KOL, set deadline, dan tracking progress tiap endorsement, **sehingga** semua endorsement termonitor di satu tempat.

**Acceptance Criteria — Halaman Daftar Campaign:**

- [ ] Tabel campaign: nama campaign, brand, tanggal mulai, tanggal selesai, jumlah KOL assigned, progress (X/Y selesai), status (draft/aktif/selesai)
- [ ] Filter: brand, status, tanggal (range)
- [ ] Tombol "Buat Campaign Baru"

**Acceptance Criteria — Halaman Detail Campaign:**

- [ ] Info campaign: nama, brand, deskripsi/brief, tanggal mulai-selesai, budget total, persyaratan konten, do's & don'ts
- [ ] Tabel endorsement di dalam campaign:
  - Kolom: nama KOL, tipe konten, deadline, fee KOL, status (assigned/in-progress/content-submitted/content-approved/selesai), bukti konten
  - Quick action: lihat bukti, approve bukti, tolak bukti (dengan catatan)
- [ ] Tombol "Assign KOL" → modal/popup pencarian KOL dengan filter cepat (niche, followers, tier) → pilih → isi: tipe konten, deadline, fee KOL
- [ ] Progress bar visual (persentase endorsement selesai vs total)

**Acceptance Criteria — Form Buat/Edit Campaign:**

- [ ] Field: nama campaign, pilih brand (dropdown), deskripsi/brief (rich text), tanggal mulai, tanggal selesai, budget, upload brief attachment (PDF/gambar, maks 10MB), persyaratan konten (textarea), do's & don'ts (textarea)

---

#### 7.a.6 — Superadmin: Manajemen Komisi

> **Sebagai** Superadmin (Admin), **saya ingin** mencatat komisi per endorsement dan memproses pencairan, **sehingga** pembayaran ke KOL akurat dan tercatat rapi.

**Acceptance Criteria — Halaman Daftar Komisi:**

- [ ] Tabel komisi: nama KOL, campaign/brand, fee endorsement (Rp), komisi KOL (Rp), persentase komisi, status (pending/approved/diproses/dicairkan), tanggal pencairan
- [ ] Filter: status, KOL, brand, periode (bulan/tahun)
- [ ] Summary di atas tabel: total komisi pending, total komisi dicairkan bulan ini, total outstanding
- [ ] Tombol "Batch Approve" untuk memproses beberapa komisi sekaligus (khusus Admin)
- [ ] Export laporan komisi ke CSV/Excel

**Acceptance Criteria — Detail Komisi Per KOL:**

- [ ] Riwayat komisi KOL lengkap dalam tabel: no, campaign, brand, tipe konten, fee, komisi, status, tanggal
- [ ] Ringkasan: total komisi diterima (all-time), komisi bulan ini, komisi pending

**Acceptance Criteria — Alur Pencairan:**

- [ ] Admin menandai endorsement sebagai "selesai" → sistem otomatis menghitung komisi berdasarkan skema
- [ ] KOL/Admin mengajukan pencairan → status berubah "Pending"
- [ ] Admin memberikan keputusan → "Approve" atau "Reject"
- [ ] Setelah approve: Admin menandai "Sudah Dicairkan" + input tanggal & bukti transfer
- [ ] Seluruh alur tercatat di log aktivitas

---

#### 7.a.7 — Superadmin: Notifikasi

> **Sebagai** Superadmin, **saya ingin** menerima notifikasi in-app untuk event penting, **sehingga** saya tidak melewatkan deadline atau permintaan yang perlu ditindaklanjuti.

**Acceptance Criteria:**

- [ ] Ikon bell/lonceng di header dengan badge jumlah notifikasi belum dibaca
- [ ] Dropdown/panel notifikasi menampilkan daftar notif terbaru (20 terakhir) dengan: icon tipe, judul singkat, waktu, status baca/belum
- [ ] Klik notifikasi → navigasi ke halaman terkait
- [ ] Halaman "Semua Notifikasi" dengan filter: tipe, status baca, tanggal
- [ ] Tombol "Tandai semua sebagai dibaca"
- [ ] Tipe notifikasi yang di-trigger:
  - Pendaftaran KOL baru masuk (untuk Admin)
  - Endorsement mendekati deadline (H-3 dan H-1)
  - Bukti konten di-upload KOL (untuk Admin yang mengelola campaign)
  - Permintaan pencairan komisi baru (untuk Admin) — termasuk yang diajukan oleh KOL

---

#### 7.a.8 — KOL: Dashboard

> **Sebagai** KOL, **saya ingin** melihat dashboard pribadi setelah login, **sehingga** saya langsung tahu apa yang perlu saya kerjakan dan status komisi saya.

**Acceptance Criteria:**

- [ ] Kartu ringkasan:
  - Jumlah endorsement aktif
  - Komisi bulan ini (Rp)
  - Komisi pending pencairan (Rp)
  - Tugas menunggu upload bukti
- [ ] Daftar "Endorsement Aktif" (3 teratas): nama brand, tipe konten, deadline, status, quick-action "Upload Bukti"
- [ ] Daftar "Endorsement Mendatang" (3 teratas): nama brand, tipe konten, tanggal mulai
- [ ] Panel notifikasi terbaru (5 terbaru)
- [ ] Sapaan personalisasi: "Halo, [Nama Panggilan]! 👋"

---

#### 7.a.9 — KOL: Progress Endorsement

> **Sebagai** KOL, **saya ingin** melihat daftar lengkap endorsement saya dengan status masing-masing, **sehingga** saya tahu mana yang sedang berjalan, perlu di-upload, dan sudah selesai.

**Acceptance Criteria:**

- [ ] Tab navigasi: Aktif | Mendatang | Riwayat
- [ ] **Tab Aktif**: tabel endorsement dengan status "assigned" hingga "content-approved" — kolom: brand, campaign, tipe konten, deadline, status, aksi
  - Status ditampilkan dengan badge berwarna
  - Tombol "Upload Bukti" muncul jika status = "in-progress" dan belum ada bukti
  - Tombol "Lihat Detail" untuk setiap endorsement
- [ ] **Tab Mendatang**: endorsement dengan status "assigned" yang start date-nya di masa depan — kolom: brand, campaign, tipe konten, tanggal mulai, deadline
  - Menampilkan brief/deskripsi singkat dan do's & don'ts
- [ ] **Tab Riwayat**: endorsement dengan status "selesai" — kolom: brand, campaign, tipe konten, tanggal selesai, fee, komisi
- [ ] Filter: brand, periode

---

#### 7.a.10 — KOL: Upload Bukti Konten

> **Sebagai** KOL, **saya ingin** upload bukti konten (screenshot/link posting) untuk setiap endorsement, **sehingga** agensi bisa memverifikasi bahwa saya sudah menyelesaikan tugas.

**Acceptance Criteria:**

- [ ] Form upload bukti per endorsement:
  - Upload file gambar/screenshot (maks 5 file, maks 5MB/file, format: JPG/PNG/WebP)
  - Input URL posting (link Instagram, TikTok, YouTube, dll)
  - Textarea catatan tambahan (opsional)
  - Tanggal posting
- [ ] Preview gambar yang di-upload sebelum submit
- [ ] Setelah submit: status endorsement otomatis berubah menjadi "content-submitted"
- [ ] KOL bisa melihat bukti yang sudah di-upload beserta status review (pending/approved/rejected)
- [ ] Jika ditolak: tampilkan alasan penolakan dari admin + tombol "Upload Ulang"

---

#### 7.a.11 — KOL: Riwayat & Status Komisi

> **Sebagai** KOL, **saya ingin** melihat riwayat komisi lengkap dan status pencairan, **sehingga** saya tahu berapa yang sudah saya terima dan berapa yang masih pending.

**Acceptance Criteria:**

- [ ] Ringkasan di atas halaman:
  - Total komisi diterima (all-time)
  - Komisi bulan ini
  - Komisi pending pencairan
- [ ] Tabel riwayat komisi: no, campaign, brand, tipe konten, fee endorsement, komisi (Rp), status (pending/diproses/dicairkan), tanggal pencairan
- [ ] Status ditampilkan dengan badge berwarna (kuning=pending, biru=diproses, hijau=dicairkan)
- [ ] Filter: status, periode (bulan/tahun)
- [ ] Tombol "Ajukan Pencairan" untuk komisi berstatus "Pending" — KOL bisa request pencairan sendiri
- [ ] Detail klik → popup/halaman detail menampilkan: rincian perhitungan komisi (fee × persentase), catatan admin (jika ada)

---

#### 7.a.12 — KOL: Update Profil & Rate Card

> **Sebagai** KOL, **saya ingin** mengupdate profil dan rate card saya, **sehingga** data saya selalu terkini untuk pertimbangan assignment endorsement.

**Acceptance Criteria:**

- [ ] Form edit profil (pre-filled dari data saat ini):
  - Foto profil (upload/ganti)
  - Nama panggilan, bio singkat, kota
  - Daftar sosial media: tambah/edit/hapus — platform, username, URL, jumlah followers, engagement rate
  - Rate card per platform & tipe konten
  - Nomor rekening, nama bank, atas nama (editable)
- [ ] Validasi: minimal 1 platform sosmed harus diisi; rate card tidak boleh kosong jika sudah pernah endorsement
- [ ] Tombol "Simpan" dengan konfirmasi
- [ ] Perubahan rate card tercatat di log (sebelum → sesudah)

---

#### 7.a.13 — KOL: Notifikasi

> **Sebagai** KOL, **saya ingin** menerima notifikasi untuk tugas baru dan perubahan status komisi, **sehingga** saya bisa merespons dengan cepat.

**Acceptance Criteria:**

- [ ] Ikon bell di header dengan badge
- [ ] Dropdown notifikasi (10 terbaru)
- [ ] Tipe notifikasi:
  - Endorsement baru di-assign ke saya
  - Perubahan jadwal/deadline endorsement
  - Bukti konten saya diapprove/ditolak
  - Status komisi berubah (diproses/dicairkan)
- [ ] Klik notifikasi → navigasi ke halaman terkait

---

### 7.b Backend/Business Logic Requirements

#### 7.b.1 — Autentikasi & Otorisasi

- **Sistem harus** mengautentikasi user menggunakan email + password (Laravel built-in auth).
  - Acceptance: login, logout, forgot password, reset password berfungsi.
- **Sistem harus** membedakan role user (Admin, KOL) dan mengontrol akses ke setiap route/action berdasarkan permission matrix (Section 6).
  - Acceptance: user yang tidak memiliki permission akan mendapat HTTP 403 Forbidden.
- **Sistem harus** menyimpan session dengan mekanisme "remember me" (opsional, default 30 hari).

#### 7.b.2 — Lifecycle Status KOL

- **Sistem harus** menerapkan state machine berikut untuk status KOL:

```
[Mendaftar] → Pending Review → Approved → Aktif ↔ Nonaktif
                               → Rejected         ↓
                                                Blacklist
```

- Acceptance:
  - Transisi status hanya bisa dilakukan oleh role yang berwenang
  - Setiap transisi wajib dicatat di audit trail (user, timestamp, status lama → baru, catatan)
  - Status "Blacklist" hanya bisa dilakukan oleh Admin dengan alasan wajib
  - KOL dengan status Nonaktif/Blacklist tidak bisa di-assign endorsement baru

#### 7.b.3 — Proses Approval — Pendaftaran KOL

- **Sistem harus** menjalankan alur approval langsung oleh Admin ketika pendaftaran KOL baru masuk:
  1. **Keputusan**: Admin melihat data pendaftaran, lalu approve atau reject (dengan opsi input skor/catatan internal)
  - Acceptance:
    - Setelah approved: sistem otomatis membuat akun user KOL dengan password random dan mengirimkan kredensial via email (atau link set password)
    - Setelah rejected: **data pendaftaran langsung dihapus (hard delete)** beserta file portofolio terkait. Hanya catatan di audit log yang dipertahankan.

#### 7.b.4 — Skema Komisi

Platform mendukung skema komisi berdasarkan **tier KOL** dengan **persentase berjenjang** dari fee endorsement yang dibayar brand ke agensi. Berikut skema default (bisa diubah per KOL jika ada perjanjian khusus):

| Tier KOL | Jumlah Followers | Persentase Komisi KOL (dari fee brand) | Persentase Agensi |
| -------- | ---------------- | -------------------------------------- | ----------------- |
| Nano     | < 10K            | 60%                                    | 40%               |
| Micro    | 10K – 100K       | 65%                                    | 35%               |
| Macro    | 100K – 1M        | 70%                                    | 30%               |
| Mega     | > 1M             | 75%                                    | 25%               |

- **Sistem harus** menghitung komisi KOL secara otomatis ketika endorsement ditandai "selesai" berdasarkan:
  - `komisi_kol = fee_endorsement × persentase_komisi_tier`
  - Jika KOL memiliki override persentase khusus → gunakan override tersebut
  - Acceptance:
    - Komisi dihitung otomatis, bisa di-override manual oleh Admin dengan alasan (tercatat di log)
    - Persentase default per tier bisa dikonfigurasi oleh Admin (halaman settings)
    - Persentase khusus per KOL bisa diset di profil KOL oleh Admin
    - Rate card KOL bersifat **indikatif** (negotiable) — fee endorsement aktual bisa berbeda dari rate card dan ditentukan saat assignment

#### 7.b.5 — Proses Approval — Pencairan Komisi

- **Sistem harus** menjalankan alur approval pencairan komisi:
  1. Admin **atau KOL** mengajukan pencairan (Admin bisa batch, KOL hanya komisi miliknya sendiri)
  2. Admin memberikan keputusan final: approve/reject
  3. Setelah approve: Admin menandai sebagai "Dicairkan" dengan input tanggal pencairan & bukti transfer
  - Acceptance:
    - KOL dapat mengajukan pencairan komisi miliknya sendiri yang berstatus "pending" melalui halaman Komisi → tombol "Ajukan Pencairan"
    - Batch approval: Admin bisa approve beberapa komisi sekaligus jika semuanya valid
    - Setiap tahap tercatat di audit trail
    - KOL menerima notifikasi in-app ketika status komisi berubah

#### 7.b.6 — Manajemen Campaign & Endorsement

- **Sistem harus** memvalidasi ketika KOL di-assign ke endorsement:
  - KOL berstatus "Aktif"
  - KOL tidak memiliki conflict jadwal (endorsement lain di tanggal yang sama, jika enforce non-overlap diaktifkan)
  - Fee endorsement sudah diisi

  - Acceptance: assignment gagal jika validasi tidak terpenuhi, dengan pesan error yang jelas.

- **Sistem harus** menerapkan lifecycle endorsement:

```
Draft → Assigned → In-Progress → Content Submitted → Content Approved → Selesai
                                                    → Content Rejected → (kembali ke In-Progress)
```

- Acceptance:
  - Transisi otomatis: "In-Progress" → "Content Submitted" saat KOL upload bukti
  - Transisi manual: "Content Submitted" → "Content Approved" atau "Content Rejected" oleh Admin
  - "Content Rejected" → KOL harus upload ulang → kembali ke "Content Submitted"
  - "Content Approved" → Admin bisa tandai "Selesai" → trigger perhitungan komisi

- **Sistem harus** mengirim reminder otomatis (notifikasi in-app) kepada KOL H-3 dan H-1 sebelum deadline endorsement.

#### 7.b.7 — Upload & Penyimpanan File

- **Sistem harus** memvalidasi file yang di-upload:
  - Portofolio pendaftaran: maks 5 file, maks 5MB/file, format JPG/PNG/PDF
  - Bukti konten: maks 5 file, maks 5MB/file, format JPG/PNG/WebP
  - Logo brand: maks 1 file, maks 2MB, format JPG/PNG/SVG
  - Brief campaign: maks 1 file, maks 10MB, format PDF/JPG/PNG
  - Bukti transfer: maks 1 file, maks 5MB, format JPG/PNG/PDF

- **Sistem harus** menyimpan file di storage disk (Laravel filesystem) dengan struktur folder terorganisir: `/{tipe}/{id}/{timestamp}_{filename}`
- **Sistem harus** menghasilkan thumbnail untuk file gambar (untuk preview di daftar)

#### 7.b.8 — Notifikasi

- **Sistem harus** menyimpan notifikasi di database (tabel `notifications`) dengan field: user_id, tipe, judul, isi, url_tujuan, is_read, created_at.
- **Sistem harus** membuat notifikasi secara otomatis untuk event yang didefinisikan di Section 7.a.7 (Superadmin) dan 7.a.13 (KOL).
- **Sistem harus** menampilkan jumlah notifikasi belum dibaca di header (badge count) — di-render saat page load (SSR), tidak perlu real-time push.

#### 7.b.9 — Audit Trail

- **Sistem harus** mencatat log audit untuk setiap aksi kritis:
  - Perubahan status KOL (pending → reviewed → approved/rejected → aktif/nonaktif/blacklist)
  - Proses approval (pendaftaran & komisi): siapa, kapan, keputusan, catatan
  - Perubahan data komisi (nilai, persentase)
  - Perubahan rate card KOL
  - Perubahan status endorsement
  - Login user (terakhir login)

- **Sistem harus** menyimpan log di tabel `audit_logs` dengan field: id, user_id, action, entity_type, entity_id, old_values (JSON), new_values (JSON), ip_address, user_agent, created_at.

#### 7.b.10 — Pencarian & Filter

- **Sistem harus** mengimplementasikan pencarian KOL yang efisien untuk 1.000+ data menggunakan indeks MySQL yang tepat.
  - Acceptance: pencarian berdasarkan nama/username mengembalikan hasil dalam < 500ms
- **Sistem harus** mendukung filter gabungan (compound filter) di halaman daftar KOL: niche + platform + range followers + range engagement rate + tier + status + lokasi.
  - Acceptance: query filter gabungan mengembalikan hasil dalam < 1 detik untuk dataset 5.000 KOL

#### 7.b.11 — Email Transaksional

- **Sistem harus** mengirim email otomatis **hanya untuk hal esensial**:
  - Konfirmasi pendaftaran KOL (setelah submit formulir)
  - Kredensial akun baru (setelah KOL di-approve)
  - Reset password

- Notifikasi operasional lainnya (endorsement baru, status komisi, dll) **hanya via in-app notification**, tidak perlu email.
- Email dikirim menggunakan Laravel Mail (queue-based untuk menghindari delay pada response time).

#### 7.b.12 — Export Laporan Periodik

- **Sistem harus** menyediakan fitur export laporan dalam format CSV/Excel:
  - Laporan komisi per periode (bulanan/kuartalan)
  - Laporan endorsement per campaign
  - Laporan data KOL (dengan filter)
  - Laporan performa campaign (jumlah endorsement, completion rate)

- **Sistem harus** mendukung scheduled report generation:
  - Admin dapat mengatur jadwal export otomatis (mis. setiap akhir bulan)
  - Report yang sudah di-generate disimpan dan bisa diunduh dari halaman Laporan
  - Acceptance: laporan bulanan ter-generate otomatis pada tanggal 1 setiap bulan untuk periode bulan sebelumnya

---

## 8. Key User Flows

### Flow 1: Pendaftaran KOL Baru (End-to-End)

```
1. [Frontend] Calon KOL mengakses halaman pendaftaran publik (tanpa login)
2. [Frontend] Calon KOL mengisi formulir: data diri, sosmed, niche, portofolio, alasan bergabung
3. [Frontend] Klik "Daftar" → validasi client-side (field wajib, format email/telepon/URL)
4. [Backend]  Validasi server-side → simpan data pendaftaran (status: "Pending Review") → kirim email konfirmasi
5. [Frontend] Calon KOL melihat halaman konfirmasi dengan nomor registrasi
6. [Backend]  Sistem membuat notifikasi untuk semua user dengan role "Admin"
7. [Frontend] Admin login → melihat notifikasi "Pendaftaran baru" → klik → halaman detail pendaftaran
8. [Frontend] Admin mereview data dan klik "Approve" (atau "Reject" + alasan)
9. [Backend]  Jika Approve:
              - Ubah status → "Approved"
              - Buat akun user KOL (role: KOL, password random)
              - Kirim email kredensial ke KOL
              - Ubah status KOL → "Aktif"
    [Backend]  Jika Reject:
              - Catat ringkasan di audit log (nama, email, alasan reject)
              - Hard delete data pendaftaran + file portofolio
10. [Backend]  Catat seluruh flow di audit trail
11. [Frontend] KOL baru login dengan kredensial → diarahkan ke halaman "Set Password Baru" → Dashboard KOL
```

### Flow 2: Assignment Endorsement & Upload Bukti Konten

```
1. [Frontend] Admin membuat campaign baru: isi brief, brand, tanggal, budget
2. [Backend]  Simpan campaign (status: "Draft")
3. [Frontend] Admin klik "Assign KOL" → modal pencarian KOL (filter: niche, followers, tier)
4. [Frontend] Admin pilih KOL → isi: tipe konten, deadline, fee → submit
5. [Backend]  Validasi: KOL aktif, tidak conflict jadwal, fee terisi → simpan endorsement (status: "Assigned")
6. [Backend]  Buat notifikasi untuk KOL yang di-assign
7. [Frontend] Admin set campaign status → "Aktif"
8. [Frontend] KOL login → melihat notifikasi "Endorsement baru" → klik → halaman detail endorsement
9. [Frontend] KOL melihat brief, do's & don'ts, deadline
10. [Backend]  Status endorsement otomatis berubah "In-Progress" saat melewati tanggal mulai (atau manual oleh Admin)
11. [Frontend] KOL membuat konten → klik "Upload Bukti" → upload screenshot + link posting + tanggal posting
12. [Backend]  Validasi file (ukuran, format) → simpan file → ubah status "Content Submitted"
13. [Backend]  Buat notifikasi untuk Admin
14. [Frontend] Admin melihat bukti konten → preview → klik "Approve" atau "Reject" (+ catatan)
15. [Backend]  Jika Approve: ubah status "Content Approved"
              Jika Reject: ubah status "Content Rejected" → KOL harus upload ulang (kembali ke step 11)
16. [Frontend] Admin menandai endorsement "Selesai"
17. [Backend]  Hitung komisi otomatis → simpan record komisi (status: "Pending")
18. [Backend]  Catat di audit trail
```

### Flow 3: Pencairan Komisi

```
1. [Frontend] Admin ATAU KOL membuka halaman Komisi
              - Admin: filter status "Pending" → pilih beberapa komisi → klik "Ajukan Pencairan" (batch)
              - KOL: melihat komisi sendiri yang berstatus "Pending" → klik "Ajukan Pencairan" (per item)
2. [Backend]  Ubah status komisi terpilih → "Pending Review" → buat notifikasi untuk Admin (jika diajukan KOL)
3. [Frontend] Admin membuka halaman approval komisi (atau batch approve dari daftar komisi)
4. [Frontend] Admin cek detail: KOL, campaign, fee, komisi, perhitungan → klik "Approve" atau "Reject"
5. [Backend]  Jika Approve: ubah status "Approved"
              Jika Reject: ubah status "Rejected" + alasan → notifikasi KOL
6. [Frontend] Admin membuka komisi yang sudah approved → klik "Tandai Dicairkan" → input tanggal + upload bukti transfer
7. [Backend]  Ubah status "Dicairkan" → simpan bukti → buat notifikasi untuk KOL
8. [Frontend] KOL login → melihat notifikasi "Komisi dicairkan" → cek halaman Komisi → status "Dicairkan" ✅
9. [Backend]  Catat seluruh flow di audit trail
```

### Flow 4: KOL Cek Progress & Next Endorsement

```
1. [Frontend] KOL login → masuk Dashboard
2. [Frontend] KOL melihat kartu ringkasan: endorsement aktif, komisi bulan ini, tugas pending upload
3. [Frontend] KOL klik "Lihat Semua Endorsement" → halaman Progress Endorsement
4. [Frontend] KOL pilih tab "Mendatang" → melihat daftar endorsement yang start date-nya di masa depan
5. [Frontend] KOL klik salah satu → melihat detail: brand, brief, deadline, tipe konten
6. [Frontend] KOL pilih tab "Aktif" → melihat endorsement yang sedang berjalan
7. [Frontend] Jika ada yang perlu upload → klik "Upload Bukti" → (lanjut ke Flow 2, step 11)
8. [Frontend] KOL klik menu "Komisi" → melihat riwayat komisi dengan status masing-masing
9. [Frontend] Jika ada komisi berstatus "Pending" → klik "Ajukan Pencairan" → (lanjut ke Flow 3)
```

---

## 9. Screens/Page Inventory (Frontend)

### 9.1 Halaman Publik (Tanpa Login)

| #   | Halaman                 | Path (Saran)              | Tujuan                                     |
| --- | ----------------------- | ------------------------- | ------------------------------------------ |
| P1  | Landing/Pendaftaran KOL | `/daftar`                 | Formulir pendaftaran KOL baru              |
| P2  | Konfirmasi Pendaftaran  | `/daftar/konfirmasi`      | Menampilkan nomor registrasi & info proses |
| P3  | Login                   | `/login`                  | Login untuk Superadmin & KOL               |
| P4  | Lupa Password           | `/lupa-password`          | Request reset password                     |
| P5  | Reset Password          | `/reset-password/{token}` | Form reset password baru                   |

### 9.2 Halaman Superadmin

| #   | Halaman             | Path (Saran)              | Tujuan                                                            |
| --- | ------------------- | ------------------------- | ----------------------------------------------------------------- |
| A1  | Dashboard           | `/admin/dashboard`        | Ringkasan operasional                                             |
| A2  | Daftar KOL          | `/admin/kol`              | Tabel semua KOL + filter + search                                 |
| A3  | Detail KOL          | `/admin/kol/{id}`         | Detail profil, endorsement, komisi, dokumen KOL                   |
| A4  | Tambah KOL (Manual) | `/admin/kol/tambah`       | Form tambah KOL manual                                            |
| A5  | Edit KOL            | `/admin/kol/{id}/edit`    | Form edit data KOL                                                |
| A6  | Daftar Pendaftaran  | `/admin/pendaftaran`      | Tabel pendaftaran KOL masuk                                       |
| A7  | Detail Pendaftaran  | `/admin/pendaftaran/{id}` | Detail data pendaftaran + form review/approve                     |
| A8  | Daftar Brand        | `/admin/brand`            | Tabel semua brand                                                 |
| A9  | Detail Brand        | `/admin/brand/{id}`       | Detail brand + campaign terkait                                   |
| A10 | Tambah/Edit Brand   | `/admin/brand/tambah`     | Form data brand                                                   |
| A11 | Daftar Campaign     | `/admin/campaign`         | Tabel semua campaign                                              |
| A12 | Detail Campaign     | `/admin/campaign/{id}`    | Detail campaign + daftar endorsement + assign KOL                 |
| A13 | Buat/Edit Campaign  | `/admin/campaign/tambah`  | Form data campaign                                                |
| A14 | Daftar Komisi       | `/admin/komisi`           | Tabel semua komisi + filter + batch action                        |
| A15 | Detail Komisi KOL   | `/admin/komisi/kol/{id}`  | Riwayat komisi per KOL                                            |
| A16 | Semua Notifikasi    | `/admin/notifikasi`       | Daftar lengkap notifikasi                                         |
| A17 | Audit Trail         | `/admin/audit-trail`      | Log aktivitas (khusus Admin)                                      |
| A18 | Pengaturan          | `/admin/pengaturan`       | Konfigurasi: skema komisi per tier, daftar niche, daftar platform |
| A19 | Kelola User         | `/admin/users`            | CRUD user internal (khusus Admin)                                 |
| A20 | Laporan             | `/admin/laporan`          | Export & download laporan periodik (komisi, endorsement, KOL)     |

### 9.3 Halaman KOL

| #   | Halaman              | Path (Saran)                   | Tujuan                                            |
| --- | -------------------- | ------------------------------ | ------------------------------------------------- |
| K1  | Dashboard            | `/kol/dashboard`               | Ringkasan endorsement & komisi                    |
| K2  | Progress Endorsement | `/kol/endorsement`             | Daftar endorsement (tab: aktif/mendatang/riwayat) |
| K3  | Detail Endorsement   | `/kol/endorsement/{id}`        | Detail endorsement + upload bukti                 |
| K4  | Upload Bukti         | `/kol/endorsement/{id}/upload` | Form upload bukti konten                          |
| K5  | Riwayat Komisi       | `/kol/komisi`                  | Daftar riwayat komisi + status + ajukan pencairan |
| K6  | Detail Komisi        | `/kol/komisi/{id}`             | Rincian perhitungan komisi                        |
| K7  | Profil Saya          | `/kol/profil`                  | Lihat profil sendiri                              |
| K8  | Edit Profil          | `/kol/profil/edit`             | Edit profil & rate card                           |
| K9  | Notifikasi           | `/kol/notifikasi`              | Daftar lengkap notifikasi                         |
| K10 | Set Password Baru    | `/kol/set-password`            | Set password pertama kali (setelah approval)      |

---

## 10. Data Entities & Business Rules (Backend)

### 10.1 Entity Relationship (Konseptual)

```
USERS ──1:N── USER_ROLES ──N:1── ROLES
USERS ──1:1── KOL_PROFILES
USERS ──1:N── NOTIFICATIONS
USERS ──1:N── AUDIT_LOGS

KOL_PROFILES ──1:N── KOL_SOCIAL_MEDIA
KOL_PROFILES ──1:N── KOL_RATE_CARDS
KOL_PROFILES ──1:N── ENDORSEMENTS
KOL_PROFILES ──1:N── COMMISSIONS
KOL_PROFILES ──N:1── TIERS
KOL_PROFILES ──M:N── NICHES (via KOL_NICHES)

KOL_REGISTRATIONS ──1:N── REGISTRATION_REVIEWS
KOL_REGISTRATIONS ──1:N── REGISTRATION_FILES

BRANDS ──1:N── CAMPAIGNS
CAMPAIGNS ──1:N── ENDORSEMENTS
CAMPAIGNS ──1:N── CAMPAIGN_FILES

ENDORSEMENTS ──1:N── CONTENT_PROOFS
ENDORSEMENTS ──1:1── COMMISSIONS

CONTENT_PROOFS ──1:N── CONTENT_PROOF_FILES

COMMISSIONS ──1:N── COMMISSION_APPROVALS
```

### 10.2 Entitas Utama

#### `users`

| Kolom          | Tipe                | Keterangan   |
| -------------- | ------------------- | ------------ |
| id             | BIGINT AUTO_INCREMENT PK        |              |
| name           | VARCHAR(255)        | Nama lengkap |
| email          | VARCHAR(255) UNIQUE |              |
| password       | VARCHAR(255)        | Hashed       |
| is_active      | BOOLEAN             | Default true |
| last_login_at  | TIMESTAMP           |              |
| remember_token | VARCHAR(100)        |              |
| created_at     | TIMESTAMP           |              |
| updated_at     | TIMESTAMP           |              |

#### `roles`

| Kolom        | Tipe               | Keterangan                     |
| ------------ | ------------------ | ------------------------------ |
| id           | SERIAL PK          |                                |
| name         | VARCHAR(50) UNIQUE | admin, kol                     |
| display_name | VARCHAR(100)       |                                |

#### `user_roles`

| Kolom   | Tipe               | Keterangan |
| ------- | ------------------ | ---------- |
| user_id | BIGINT FK → users  |            |
| role_id | INT FK → roles     |            |
| PK      | (user_id, role_id) | Composite  |

#### `kol_profiles`

| Kolom                   | Tipe                     | Keterangan                            |
| ----------------------- | ------------------------ | ------------------------------------- |
| id                      | BIGINT AUTO_INCREMENT PK             |                                       |
| user_id                 | BIGINT FK → users UNIQUE |                                       |
| nickname                | VARCHAR(100)             | Nama panggilan                        |
| bio                     | TEXT                     |                                       |
| date_of_birth           | DATE                     |                                       |
| gender                  | VARCHAR(20)              |                                       |
| city                    | VARCHAR(100)             | Kota domisili                         |
| province                | VARCHAR(100)             | Provinsi                              |
| photo_path              | VARCHAR(500)             | Path foto profil                      |
| tier_id                 | INT FK → tiers           |                                       |
| commission_override_pct | DECIMAL(5,2)             | Override persentase komisi (nullable) |
| bank_name               | VARCHAR(100)             |                                       |
| bank_account_number     | VARCHAR(50)              |                                       |
| bank_account_name       | VARCHAR(255)             |                                       |
| npwp                    | VARCHAR(30)              | Opsional                              |
| status                  | VARCHAR(20)              | pending/aktif/nonaktif/blacklist      |
| status_reason           | TEXT                     | Alasan perubahan status               |
| joined_at               | TIMESTAMP                | Tanggal bergabung resmi               |
| created_at              | TIMESTAMP                |                                       |
| updated_at              | TIMESTAMP                |                                       |

**Index**: `idx_kol_profiles_status` ON (status), `idx_kol_profiles_tier` ON (tier_id), `idx_kol_profiles_city` ON (city)

#### `tiers`

| Kolom          | Tipe         | Keterangan                       |
| -------------- | ------------ | -------------------------------- |
| id             | SERIAL PK    |                                  |
| name           | VARCHAR(50)  | Nano/Micro/Macro/Mega            |
| min_followers  | INT          | Batas bawah followers            |
| max_followers  | INT          | Batas atas (nullable untuk Mega) |
| commission_pct | DECIMAL(5,2) | Persentase komisi default        |
| agency_pct     | DECIMAL(5,2) | Persentase agensi default        |
| created_at     | TIMESTAMP    |                                  |
| updated_at     | TIMESTAMP    |                                  |

#### `kol_social_media`

| Kolom           | Tipe                     | Keterangan                           |
| --------------- | ------------------------ | ------------------------------------ |
| id              | BIGINT AUTO_INCREMENT PK             |                                      |
| kol_profile_id  | BIGINT FK → kol_profiles |                                      |
| platform        | VARCHAR(50)              | instagram/tiktok/youtube/twitter/dll |
| username        | VARCHAR(255)             |                                      |
| profile_url     | VARCHAR(500)             |                                      |
| followers_count | INT                      | Self-reported                        |
| engagement_rate | DECIMAL(5,2)             | Dalam persen                         |
| created_at      | TIMESTAMP                |                                      |
| updated_at      | TIMESTAMP                |                                      |

**Index**: `idx_kol_social_platform` ON (kol_profile_id, platform), `idx_kol_social_followers` ON (followers_count)

#### `kol_rate_cards`

| Kolom          | Tipe                     | Keterangan                            |
| -------------- | ------------------------ | ------------------------------------- |
| id             | BIGINT AUTO_INCREMENT PK             |                                       |
| kol_profile_id | BIGINT FK → kol_profiles |                                       |
| platform       | VARCHAR(50)              |                                       |
| content_type   | VARCHAR(50)              | feed_post/story/reels/video/tweet/dll |
| rate           | DECIMAL(15,2)            | Dalam Rupiah                          |
| created_at     | TIMESTAMP                |                                       |
| updated_at     | TIMESTAMP                |                                       |

**Index**: `idx_rate_cards_kol_platform` ON (kol_profile_id, platform, content_type) UNIQUE

#### `kol_registrations`

| Kolom               | Tipe               | Keterangan                                |
| ------------------- | ------------------ | ----------------------------------------- |
| id                  | BIGINT AUTO_INCREMENT PK       |                                           |
| registration_number | VARCHAR(20) UNIQUE | Auto-generated (REG-YYYYMMDD-XXXX)        |
| full_name           | VARCHAR(255)       |                                           |
| email               | VARCHAR(255)       |                                           |
| phone               | VARCHAR(20)        |                                           |
| city                | VARCHAR(100)       |                                           |
| niches              | JSON              | Array of niche strings                    |
| expected_rate       | TEXT               | Rate card harapan (free text)             |
| join_reason         | TEXT               |                                           |
| status              | VARCHAR(20)        | pending_review/reviewed/approved/rejected |
| reviewed_by         | BIGINT FK → users  | Nullable                                  |
| reviewed_at         | TIMESTAMP          |                                           |
| approved_by         | BIGINT FK → users  | Nullable                                  |
| approved_at         | TIMESTAMP          |                                           |
| rejection_reason    | TEXT               |                                           |
| created_at          | TIMESTAMP          |                                           |
| updated_at          | TIMESTAMP          |                                           |

**Index**: `idx_registrations_status` ON (status), `idx_registrations_email` ON (email)

| notes               | TEXT               | Catatan internal admin                    |

#### `registration_files`

| Kolom           | Tipe                          | Keterangan        |
| --------------- | ----------------------------- | ----------------- |
| id              | BIGINT AUTO_INCREMENT PK                  |                   |
| registration_id | BIGINT FK → kol_registrations |                   |
| file_path       | VARCHAR(500)                  |                   |
| file_name       | VARCHAR(255)                  | Original filename |
| file_size       | INT                           | Dalam bytes       |
| mime_type       | VARCHAR(100)                  |                   |
| created_at      | TIMESTAMP                     |                   |

#### `brands`

| Kolom      | Tipe         | Keterangan       |
| ---------- | ------------ | ---------------- |
| id         | BIGINT AUTO_INCREMENT PK |                  |
| name       | VARCHAR(255) |                  |
| industry   | VARCHAR(100) |                  |
| address    | TEXT         |                  |
| logo_path  | VARCHAR(500) |                  |
| pic_name   | VARCHAR(255) | Person in charge |
| pic_title  | VARCHAR(100) | Jabatan PIC      |
| pic_email  | VARCHAR(255) |                  |
| pic_phone  | VARCHAR(20)  |                  |
| notes      | TEXT         |                  |
| is_active  | BOOLEAN      | Default true     |
| created_at | TIMESTAMP    |                  |
| updated_at | TIMESTAMP    |                  |

#### `campaigns`

| Kolom                | Tipe               | Keterangan               |
| -------------------- | ------------------ | ------------------------ |
| id                   | BIGINT AUTO_INCREMENT PK       |                          |
| brand_id             | BIGINT FK → brands |                          |
| name                 | VARCHAR(255)       |                          |
| description          | TEXT               | Brief/deskripsi campaign |
| start_date           | DATE               |                          |
| end_date             | DATE               |                          |
| budget               | DECIMAL(15,2)      | Budget total (Rp)        |
| content_requirements | TEXT               | Persyaratan konten       |
| dos_and_donts        | TEXT               |                          |
| status               | VARCHAR(20)        | draft/aktif/selesai      |
| created_by           | BIGINT FK → users  |                          |
| created_at           | TIMESTAMP          |                          |
| updated_at           | TIMESTAMP          |                          |

**Index**: `idx_campaigns_brand` ON (brand_id), `idx_campaigns_status` ON (status), `idx_campaigns_dates` ON (start_date, end_date)

#### `campaign_files`

| Kolom       | Tipe                  | Keterangan |
| ----------- | --------------------- | ---------- |
| id          | BIGINT AUTO_INCREMENT PK          |            |
| campaign_id | BIGINT FK → campaigns |            |
| file_path   | VARCHAR(500)          |            |
| file_name   | VARCHAR(255)          |            |
| file_size   | INT                   |            |
| mime_type   | VARCHAR(100)          |            |
| created_at  | TIMESTAMP             |            |

#### `endorsements`

| Kolom          | Tipe                     | Keterangan                                                                             |
| -------------- | ------------------------ | -------------------------------------------------------------------------------------- |
| id             | BIGINT AUTO_INCREMENT PK             |                                                                                        |
| campaign_id    | BIGINT FK → campaigns    |                                                                                        |
| kol_profile_id | BIGINT FK → kol_profiles |                                                                                        |
| content_type   | VARCHAR(50)              | feed_post/story/reels/video/dll                                                        |
| fee            | DECIMAL(15,2)            | Fee yang dibayar brand untuk endorsement ini                                           |
| deadline       | DATE                     |                                                                                        |
| start_date     | DATE                     | Nullable                                                                               |
| status         | VARCHAR(30)              | draft/assigned/in_progress/content_submitted/content_approved/content_rejected/selesai |
| assigned_by    | BIGINT FK → users        |                                                                                        |
| completed_at   | TIMESTAMP                |                                                                                        |
| notes          | TEXT                     |                                                                                        |
| created_at     | TIMESTAMP                |                                                                                        |
| updated_at     | TIMESTAMP                |                                                                                        |

**Index**: `idx_endorsements_kol` ON (kol_profile_id), `idx_endorsements_campaign` ON (campaign_id), `idx_endorsements_status` ON (status), `idx_endorsements_deadline` ON (deadline)

#### `content_proofs`

| Kolom          | Tipe                     | Keterangan                |
| -------------- | ------------------------ | ------------------------- |
| id             | BIGINT AUTO_INCREMENT PK             |                           |
| endorsement_id | BIGINT FK → endorsements |                           |
| posted_at      | DATE                     | Tanggal posting           |
| post_url       | VARCHAR(500)             | Link posting              |
| notes          | TEXT                     | Catatan KOL               |
| review_status  | VARCHAR(20)              | pending/approved/rejected |
| review_notes   | TEXT                     | Catatan Admin             |
| reviewed_by    | BIGINT FK → users        |                           |
| reviewed_at    | TIMESTAMP                |                           |
| created_at     | TIMESTAMP                |                           |

#### `content_proof_files`

| Kolom            | Tipe                       | Keterangan |
| ---------------- | -------------------------- | ---------- |
| id               | BIGINT AUTO_INCREMENT PK               |            |
| content_proof_id | BIGINT FK → content_proofs |            |
| file_path        | VARCHAR(500)               |            |
| file_name        | VARCHAR(255)               |            |
| file_size        | INT                        |            |
| mime_type        | VARCHAR(100)               |            |
| created_at       | TIMESTAMP                  |            |

#### `commissions`

| Kolom                   | Tipe                            | Keterangan                                                  |
| ----------------------- | ------------------------------- | ----------------------------------------------------------- |
| id                      | BIGINT AUTO_INCREMENT PK                    |                                                             |
| endorsement_id          | BIGINT FK → endorsements UNIQUE |                                                             |
| kol_profile_id          | BIGINT FK → kol_profiles        |                                                             |
| endorsement_fee         | DECIMAL(15,2)                   | Fee dari brand                                              |
| commission_pct          | DECIMAL(5,2)                    | Persentase komisi yang digunakan                            |
| commission_amount       | DECIMAL(15,2)                   | Nominal komisi KOL                                          |
| agency_amount           | DECIMAL(15,2)                   | Nominal bagian agensi                                       |
| is_override             | BOOLEAN                         | Apakah menggunakan override manual                          |
| override_reason         | TEXT                            | Alasan override (jika ada)                                  |
| status                  | VARCHAR(20)                     | pending/approved/rejected/dicairkan         |
| disbursed_at            | DATE                            | Tanggal pencairan                                           |
| disbursement_proof_path | VARCHAR(500)                    | Bukti transfer                                              |
| created_at              | TIMESTAMP                       |                                                             |
| updated_at              | TIMESTAMP                       |                                                             |

**Index**: `idx_commissions_kol` ON (kol_profile_id), `idx_commissions_status` ON (status)



#### `notifications`

| Kolom      | Tipe              | Keterangan             |
| ---------- | ----------------- | ---------------------- |
| id         | UUID PK           |                        |
| user_id    | BIGINT FK → users |                        |
| type       | VARCHAR(50)       | Tipe notifikasi        |
| title      | VARCHAR(255)      |                        |
| body       | TEXT              |                        |
| target_url | VARCHAR(500)      | URL tujuan saat diklik |
| is_read    | BOOLEAN           | Default false          |
| read_at    | TIMESTAMP         |                        |
| created_at | TIMESTAMP         |                        |

**Index**: `idx_notifications_user_read` ON (user_id, is_read, created_at DESC)

#### `audit_logs`

| Kolom       | Tipe              | Keterangan                                               |
| ----------- | ----------------- | -------------------------------------------------------- |
| id          | BIGINT AUTO_INCREMENT PK      |                                                          |
| user_id     | BIGINT FK → users |                                                          |
| action      | VARCHAR(100)      | Deskripsi aksi                                           |
| entity_type | VARCHAR(50)       | Nama entitas (kol_profile, endorsement, commission, dll) |
| entity_id   | BIGINT            | ID entitas terkait                                       |
| old_values  | JSON             | Nilai sebelum perubahan                                  |
| new_values  | JSON             | Nilai setelah perubahan                                  |
| ip_address  | VARCHAR(45)       |                                                          |
| user_agent  | TEXT              |                                                          |
| created_at  | TIMESTAMP         |                                                          |

**Index**: `idx_audit_entity` ON (entity_type, entity_id), `idx_audit_user` ON (user_id), `idx_audit_created` ON (created_at DESC)

#### `niches` (Lookup table)

| Kolom     | Tipe                | Keterangan                                                                                                                            |
| --------- | ------------------- | ------------------------------------------------------------------------------------------------------------------------------------- |
| id        | SERIAL PK           |                                                                                                                                       |
| name      | VARCHAR(100) UNIQUE | lifestyle, beauty, gaming, food, travel, tech, fashion, health, education, parenting, automotive, sports, entertainment, finance, dll |
| is_active | BOOLEAN             | Default true                                                                                                                          |

#### `kol_niches` (Pivot table)

| Kolom          | Tipe                       | Keterangan |
| -------------- | -------------------------- | ---------- |
| kol_profile_id | BIGINT FK → kol_profiles   |            |
| niche_id       | INT FK → niches            |            |
| PK             | (kol_profile_id, niche_id) |            |

### 10.3 Business Rules Penting

#### BR1: Perhitungan Komisi

```
JIKA kol_profiles.commission_override_pct IS NOT NULL:
    komisi_pct = commission_override_pct
LAINNYA:
    komisi_pct = tiers.commission_pct (berdasarkan tier KOL saat ini)

commission_amount = endorsement_fee × (komisi_pct / 100)
agency_amount = endorsement_fee - commission_amount
```

#### BR2: Lifecycle Status KOL

- `pending` → hanya melalui proses pendaftaran
- `pending` → `aktif` → setelah di-approve oleh Admin
- `aktif` → `nonaktif` → oleh Admin, wajib alasan, reversible
- `nonaktif` → `aktif` → oleh Admin
- `aktif`/`nonaktif` → `blacklist` → hanya oleh Admin, wajib alasan, **tidak reversible** (irreversible, harus eskalasi manual)
- KOL dengan status selain `aktif` **tidak boleh** di-assign endorsement baru

#### BR3: Validasi Assignment Endorsement

- KOL.status harus = `aktif`
- endorsement.fee harus > 0
- endorsement.deadline harus > hari ini
- (Opsional, configurable) Tidak boleh ada overlap jadwal endorsement untuk KOL yang sama

#### BR4: Urutan Approval Komisi

- `pending` (saat diajukan) → `approved`/`rejected` (Admin keputusan final)
- `approved` → `dicairkan` (Admin tandai + bukti)

#### BR5: Auto-Reminder Deadline

- Sistem menjalankan scheduled task (Laravel Scheduler) setiap hari pukul 08:00 WIB
- Cek endorsement dengan deadline H-3 dan H-1 → buat notifikasi untuk KOL terkait
- Cek endorsement dengan deadline H+1 (melewati deadline) → buat notifikasi untuk Admin (overdue warning)

#### BR6: Nomor Registrasi

- Format: `REG-YYYYMMDD-XXXX` (XXXX = auto-increment per hari, reset tiap hari)
- Contoh: `REG-20260902-0001`, `REG-20260902-0002`

#### BR7: Soft Delete

- Entitas utama (KOL, Brand, Campaign) menggunakan soft delete (`deleted_at` timestamp)
- Data yang di-soft-delete tidak muncul di listing default, tapi bisa diakses via filter "Termasuk yang dihapus" (khusus Admin)

---

## 11. Non-Functional Requirements

### 11.1 Frontend (Blade View)

| #       | Requirement                | Detail                                                                                                                                                                                    |
| ------- | -------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| NF-FE-1 | Konsistensi UX             | Semua halaman menggunakan layout & komponen UI yang konsisten (header, sidebar, breadcrumb, tombol, tabel, form, alert). Gunakan template Blade layout yang ter-standarisasi.             |
| NF-FE-2 | Responsif                  | Semua halaman responsif untuk desktop (≥1024px) dan tablet (≥768px). Halaman KOL juga responsif untuk mobile (≥375px). Halaman admin minimal responsif untuk tablet.                      |
| NF-FE-3 | Loading State              | Tombol submit menampilkan spinner/loading saat form sedang diproses (disable double-click). Tabel menampilkan skeleton/loading saat data dimuat.                                          |
| NF-FE-4 | Empty State                | Halaman tabel/daftar menampilkan pesan dan ilustrasi jika data kosong (bukan tabel kosong tanpa penjelasan). Contoh: "Belum ada endorsement aktif. Endorsement baru akan muncul di sini." |
| NF-FE-5 | Error State                | Form menampilkan pesan error per field (inline validation feedback). Halaman error (404, 403, 500) menggunakan template custom yang konsisten dengan branding.                            |
| NF-FE-6 | Konfirmasi Aksi Destruktif | Semua aksi destruktif (hapus, reject, blacklist, batalkan) memerlukan konfirmasi modal dengan deskripsi jelas.                                                                            |
| NF-FE-7 | Aksesibilitas Dasar        | Label pada semua input form. Kontras warna memenuhi WCAG AA. Navigasi keyboard untuk elemen interaktif utama.                                                                             |
| NF-FE-8 | Bahasa                     | Seluruh UI menggunakan Bahasa Indonesia. Label, placeholder, pesan error, notifikasi — semua dalam Bahasa Indonesia.                                                                      |

### 11.2 Backend

| #        | Requirement            | Detail                                                                                                                                                                                                              |
| -------- | ---------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| NF-BE-1  | Validasi Data          | Semua input divalidasi di server-side (Laravel Form Request) terlepas dari validasi client-side. Tidak boleh mengandalkan validasi frontend saja.                                                                   |
| NF-BE-2  | Keamanan Akses         | Middleware role-based authorization di setiap route. CSRF protection aktif di semua form. Rate limiting pada halaman login (maks 5 attempt per menit) dan pendaftaran publik.                                       |
| NF-BE-3  | Audit Trail            | Setiap aksi kritis tercatat di `audit_logs` (lihat Section 7.b.9). Log tidak boleh dihapus atau di-edit.                                                                                                            |
| NF-BE-4  | Performa Query         | Halaman daftar KOL (dengan filter gabungan) merespons < 1 detik untuk dataset 5.000 KOL. Pencarian nama/username < 500ms. Dashboard merespons < 2 detik. Gunakan database indexing yang tepat (lihat Section 10.2). |
| NF-BE-5  | Queue untuk Email      | Email transaksional dikirim via Laravel Queue (async) untuk menghindari delay pada HTTP response.                                                                                                                   |
| NF-BE-6  | Backup Data            | Database MySQL di-backup harian (automated). File storage di-backup mingguan.                                                                                                                                  |
| NF-BE-7  | Enkripsi Data Sensitif | Password di-hash (bcrypt). Nomor rekening bank di-encrypt (Laravel Crypt). Data sensitif tidak di-log dalam plain text.                                                                                             |
| NF-BE-8  | Session & Timeout      | Session timeout: 2 jam inaktif. Remember me: 30 hari. Concurrent session: diperbolehkan (1 user bisa login dari beberapa device).                                                                                   |
| NF-BE-9  | File Size Limit        | Upload file dibatasi sesuai spec (Section 7.b.7). Server menolak file yang melebihi batas dengan pesan error jelas.                                                                                                 |
| NF-BE-10 | Logging                | Aplikasi menggunakan Laravel logging (daily rotation) untuk error tracking. Level: error dan critical dikirim ke notification channel (opsional).                                                                   |

---

## 12. Assumptions & Open Questions

### Assumptions

| #   | Asumsi                                                                                                                                      | Implikasi                                                            |
| --- | ------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------- |
| A1  | Satu KOL = satu akun user. Tidak ada KOL yang dikelola oleh manajer/pihak ketiga melalui platform.                                          | Relasi users ↔ kol_profiles adalah 1:1                               |
| A2  | Jumlah followers dan engagement rate diinput manual (self-reported oleh KOL, diverifikasi visual oleh Admin).                             | Tidak ada integrasi API sosmed; data bisa tidak 100% akurat          |
| A3  | Pembayaran komisi dilakukan di luar platform (transfer bank manual). Platform hanya mencatat.                                               | Tidak ada integrasi payment gateway                                  |
| A4  | Tier KOL ditentukan berdasarkan jumlah followers di platform utama mereka.                                                                  | Admin menentukan tier saat onboarding; bisa di-update manual         |
| A5  | Satu endorsement = satu tipe konten. Jika campaign memerlukan beberapa tipe konten dari KOL yang sama, dibuat sebagai endorsement terpisah. | Memudahkan tracking per tipe konten dan perhitungan komisi           |
| A6  | Platform di-host di server/cloud Indonesia untuk compliance data dan latency.                                                               | Deployment menggunakan cloud provider yang memiliki region Indonesia |
| A7  | Email menggunakan SMTP provider standar (Mailtrap untuk dev, Mailgun/SES untuk produksi).                                                   | Konfigurasi di `.env`, tidak perlu development khusus                |
| A8  | Bahasa platform hanya Bahasa Indonesia (single language, tidak multi-language).                                                             | Tidak perlu sistem i18n/localization                                 |

### Open Questions

| #   | Pertanyaan                                                                                                         | Keputusan                                                                               | Status              |
| --- | ------------------------------------------------------------------------------------------------------------------ | --------------------------------------------------------------------------------------- | ------------------- |
| OQ1 | Apakah skema komisi berjenjang per tier sudah sesuai? Apakah ada tier/rate khusus yang berbeda?                    | Belum ditentukan — menggunakan skema default industri (Nano 60%, Micro 65%, Macro 70%, Mega 75%) | ⏳ Menunggu konfirmasi |
| OQ2 | Apakah perlu fitur "KOL mengajukan pencairan sendiri" atau hanya Admin yang bisa mengajukan?                       | **Ya**, KOL bisa mengajukan pencairan sendiri                                            | ✅ Resolved          |
| OQ3 | Berapa lama data pendaftaran yang ditolak disimpan di sistem?                                                      | **Tidak disimpan** — langsung hard delete setelah reject, hanya audit log yang dipertahankan | ✅ Resolved          |
| OQ4 | Apakah ada kebutuhan export laporan periodik otomatis (mis. laporan bulanan komisi ke email)?                      | **Ya**, perlu export laporan periodik (ditambahkan di Section 7.b.12)                    | ✅ Resolved          |
| OQ5 | Apakah rate card KOL bersifat indikatif (negotiable per deal) atau fixed?                                          | **Indikatif** — fee endorsement bisa berbeda dari rate card                               | ✅ Resolved          |
| OQ6 | Apakah perlu fitur "Campaign Template" untuk mempercepat pembuatan campaign serupa?                                | **Tidak perlu** — dihapus dari Future Considerations                                      | ✅ Resolved          |
| OQ7 | Siapa yang akan menjadi Approver awal? Apakah ada lebih dari 1 Approver?                                           | **Hanya ada role Admin dan KOL**. Tidak ada role Reviewer/Approver. Alur langsung oleh Admin | ✅ Resolved          |
| OQ8 | Apakah notifikasi email juga diperlukan selain notifikasi in-app? (mis. email saat ada endorsement baru untuk KOL) | **Tidak perlu** — cukup in-app notification. Email hanya untuk esensial (konfirmasi, kredensial, reset password) | ✅ Resolved          |

---

## 13. Future Considerations

Fitur-fitur berikut **di luar scope** versi 1.0, namun direkomendasikan untuk pengembangan selanjutnya:

| #    | Fitur                                  | Prioritas | Keterangan                                                                                             |
| ---- | -------------------------------------- | --------- | ------------------------------------------------------------------------------------------------------ |
| FC1  | Integrasi Payment Gateway              | Tinggi    | Automasi pencairan komisi via Midtrans/Xendit → mengurangi proses manual                               |
| FC2  | Integrasi API Sosial Media             | Tinggi    | Auto-fetch followers, engagement rate, statistik dari Instagram/TikTok/YouTube API → data lebih akurat |
| FC3  | Aplikasi Mobile Native (atau PWA)      | Menengah  | Khususnya untuk KOL yang lebih sering mengakses via mobile                                             |
| FC4  | Dashboard Analitik Lanjutan            | Menengah  | Tren performa KOL, ROI per campaign, perbandingan engagement rate per niche, revenue forecast          |
| FC5  | Sistem Kontrak Digital / E-Signature   | Menengah  | Generate kontrak otomatis → tanda tangan digital → arsip di platform                                   |
| FC6  | Marketplace / Brand Portal             | Menengah  | Brand bisa login, browse KOL, dan request campaign langsung — self-service                             |
| FC7  | Multi-Tenant (SaaS)                    | Rendah    | Membuka platform untuk digunakan agensi lain dengan branding sendiri                                   |
| FC8  | Sistem Chat Internal                   | Rendah    | Chat antara Admin ↔ KOL di dalam platform (mengurangi ketergantungan pada WhatsApp)                    |
| FC9  | KOL Scoring / Rating System            | Menengah  | Scoring otomatis berdasarkan performa historis (delivery tepat waktu, kualitas konten, engagement)     |
| FC10 | API untuk Integrasi Eksternal          | Rendah    | REST API untuk integrasi dengan tools lain (CRM, accounting, dll)                                      |
| FC11 | KOL Self-Service Registration Tracking | Rendah    | Calon KOL bisa cek status pendaftaran via nomor registrasi tanpa login                                 |

---

## Appendix: Glosarium

| Istilah             | Definisi                                                                                                 |
| ------------------- | -------------------------------------------------------------------------------------------------------- |
| **KOL**             | Key Opinion Leader — influencer/content creator yang memiliki pengaruh di media sosial                   |
| **Tier**            | Klasifikasi KOL berdasarkan jumlah followers: Nano (<10K), Micro (10K-100K), Macro (100K-1M), Mega (>1M) |
| **Endorsement**     | Satu unit pekerjaan KOL dalam campaign: membuat konten tertentu untuk brand tertentu                     |
| **Campaign**        | Kegiatan promosi yang dijalankan oleh brand, bisa melibatkan beberapa KOL dan beberapa endorsement       |
| **Rate Card**       | Tarif yang ditetapkan KOL untuk setiap tipe konten di setiap platform                                    |
| **Engagement Rate** | Persentase interaksi (like, comment, share) dibandingkan jumlah followers                                |
| **Fee**             | Nominal pembayaran dari brand ke agensi untuk satu endorsement                                           |
| **Komisi**          | Bagian dari fee yang dibayarkan agensi ke KOL                                                            |
| **Brief**           | Dokumen/instruksi dari brand tentang apa yang harus dibuat KOL dalam endorsement                         |
| **Content Proof**   | Bukti bahwa KOL telah memposting konten sesuai brief (screenshot, link posting)                          |

---

_Dokumen ini adalah versi 1.0 dan bersifat living document. Update akan dilakukan seiring klarifikasi open questions dan feedback dari stakeholder._
