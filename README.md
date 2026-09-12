# Threads FYP Caption Scraper (Proof of Concept)

Script otomasi berbasis Python dan **Playwright** untuk mengumpulkan dataset caption postingan dari feed *For You* (FYP) Threads secara aman, bertahap, dan terstruktur.

---

## 🚀 Fitur Utama

- **Persistent Browser Context**: Menyimpan sesi login di folder lokal (`data/browser_profile/`) sehingga tidak perlu login ulang setiap kali script dijalankan.
- **Login Manual yang Aman**: Pengguna melakukan login mandiri di browser; tidak ada kredensial atau password yang disimpan di kode.
- **Multi-layer Deduplication**: Mencegah duplikasi postingan menggunakan hierarki identifier: `post_id` → `url` → `username + caption` → `hash(caption)`.
- **Lightweight Caption Cleaning**: Membersihkan spasi dan baris baru berlebih tanpa merusak emoji, hashtag (`#`), dan mention (`@`).
- **Resilient Extraction**: Ekstraksi elemen berbasis semantik HTML (`article`, link profil `/@username`, tag `<time>`) yang tahan terhadap perubahan CSS class dinamis.
- **Export Ganda & Validasi**: Menghasilkan file `threads_fyp_100.csv` (dengan format UTF-8 BOM yang ramah Microsoft Excel) dan `threads_fyp_100.json`, disertai ringkasan statistik dataset.

---

## 📁 Struktur Proyek

```text
scrap-threads/
│
├── config.py             # Konfigurasi konstanta & limit scraping
├── threads_scraper.py    # Logika utama (browser, parser, scroll, dedup, export)
├── requirements.txt      # Dependency package (playwright)
├── data/                 # Folder output & session lokal
│   ├── threads_fyp_100.csv
│   ├── threads_fyp_100.json
│   └── browser_profile/  # Session cookies browser (di-gitignore)
├── prd.md                # Dokumen spesifikasi teknis
└── README.md             # Petunjuk instalasi & penggunaan
```

---

## 🛠️ Panduan Instalasi

### 1. Prasyarat
- **Python 3.10** atau lebih baru
- Koneksi internet stabil

### 2. Install Dependencies
Buka terminal / PowerShell di folder proyek ini (`d:\Intern\scrap-threads`), lalu jalankan:

```bash
pip install -r requirements.txt
playwright install chromium
```

---

## 💻 Cara Penggunaan (Langkah Demi Langkah)

1. **Jalankan Script**:
   ```bash
   python threads_scraper.py
   ```

2. **Jendela Browser Terbuka**:
   - Browser Chromium akan terbuka secara otomatis menuju `https://www.threads.net`.
   - Jika belum login, silakan **login akun Threads Anda secara manual**.
   - Pastikan feed yang aktif adalah tab **For You** (Untuk Anda).

3. **Mulai Scraping**:
   - Kembali ke terminal.
   - Tekan tombol **[ENTER]**.

4. **Proses Otomatis Berjalan**:
   - Script akan mulai membaca postingan yang terlihat di layar.
   - Script melakukan scroll bertahap ke bawah dan mengekstrak postingan baru secara otomatis.
   - Progress akan ditampilkan secara *real-time* di terminal:
     ```text
     [+] #001 | @user_a         | Caption pertama...
     [+] #002 | @user_b         | Caption kedua...
     [INFO] Scroll #01 | Post Baru: 02 | Terkumpul: 2/100
     ```

5. **Selesai**:
   - Script berhenti otomatis saat target 100 post unik tercapai (atau batas scroll tercapai).
   - Hasil tersimpan di:
     - `data/threads_fyp_100.csv`
     - `data/threads_fyp_100.json`
   - Ringkasan statistik dataset akan dicetak di terminal.

---

## ⚙️ Kustomisasi (`config.py`)

Anda dapat mengubah parameter scraping di dalam file `config.py` sesuai kebutuhan:

| Variabel | Default | Penjelasan |
|---|---|---|
| `TARGET_POSTS` | `100` | Jumlah postingan unik yang ingin dikumpulkan |
| `MAX_SCROLLS` | `100` | Batas maksimal scroll per sesi |
| `SCROLL_PIXELS` | `1500` | Jarak scroll per putaran (pixel) |
| `SCROLL_DELAY` | `2.0` | Jeda waktu setelah scroll (detik) untuk render feed |
| `EMPTY_SCROLL_LIMIT` | `5` | Batas toleransi jika feed tidak memuat konten baru berturut-turut |
| `ONLY_COUNT_WITH_CAPTION` | `True` | Hanya menghitung post yang memiliki caption ke target |

---

## 🔒 Catatan Keamanan & Privasi

- **Peringatan "Stop! / Self-XSS" di Console Browser**: Merupakan fitur keamanan bawaan dari Meta untuk memperingatkan pengguna agar tidak menempelkan script sembarangan. Script kita tidak menginjeksi kode berbahaya ke console browser.
- **Folder `data/browser_profile/`**: Berisi cookies dan token sesi login Anda. Folder ini sudah otomatis diabaikan oleh `.gitignore` agar tidak terunggah ke repositori publik seperti GitHub.
