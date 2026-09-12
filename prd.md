# PRD / Technical Specification
## Threads FYP Caption Scraper — Proof of Concept

**Versi:** 1.0
**Tanggal:** 12 September 2026
**Tipe proyek:** Script Python single-purpose, bukan aplikasi
**Status:** Draft untuk eksperimen internal

---

## 1. Objective

Membangun sebuah script Python sederhana yang:

- Membuka Threads di browser (Playwright)
- Membaca postingan yang muncul di feed FYP / For You milik user yang sedang login
- Melakukan scroll bertahap sambil mengekstrak postingan baru
- Melakukan deduplication terhadap postingan yang berulang
- Berhenti setelah mendapatkan **100 postingan unik** (atau mencapai batas maksimum usaha)
- Menyimpan hasil ke **CSV** (dan opsional JSON untuk debugging)

Tujuan akhirnya adalah menghasilkan dataset caption yang nantinya akan diringkas (summarization) di tahap terpisah — **tahap itu tidak termasuk dalam scope dokumen ini**.

---

## 2. Scope

Yang termasuk dalam scope MVP:

- Satu script (atau beberapa file kecil) yang dijalankan manual dari terminal
- Browser automation dengan Playwright, `headless=False`
- Login manual oleh user (bukan otomatis)
- Scroll otomatis pada feed FYP yang sedang dibuka
- Ekstraksi field: username, caption, url, timestamp (jika ada), urutan, scraped_at
- Deduplication dengan strategi berlapis
- Cleaning ringan pada caption
- Output CSV + JSON
- Logging progress di terminal
- Validasi dataset di akhir proses

---

## 3. Non-Goals

Secara eksplisit **tidak** dikerjakan di tahap ini:

- Aplikasi web, dashboard, atau UI apa pun
- Database (SQLite/Postgres/dll)
- API backend atau service yang berjalan terus-menerus (daemon)
- Autentikasi otomatis / penyimpanan password
- Bypass CAPTCHA, rate limit, atau proteksi keamanan Threads apa pun
- Scraping massal / multi-akun / scheduling otomatis
- Summarization atau pemrosesan LLM (itu tahap berikutnya)
- Deployment ke server / cloud

---

## 4. User Flow

```
1. User menjalankan script dari terminal
2. Browser terbuka (headless=False) menuju threads.net
3. Jika belum login → user login manual (script menunggu)
4. User memastikan berada di feed For You / FYP
5. User menekan Enter di terminal untuk memberi sinyal "siap mulai"
6. Script mulai membaca postingan yang tampil di viewport
7. Script melakukan scroll bertahap
8. Setiap putaran: extract → dedupe → log progress
9. Berhenti ketika unique_posts >= 100 ATAU max_scrolls tercapai
10. Script menyimpan hasil ke CSV (+ JSON opsional)
11. Script menampilkan ringkasan validasi dataset
```

Catatan penting: langkah 5 (konfirmasi manual "siap mulai") sengaja ditambahkan di luar daftar awal Anda, karena Threads FYP bersifat personalized feed — script tidak bisa "menavigasi" ke FYP secara mandiri dengan andal tanpa asumsi tentang struktur navigasi yang bisa berubah. User yang memastikan posisi feed sudah benar adalah pendekatan paling stabil.

---

## 5. Technical Approach

| Komponen | Pilihan | Alasan |
|---|---|---|
| Bahasa | Python 3.10+ | Sesuai permintaan |
| Automation | Playwright (sync API) | Lebih stabil dibanding Selenium untuk SPA modern seperti Threads |
| Browser | Chromium, `headless=False` | User perlu login manual & memverifikasi visual |
| Session | Persistent context (`launch_persistent_context`) | Agar tidak perlu login ulang setiap run |
| Storage | CSV (pandas atau csv builtin) + JSON | Sesuai kebutuhan analisis lanjutan |
| Konfigurasi | File `config.py` atau konstanta di atas script | Mudah diubah tanpa membaca seluruh kode |

**Analisis kelayakan teknis (jujur):**

Playwright untuk membaca DOM feed yang sudah ter-render di browser adalah pendekatan yang realistis dan umum digunakan untuk eksperimen skala kecil seperti ini. Namun ada beberapa keterbatasan nyata yang perlu Anda terima sejak awal:

1. **Threads adalah SPA (React-based) dengan class name yang di-generate/obfuscated** (mirip Instagram/Facebook). Class CSS seperti `x1n2onr6` dsb. **tidak stabil** dan bisa berubah kapan saja tanpa pemberitahuan. Script tidak boleh bergantung pada class-class semacam ini sebagai satu-satunya identifier.
2. **FYP bersifat personalized dan tidak deterministik.** Urutan dan isi feed akan berbeda antar sesi, antar akun, bahkan antar refresh. Ini normal dan bukan bug — jadi jangan mengharapkan hasil yang "reproducible" 1:1.
3. **Tidak ada API resmi publik untuk membaca feed For You orang lain.** Meta menyediakan *Threads API* resmi, tetapi API tersebut ditujukan untuk mengelola/posting konten milik akun sendiri (publishing, insights, reply), **bukan** untuk membaca feed FYP personalisasi seperti yang dilihat user di aplikasi. Jadi untuk use case ini, browser automation adalah satu-satunya jalur yang masuk akal — bukan karena API "sengaja dihindari", tapi karena API resmi memang tidak menyediakan endpoint ini.
4. **Scraping FYP kemungkinan besar berada di area abu-abu Terms of Service Meta.** Ini bukan halangan teknis, tapi perlu Anda sadari sebagai risiko (akun bisa mendapat warning/rate-limit/pembatasan jika pola aktivitas dianggap tidak wajar). Untuk eksperimen kecil (100 post, sekali jalan, manual login, tanpa automasi agresif), risikonya relatif rendah, tapi tetap ada.
5. **Selector spesifik tidak bisa saya tebak/karang di dokumen ini** tanpa membuka DevTools langsung pada Threads yang sedang berjalan. Setiap bagian yang membutuhkan selector konkret saya tandai sebagai *"Implementation detail to validate during development"* sesuai instruksi Anda.

Kesimpulan: pendekatan ini **realistis untuk proof-of-concept**, dengan catatan bahwa selector harus divalidasi manual dan hasil harus dianggap "best effort", bukan garansi 100 post sempurna setiap kali dijalankan.

---

## 6. Scraping Strategy

Karena class CSS Threads tidak stabil, gunakan strategi berlapis dari yang paling stabil ke paling rapuh:

### 6.1 Menemukan container postingan
Prioritas pendekatan (dari paling disarankan):
1. **Role/semantic HTML** — cari elemen dengan role ARIA seperti `article` (elemen HTML `<article>` cenderung dipertahankan lebih lama karena berkaitan dengan accessibility, bukan hanya styling).
2. **data-* attributes** jika ditemukan (mis. `data-pressable-container`, `data-testid`, dll — beberapa produk Meta memakai atribut semacam ini).
3. **Struktur berbasis teks/posisi** sebagai fallback: elemen yang berulang dengan pola anak (link ke profil + blok teks) di dalam scroll container utama.

→ *Implementation detail to validate during development*: buka Threads di DevTools, inspect satu post card, catat tag/role/atribut yang konsisten muncul di beberapa post berbeda.

### 6.2 Menemukan caption/text
- Cari elemen teks terbesar/utama di dalam container post (biasanya `<span>` atau `<div>` dengan `dir="auto"`, karena Meta banyak memakai atribut ini untuk teks multi-bahasa).
- Hindari mengambil elemen yang berisi angka like/reply count (biasanya terpisah, sering dalam elemen kecil di dekat tombol aksi).
- → *Implementation detail to validate during development*

### 6.3 Menemukan username
- Biasanya berupa `<a>` yang link ke `/@username`. Ini pola URL yang relatif stabil karena berkaitan dengan routing, bukan styling.
- Ambil username dari `href` link tersebut (regex `/@([\w.]+)`), bukan dari teks yang ditampilkan (agar tidak tertukar display name).

### 6.4 Menemukan URL postingan
- Cari `<a>` di dalam card yang mengarah ke pola `/@username/post/xxxxx`.
- Pola URL semacam ini jauh lebih stabil dibanding class CSS.

### 6.5 Menemukan post ID
- Ambil dari segmen terakhir URL post (`/post/{id}`) bila URL berhasil ditemukan.
- Jika URL tidak ditemukan, post_id dianggap tidak tersedia — **jangan membuat ID palsu**.

### 6.6 Mendeteksi postingan baru setelah scroll
- Sebelum scroll: catat jumlah/identifier post yang sudah ter-extract.
- Setelah scroll + wait: extract ulang container yang terlihat, bandingkan dengan `seen_posts`.
- Selisihnya adalah "postingan baru" pada putaran tersebut.

### 6.7 Menentukan batas
- Berhenti jika `unique_posts >= TARGET_POSTS`.
- Berhenti jika jumlah scroll mencapai `MAX_SCROLLS`.
- Berhenti lebih awal jika N kali scroll berturut-turut menghasilkan 0 postingan baru (kemungkinan sudah mentok/rate-limited/loading gagal).

**Prinsip umum:** semua selector CSS/XPath yang konkret di atas **harus divalidasi manual** oleh Anda di DevTools sebelum dipakai di script — dokumen ini sengaja tidak mengarang class name spesifik karena akan cepat basi dan menyesatkan.

---

## 7. Data Fields

| Field | Wajib? | Sumber | Catatan |
|---|---|---|---|
| `no` | Ya | Index urutan penyimpanan | Mulai dari 1 |
| `username` | **Wajib** | href profil (`/@username`) | Jika tidak ketemu, post di-skip dari "valid data" tapi tetap dicatat sebagai gagal-extract |
| `display_name` | Opsional | Teks di dekat username | Kosongkan jika tidak ada |
| `caption` | **Wajib** | Elemen teks utama | Lihat §8 & §9 untuk aturan lanjutan |
| `post_id` | Opsional | Segmen akhir URL post | Kosongkan jika URL tidak ditemukan |
| `url` | Opsional | href ke `/post/...` | Kosongkan jika tidak ditemukan |
| `timestamp` | Opsional | `<time datetime="...">` jika ada | Threads biasanya render elemen `<time>` untuk ini |
| `feed_order` | Ya | Urutan saat pertama kali terlihat script | Bukan urutan absolut Threads, hanya urutan pembacaan |
| `scraped_at` | Ya | `datetime.now()` saat post pertama disimpan | Format `YYYY-MM-DD HH:MM:SS` |

Jangan pernah mengisi field dengan data karangan jika informasi tidak ditemukan — biarkan kosong (`""` atau `None`).

---

## 8. Deduplication Strategy

Fungsi utama:

```python
def get_post_identifier(post: dict) -> str:
    """
    Mengembalikan identifier terbaik yang tersedia untuk sebuah post,
    dicoba berurutan dari yang paling reliable.
    """
    if post.get("post_id"):
        return f"id:{post['post_id']}"
    if post.get("url"):
        return f"url:{post['url']}"
    if post.get("username") and post.get("caption"):
        return f"combo:{post['username']}::{post['caption'][:100]}"
    if post.get("caption"):
        return f"hash:{hashlib.sha256(post['caption'].encode()).hexdigest()}"
    return None  # tidak bisa dideduplikasi secara reliable


def is_duplicate(post: dict, seen_posts: set) -> bool:
    ident = get_post_identifier(post)
    if ident is None:
        return False  # tidak bisa ditentukan; diperlakukan sebagai unique tapi berisiko
    return ident in seen_posts
```

**Kelemahan tiap metode (jujur):**

| Metode | Kelebihan | Kelemahan |
|---|---|---|
| `post_id` | Paling akurat | Hanya tersedia jika URL post berhasil di-extract |
| `url` | Sangat reliable | Sama seperti di atas; URL bisa punya query param berbeda untuk post sama |
| `username + caption` | Fallback cukup baik | Bisa keliru jika user memang posting caption identik dua kali (repost/typo) |
| `hash(caption)` | Berguna saat username tidak ketemu | Caption yang sangat pendek/umum ("😂😂😂") bisa dianggap sama padahal beda post; caption kosong tidak bisa di-hash secara berarti |

Karena kelemahan ini nyata, sebaiknya field `post_id`/`url` diusahakan didapat semaksimal mungkin — ini prioritas tertinggi di §6.

---

## 9. Caption Cleaning

Fungsi `clean_caption()` melakukan **cleaning ringan saja**:

- `strip()` whitespace di awal/akhir
- Normalize multiple line breaks berlebihan (`\n\n\n+` → `\n\n`)
- Hilangkan whitespace ganda di tengah teks (`"   "` → `" "`)
- **Pertahankan** emoji apa adanya
- **Pertahankan** hashtag (`#tag`)
- **Pertahankan** mention (`@user`) jika itu memang bagian dari caption asli
- Hilangkan teks UI yang kadang ikut ter-scrape secara tidak sengaja, misalnya label tombol ("Like", "Reply", "Translate") — **hanya jika** teks tersebut berada persis di string yang sama dengan caption (ini indikasi extraction salah container, bukan bagian dari caption)

**Jangan** melakukan: lowercase, hapus emoji, hapus tanda baca, hapus hashtag/mention, translate otomatis. Tujuannya menjaga data sedekat mungkin dengan aslinya untuk tahap summarization nanti.

---

## 10. Scrolling Strategy

```text
Extract visible posts (initial state)
↓
Deduplicate → simpan yang unik
↓
Log progress
↓
Jika unique_posts >= TARGET_POSTS → STOP
↓
Scroll sejauh SCROLL_PIXELS
↓
Wait SCROLL_DELAY detik (beri waktu render/lazy-load)
↓
Extract visible posts lagi
↓
Deduplicate
↓
Jika 0 post baru → increment empty_scroll_counter
   Jika empty_scroll_counter >= EMPTY_SCROLL_LIMIT → STOP (kemungkinan feed mentok)
Jika ada post baru → reset empty_scroll_counter
↓
Jika scroll_count >= MAX_SCROLLS → STOP
↓
Repeat
```

Scroll harus bertahap dan tidak agresif (jangan langsung scroll ke bawah dalam jumlah besar) — ini juga mengurangi risiko dianggap aktivitas bot yang mencurigakan oleh Threads.

---

## 11. Error Handling

| Skenario | Penanganan |
|---|---|
| Browser gagal dibuka | Log error, exit dengan pesan jelas |
| Threads gagal dimuat (timeout) | Retry 1x, jika gagal lagi → exit dengan instruksi manual |
| User belum login | Script menunggu (polling / menunggu Enter dari user), tidak mencoba login otomatis |
| Session expired di tengah scraping | Deteksi (mis. redirect ke halaman login), stop scraping, simpan data yang sudah terkumpul sejauh ini, beri warning |
| Feed belum selesai loading | `wait_for_selector` dengan timeout wajar, lanjut walau sebagian gagal |
| Satu postingan gagal diekstrak | `try/except` per-post, log `[WARNING] Failed to extract post`, **lanjut ke post berikutnya** — tidak boleh menghentikan seluruh proses |
| Scroll tidak menghasilkan post baru berturut-turut | Stop lebih awal (lihat §10) |
| Selector tidak ditemukan | Log warning, skip elemen tersebut, jangan crash |
| Timeout umum | Bungkus operasi Playwright dengan timeout eksplisit + try/except |
| File gagal disimpan | Backup ke path alternatif / print isi data mentah ke terminal sebagai fallback terakhir agar data tidak hilang total |

Prinsip inti: **kegagalan lokal (1 post, 1 scroll) tidak boleh menggagalkan keseluruhan run.**

---

## 12. Security Considerations

- **Tidak ada password yang ditulis di script atau file konfigurasi.** Login sepenuhnya manual oleh user di jendela browser.
- **Tidak ada upaya bypass CAPTCHA, autentikasi, atau rate limiting** dalam bentuk apa pun.
- **Persistent browser profile** (`launch_persistent_context`) digunakan agar user tidak perlu login berulang kali setiap run.

  Risiko yang perlu disadari:
  - Folder profile ini berisi **cookies/session token asli Threads** Anda — perlakukan seperti kredensial: jangan commit ke Git, jangan upload ke tempat publik, jangan share.
  - Tambahkan folder profile (mis. `data/browser_profile/`) ke `.gitignore`.
  - Jika komputer digunakan bersama orang lain, session ini bisa dipakai untuk mengakses akun Anda — simpan di lokasi yang aman.
- Scraping dilakukan dengan kecepatan wajar (delay antar scroll) untuk tidak membebani/memicu deteksi sistem Threads secara tidak perlu.
- Dataset yang dihasilkan berisi konten dan username orang lain — perlakukan sebagai data yang perlu dijaga privasinya, jangan dipublikasikan mentah-mentah.

---

## 13. Project Structure

Mengikuti preferensi Anda: sederhana, mudah didebug. Untuk eksperimen skala ini, direkomendasikan **single-file** agar mudah dibaca dan diubah cepat, dengan opsi split jika sudah terasa panjang:

```text
threads_scraper/
│
├── threads_scraper.py     # semua logic utama (boleh 1 file untuk MVP)
├── config.py               # konstanta yang mudah diubah
├── requirements.txt
├── data/
│   ├── threads_fyp_100.csv
│   ├── threads_fyp_100.json
│   └── browser_profile/    # persistent session (di-gitignore)
└── README.md
```

`requirements.txt`:
```text
playwright
```

(pandas opsional jika ingin mempermudah penulisan CSV; builtin `csv` module juga cukup)

---

## 14. Function Design

| Fungsi | Tanggung jawab |
|---|---|
| `launch_browser()` | Membuka Chromium dengan persistent context, `headless=False`, mengarah ke threads.net |
| `wait_for_threads()` | Menunggu halaman Threads selesai load; menunggu konfirmasi manual dari user (login + posisi di FYP) sebelum lanjut |
| `extract_visible_posts(page)` | Mengambil semua elemen post yang saat ini ada di DOM/viewport, mengembalikan list raw element/data |
| `extract_post_data(post_element)` | Mengubah satu elemen post menjadi dict terstruktur (username, caption, url, dll), dibungkus try/except |
| `clean_caption(text)` | Menerapkan aturan cleaning ringan sesuai §9 |
| `get_post_identifier(post)` | Menentukan identifier terbaik untuk keperluan dedup, sesuai §8 |
| `is_duplicate(post, seen_posts)` | Mengecek apakah post sudah pernah terlihat |
| `scroll_feed(page, pixels)` | Melakukan satu kali scroll sejauh `pixels`, lalu wait sesuai `SCROLL_DELAY` |
| `save_to_csv(posts, path)` | Menulis list dict ke file CSV sesuai format §16 |
| `save_to_json(posts, path)` | Menulis raw data ke JSON untuk debugging |
| `validate_dataset(posts)` | Menghitung total, unique, with-caption, without-caption; print ringkasan (§18) |
| `main()` | Orkestrasi keseluruhan alur sesuai §4 |

---

## 15. Configuration

```python
# config.py

TARGET_POSTS = 100
MAX_SCROLLS = 100
SCROLL_PIXELS = 1500
SCROLL_DELAY = 2          # detik, waktu tunggu setelah tiap scroll
EMPTY_SCROLL_LIMIT = 5    # stop jika N scroll berturut-turut tanpa post baru

HEADLESS = False
BROWSER_PROFILE_DIR = "data/browser_profile"

OUTPUT_CSV = "data/threads_fyp_100.csv"
OUTPUT_JSON = "data/threads_fyp_100.json"

MIN_CAPTION_LENGTH = 1    # lihat §9/§18 soal caption sangat pendek/kosong
```

Semua nilai ini dirancang agar mudah diubah tanpa menyentuh logic utama.

---

## 16. Output Format

### CSV (`threads_fyp_100.csv`)
```csv
no,username,caption,url,scraped_at
1,user_a,"Ini adalah caption...",https://www.threads.net/@user_a/post/xxxx,2026-09-12 11:00:00
2,user_b,"Menurut saya...",https://www.threads.net/@user_b/post/yyyy,2026-09-12 11:00:03
```

### JSON (`threads_fyp_100.json`, opsional, untuk debugging)
```json
[
  {
    "no": 1,
    "username": "user_a",
    "display_name": "User A",
    "caption": "Ini adalah caption...",
    "post_id": "xxxx",
    "url": "https://www.threads.net/@user_a/post/xxxx",
    "timestamp": "2026-09-12T10:59:50",
    "feed_order": 3,
    "scraped_at": "2026-09-12 11:00:00"
  }
]
```

---

## 17. Testing Plan

Manual, tidak perlu automated test framework untuk eksperimen ini.

| # | Test | Cara validasi |
|---|---|---|
| 1 | Browser dapat membuka Threads | Jendela Chromium terbuka, URL threads.net termuat |
| 2 | Login manual berhasil | User bisa login, session tersimpan di persistent profile, run kedua tidak minta login ulang |
| 3 | Script dapat membaca satu postingan | Print 1 dict hasil `extract_post_data()` ke terminal, cek field terisi wajar |
| 4 | Scroll menghasilkan post baru | Log `[INFO] New posts: N` menunjukkan N > 0 pada beberapa putaran awal |
| 5 | Duplicate berhasil dihapus | Sengaja scroll naik-turun; pastikan `unique_posts` tidak bertambah untuk post yang sama |
| 6 | Script bisa kumpulkan 100 post | Jalankan full run, cek log akhir `Total unique posts: 100/100` (atau kurang jika feed habis) |
| 7 | CSV valid | Buka dengan `pandas.read_csv()` atau Excel, pastikan tidak ada baris korup/kolom bergeser |

---

## 18. Acceptance Criteria

MVP dianggap **berhasil** jika:

- [ ] Script berjalan dari terminal tanpa error fatal dari awal sampai akhir
- [ ] User bisa login manual dan script menunggu dengan benar
- [ ] Script berhasil mengumpulkan postingan unik hingga `TARGET_POSTS` **atau** berhenti dengan wajar di angka lebih rendah disertai pesan yang jelas (bukan crash)
- [ ] Tidak ada data palsu/karangan pada field mana pun
- [ ] File `threads_fyp_100.csv` valid dan bisa dibuka
- [ ] Field wajib (`username`, `caption`) terisi untuk mayoritas baris
- [ ] Satu post gagal-extract tidak menghentikan keseluruhan proses
- [ ] Tidak ada password/credential tersimpan di kode maupun file config
- [ ] Ringkasan validasi dataset tercetak di akhir run, contoh:
  ```text
  Target: 100
  Collected: 100
  Unique: 100
  With caption: 96
  Without caption: 4
  ```

---

## 19. Known Limitations

Disampaikan secara jujur agar tidak ada ekspektasi yang salah:

1. **Selector DOM Threads bisa berubah kapan saja** — script yang berjalan baik hari ini bisa perlu penyesuaian selector di kemudian hari. Ini bukan kegagalan desain, tapi karakteristik scraping terhadap SPA yang tidak menyediakan API publik untuk kasus ini.
2. **FYP tidak deterministik** — dua kali run bisa menghasilkan set postingan yang sangat berbeda, dan itu normal.
3. **Tidak ada garansi mencapai 100 post** — jika feed lambat, user idle, atau Threads membatasi loading, jumlah unik bisa lebih kecil. Script harus melaporkan angka sebenarnya, bukan memaksakan.
4. **Rekomendasi soal post tanpa caption (§9 di brief Anda):** untuk tujuan akhir *summarization caption*, disarankan **Opsi B — hanya postingan dengan caption yang dihitung menuju target 100**, karena post tanpa teks (gambar/video murni) tidak memberi nilai untuk tahap summarization dan hanya akan jadi baris kosong yang harus difilter lagi nanti. Post tanpa caption tetap boleh dicatat di JSON mentah (untuk transparansi), tapi tidak dihitung ke counter `unique_posts` menuju 100.
5. **Risiko ToS** — scraping FYP kemungkinan berada di luar penggunaan yang dimaksudkan Meta. Untuk eksperimen skala kecil, sekali jalan, dengan login manual milik akun sendiri, risikonya rendah, tapi tidak nol (potensi rate-limit/soft warning pada akun).
6. **Persistent browser profile menyimpan session asli** — perlu dijaga seperti kredensial (lihat §12).
7. **Caption panjang/ber-newline kompleks** bisa membuat parsing CSV sedikit rumit (koma, newline di dalam field) — pastikan penulisan CSV memakai proper quoting (`csv` module Python sudah menangani ini secara default, tapi tetap perlu divalidasi dengan Test #7).

---

## 20. Implementation Steps

### Step-by-Step Development Plan

```text
Step 1  — Setup Playwright
Step 2  — Open Threads
Step 3  — Manual Login
Step 4  — Detect Feed
Step 5  — Extract One Post
Step 6  — Extract Multiple Posts
Step 7  — Deduplication
Step 8  — Scrolling
Step 9  — Collect 100 Posts
Step 10 — Save CSV
Step 11 — Validate Dataset
```

**Step 1 — Setup Playwright**
Install dependency dan browser binary:
```bash
pip install playwright
playwright install chromium
```
*Validasi:* jalankan script minimal yang membuka `about:blank`, pastikan window Chromium muncul.

**Step 2 — Open Threads**
Gunakan `launch_persistent_context()` mengarah ke `data/browser_profile`, navigasi ke `https://www.threads.net`.
*Validasi:* halaman Threads termuat (bisa berupa halaman login atau feed, tergantung status session).

**Step 3 — Manual Login**
Script mencetak instruksi ke terminal ("Silakan login manual, lalu tekan Enter di sini"), lalu `input()` menunggu user.
*Validasi:* setelah Enter ditekan, cek apakah page URL/DOM menunjukkan user sudah di halaman feed (bukan halaman login) — jika masih di login, minta ulang.

**Step 4 — Detect Feed**
Minta user memastikan berada di tab **For You**, lalu tekan Enter lagi untuk konfirmasi "siap mulai scraping".
*Validasi:* tunggu 1 elemen post pertama muncul di DOM (`wait_for_selector` dengan selector yang sudah divalidasi manual — lihat §6.1) sebelum lanjut.

**Step 5 — Extract One Post**
Implementasikan `extract_post_data()` untuk **satu** elemen post saja, print hasilnya.
*Validasi:* bandingkan manual dengan apa yang terlihat di browser — apakah username/caption sesuai dengan post yang dimaksud.

**Step 6 — Extract Multiple Posts**
Implementasikan `extract_visible_posts()` untuk mengambil semua post yang saat ini ada di DOM, loop memanggil `extract_post_data()` per elemen dengan try/except.
*Validasi:* jumlah post yang berhasil di-extract masuk akal dibanding jumlah yang terlihat di layar.

**Step 7 — Deduplication**
Implementasikan `get_post_identifier()` dan `is_duplicate()`, integrasikan ke alur extract.
*Validasi:* jalankan `extract_visible_posts()` dua kali tanpa scroll — pastikan `unique_posts` tidak bertambah pada panggilan kedua.

**Step 8 — Scrolling**
Implementasikan `scroll_feed()`, integrasikan loop: extract → dedupe → scroll → wait → repeat.
*Validasi:* log `[INFO] Scroll N` dan `[INFO] New posts: X` menunjukkan angka yang berubah wajar antar putaran.

**Step 9 — Collect 100 Posts**
Tambahkan stop condition (`TARGET_POSTS`, `MAX_SCROLLS`, `EMPTY_SCROLL_LIMIT`) ke dalam loop utama.
*Validasi:* jalankan full run, amati log sampai berhenti — baik karena mencapai 100 maupun karena limit tercapai, pastikan berhenti dengan pesan yang jelas, bukan hang/infinite loop.

**Step 10 — Save CSV**
Implementasikan `save_to_csv()` (dan `save_to_json()` opsional).
*Validasi:* buka file hasil di Excel/`pandas.read_csv()`, cek jumlah baris = jumlah unique posts, cek tidak ada kolom bergeser akibat koma/newline di caption.

**Step 11 — Validate Dataset**
Implementasikan `validate_dataset()` untuk mencetak ringkasan (§18) di akhir `main()`.
*Validasi:* angka `Collected`, `Unique`, `With caption`, `Without caption` masuk akal dan konsisten dengan isi file CSV.

---

## Catatan Penutup

Beberapa selector CSS/XPath konkret **sengaja tidak dicantumkan** di dokumen ini karena:
- Struktur DOM Threads bisa berubah kapan saja
- Menulis selector tanpa memverifikasi langsung di browser berisiko menyesatkan proses development

Setiap titik yang membutuhkan selector nyata ditandai sebagai **"Implementation detail to validate during development"** — langkah pertama saat mulai coding sebaiknya adalah membuka Threads di DevTools dan mendokumentasikan struktur DOM aktual sebelum menulis `extract_post_data()`.

Dokumen ini fokus 100% pada scraping. Tahap summarization (CSV → cleaning → batching → LLM → summary) akan dibuat sebagai dokumen/PRD terpisah setelah scraper ini terbukti berhasil.
