# Threads FYP Caption Scraper & Groq AI Summarizer (Proof of Concept)

Proyek otomasi Python terintegrasi untuk:
1. **Scraping**: Mengumpulkan 100 caption postingan dari feed *For You* (FYP) Threads secara aman & bertahap.
2. **Summarizing**: Menganalisis statistik, kata kunci tren, hashtag dominan, serta menyusun rangkuman naratif & kesimpulan eksekutif menggunakan **Groq AI (Llama 3.3 70B)** atau engine analitik bawaan.

---

## 🚀 Fitur Utama

### 1. Threads Scraper (`threads_scraper.py`)
- **Persistent Browser Context**: Menyimpan sesi login di folder lokal (`data/browser_profile/`) sehingga tidak perlu login ulang tiap kali script dijalankan.
- **Login Manual yang Aman**: Pengguna login mandiri di browser tanpa menyimpan kredensial di kode.
- **Multi-layer Deduplication**: Mencegah duplikasi postingan (`post_id` → `url` → `username + caption` → `hash(caption)`).
- **Lightweight Caption Cleaning**: Membersihkan spasi, relative timestamp (`21h`), tombol translate, dan baris baru berlebih tanpa merusak emoji, hashtag (`#`), dan mention (`@`).
- **Export Dataset**: Menghasilkan file `threads_fyp_100.csv` (UTF-8 BOM untuk Excel) dan `threads_fyp_100.json`.

### 2. Dataset Summarizer & Analyzer (`summarizer.py`)
- **Analitik & Frekuensi NLP**: Ekstraksi otomatis kata kunci topik, hashtag terpopuler, dan kreator paling aktif di feed.
- **Groq AI Executive Summary (Llama 3.3 70B)**: Kecepatan inferensi super cepat dari Groq untuk merangkum tema utama, nuansa sentimen publik, perdebatan/isu yang sedang viral, dan kesimpulan penting (*key takeaways*).
- **Dual Mode (Dengan/Tanpa API Key)**: Jika tidak memiliki API key, tetap menghasilkan statistik lengkap dan menyusun template prompt siap pakai (*ready-to-paste*) untuk AI web.
- **Laporan Markdown & JSON**: Hasil tersimpan di `data/threads_fyp_summary.md` dan `data/threads_fyp_summary.json`.

---

## 📁 Struktur Proyek

```text
scrap-threads/
│
├── config.py             # Konfigurasi konstanta, limit, path, dan Groq API settings
├── threads_scraper.py    # Logika scraping (browser, parser, scroll, export)
├── summarizer.py         # Logika perangkum (analisis statistik & Groq AI summary)
├── requirements.txt      # Dependency package (playwright)
├── data/                 # Folder output & session lokal
│   ├── threads_fyp_100.csv
│   ├── threads_fyp_100.json
│   ├── threads_fyp_summary.md
│   ├── threads_fyp_summary.json
│   └── browser_profile/  # Session cookies browser (di-gitignore)
├── prd.md                # Dokumen spesifikasi teknis
└── README.md             # Petunjuk instalasi & penggunaan
```

---

## 🛠️ Panduan Instalasi

### 1. Prasyarat
- **Python 3.10** atau lebih baru

### 2. Install Dependencies
Buka terminal / PowerShell di folder proyek ini (`d:\Intern\scrap-threads`), lalu jalankan:

```bash
pip install -r requirements.txt
playwright install chromium
```

---

## 💻 Alur Penggunaan Lengkap

### Langkah 1: Jalankan Scraper
```bash
python threads_scraper.py
```
1. Browser Chromium akan terbuka secara otomatis menuju `https://www.threads.net`.
2. Login akun Threads Anda secara manual dan pastikan berada di tab **For You**.
3. Kembali ke terminal, tekan **[ENTER]**.
4. Script akan scroll otomatis hingga 100 postingan unik terkumpul di `data/threads_fyp_100.csv`.

---

### Langkah 2: Jalankan Perangkum dengan Groq AI
Setelah dataset CSV/JSON terkumpul, jalankan:

```bash
python summarizer.py
```

* **Mode Groq AI Otomatis**:
  Masukkan Groq API Key Anda sebelum menjalankan script:
  ```powershell
  # PowerShell
  $env:GROQ_API_KEY="gsk_..."
  python summarizer.py
  ```
  *(Atau masukkan langsung ke variabel `GROQ_API_KEY = "gsk_..."` di dalam [config.py](file:///d:/Intern/scrap-threads/config.py))*.

* **Hasil Ringkasan**:
  Buka file `data/threads_fyp_summary.md` untuk membaca kesimpulan, tema diskusi, dan tren FYP Anda!

---

## ⚙️ Kustomisasi (`config.py`)

| Variabel | Default | Penjelasan |
|---|---|---|
| `TARGET_POSTS` | `100` | Jumlah postingan unik yang dikumpulkan |
| `SCROLL_PIXELS` | `1500` | Jarak scroll per putaran (pixel) |
| `SCROLL_DELAY` | `2.0` | Jeda waktu setelah scroll (detik) |
| `EMPTY_SCROLL_LIMIT` | `5` | Batas toleransi jika feed tidak memuat konten baru |
| `GROQ_API_KEY` | `""` | API Key Groq (didapat dari https://console.groq.com) |
| `GROQ_MODEL` | `llama-3.3-70b-versatile` | Model AI Groq berkecepatan tinggi |
