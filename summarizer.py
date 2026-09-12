"""
summarizer.py
Threads FYP Caption Summarizer & Insight Generator (Powered by Groq AI)
Format Output:
1. Daftar Topik Utama
2. Rangkuman Tiap Topik
3. Kesimpulan Utama
"""

import csv
import json
import os
import re
import sys
import urllib.request
import urllib.error
from collections import Counter
from datetime import datetime
from pathlib import Path
from typing import Any, Dict, List, Optional

# Set UTF-8 encoding untuk terminal Windows
if sys.stdout and hasattr(sys.stdout, "reconfigure"):
    try:
        sys.stdout.reconfigure(encoding="utf-8")
    except Exception:
        pass

import config


# ==============================================================================
# 1. Dataset Loading & Cleaning
# ==============================================================================
def clean_text_for_summary(text: Optional[str]) -> str:
    """Membersihkan caption dari sisa header waktu dan tombol translate."""
    if not text:
        return ""
    cleaned = text.replace("\xa0", " ").strip()
    cleaned = re.sub(r"^([a-zA-Z0-9_-]+\s*\n+)?\d+\s*(?:s|m|h|d|w|y|detik|menit|jam|hari|minggu)\s*\n+", "", cleaned, flags=re.IGNORECASE)
    cleaned = re.sub(r"[\s\xa0]*(?:Translate|Terjemahkan|See translation|Lihat terjemahan)[\s\xa0]*$", "", cleaned, flags=re.IGNORECASE)
    cleaned = re.sub(r"[\s\xa0]*\d+/\d+[\s\xa0]*$", "", cleaned)
    return cleaned.strip()


def load_dataset(csv_path: Path, json_path: Path) -> List[Dict[str, Any]]:
    """Membaca data dari CSV jika ada, atau fallback ke JSON."""
    posts = []

    if csv_path.exists():
        print(f"[INFO] Membaca dataset dari CSV: {csv_path.resolve()}")
        with open(csv_path, mode="r", encoding="utf-8-sig") as f:
            reader = csv.DictReader(f)
            for row in reader:
                row["caption"] = clean_text_for_summary(row.get("caption", ""))
                if row["caption"]:
                    posts.append(row)
        return posts

    if json_path.exists():
        print(f"[INFO] Membaca dataset dari JSON: {json_path.resolve()}")
        with open(json_path, mode="r", encoding="utf-8") as f:
            raw_posts = json.load(f)
            for p in raw_posts:
                p["caption"] = clean_text_for_summary(p.get("caption", ""))
                if p["caption"]:
                    posts.append(p)
        return posts

    return []


# ==============================================================================
# 2. AI Summarization via Groq API (Strict 3-Part Structure)
# ==============================================================================
def call_groq_api(prompt: str, api_key: str, model: str = config.GROQ_MODEL) -> Optional[str]:
    """Mengirim request ringkasan ke Groq Cloud API."""
    url = config.GROQ_API_URL
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        "Content-Type": "application/json",
        "Authorization": f"Bearer {api_key}"
    }
    payload = {
        "model": model,
        "messages": [
            {
                "role": "system",
                "content": (
                    "Anda adalah asisten perangkum teks yang sangat rapi, jelas, dan to-the-point dalam Bahasa Indonesia. "
                    "Format jawaban Anda HANYA terdiri dari 3 bagian:\n"
                    "1. Topik-Topik Utama yang Dibahas\n"
                    "2. Rangkuman Masing-Masing Topik\n"
                    "3. Kesimpulan Utama\n"
                    "Gunakan gaya bahasa profesional, informatif, dan mudah dipahami."
                )
            },
            {
                "role": "user",
                "content": prompt
            }
        ],
        "temperature": 0.3,
        "max_tokens": 2048,
    }

    try:
        req = urllib.request.Request(
            url,
            data=json.dumps(payload).encode("utf-8"),
            headers=headers,
            method="POST"
        )
        with urllib.request.urlopen(req, timeout=45) as resp:
            data = json.loads(resp.read().decode("utf-8"))
            choices = data.get("choices", [])
            if choices:
                return choices[0].get("message", {}).get("content", "").strip()
            return None
    except urllib.error.HTTPError as e:
        error_msg = e.read().decode("utf-8")
        print(f"[WARNING] Gagal memanggil Groq API (HTTP {e.code}): {error_msg}")
        return None
    except Exception as e:
        print(f"[WARNING] Error koneksi Groq API: {e}")
        return None


def build_llm_prompt(posts: List[Dict[str, Any]]) -> str:
    """Menyusun prompt analitik yang fokus pada 3 struktur permintaan user."""
    captions_text = []
    for idx, p in enumerate(posts[:100], 1):
        c = p.get("caption", "").strip()
        u = p.get("username", "anonim")
        if c:
            c_snippet = (c[:250] + "...") if len(c) > 250 else c
            c_clean = c_snippet.replace("\n", " ")
            captions_text.append(f"{idx}. [@{u}]: {c_clean}")

    captions_block = "\n".join(captions_text)

    prompt = f"""
Berikut adalah {len(posts)} postingan dari feed 'For You' Threads:
\"\"\"
{captions_block}
\"\"\"

Tolong buatkan rangkuman dalam Bahasa Indonesia dengan format persis seperti ini:

# 📑 Rangkuman Postingan Threads FYP

## 1. 📌 Topik-Topik Utama yang Dibahas
(Sebutkan daftar poin topik apa saja yang ditemukan dari 100 postingan di atas)

## 2. 📝 Rangkuman Masing-Masing Topik
(Untuk setiap topik yang disebutkan di nomor 1, jelaskan secara mendalam: apa yang dibahas oleh warganet, cerita/isu apa yang ramai, dan intisari dari topik tersebut)

## 3. 🎯 Kesimpulan Utama
(Tuliskan kesimpulan besar dari keseluruhan postingan ini: apa tren atau pola utama yang terjadi di feed tersebut)
"""
    return prompt.strip()


# ==============================================================================
# 3. Storage & Execution
# ==============================================================================
def save_summary_files(md_text: str) -> None:
    """Menyimpan hasil ke file Markdown dan JSON."""
    config.OUTPUT_SUMMARY_MD.parent.mkdir(parents=True, exist_ok=True)

    with open(config.OUTPUT_SUMMARY_MD, mode="w", encoding="utf-8") as f:
        f.write(md_text)
    print(f"[SUCCESS] Rangkuman tersimpan di: {config.OUTPUT_SUMMARY_MD.resolve()}")

    # Juga simpan ke data/rangkuman_threads.md
    alt_path = config.DATA_DIR / "rangkuman_threads.md"
    with open(alt_path, mode="w", encoding="utf-8") as f:
        f.write(md_text)


def main():
    print("=" * 65)
    print("             THREADS FYP DATASET SUMMARIZER")
    print("=" * 65)

    posts = load_dataset(config.OUTPUT_CSV, config.OUTPUT_JSON)
    if not posts:
        print(f"\n[ERROR] File dataset tidak ditemukan di {config.OUTPUT_CSV.resolve()}")
        return

    print(f"[INFO] Memuat {len(posts)} postingan...")

    api_key = config.GROQ_API_KEY or os.getenv("GROQ_API_KEY", "")
    ai_summary_text = None

    if api_key:
        print(f"[INFO] Menghubungkan ke Groq AI ({config.GROQ_MODEL}) untuk merangkum...")
        prompt = build_llm_prompt(posts)
        ai_summary_text = call_groq_api(prompt, api_key, config.GROQ_MODEL)

    if ai_summary_text:
        print("[SUCCESS] Rangkuman Groq AI berhasil dibuat!\n")
        final_summary = ai_summary_text
    else:
        print("[WARNING] Tidak dapat memanggil Groq AI. Menggunakan format rangkuman standar.")
        import sys
        # fallback default
        final_summary = "Gagal memanggil Groq AI. Periksa API key atau koneksi internet."

    save_summary_files(final_summary)

    print("\n" + "=" * 65)
    print("                  HASIL RANGKUMAN")
    print("=" * 65)
    print(final_summary)
    print("=" * 65)
    print(f"\n[INFO] File rangkuman tersimpan di: {config.OUTPUT_SUMMARY_MD.resolve()}\n")


if __name__ == "__main__":
    main()
