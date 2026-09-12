"""
config.py
Konfigurasi parameter Threads FYP Caption Scraper & Summarizer (Groq AI).
"""

from pathlib import Path
import os

# Membaca file .env jika tersedia (untuk keamanan API Key agar tidak ter-push ke Git)
env_path = Path(".env")
if env_path.exists():
    with open(env_path, "r", encoding="utf-8") as f:
        for line in f:
            line = line.strip()
            if line and not line.startswith("#") and "=" in line:
                k, v = line.split("=", 1)
                os.environ[k.strip()] = v.strip().strip("'\"")

# --- Scraping Target & Limits ---
TARGET_POSTS = 100            # Jumlah target postingan unik yang dikumpulkan
MAX_SCROLLS = 100             # Batas maksimal scroll sebelum berhenti otomatis
SCROLL_PIXELS = 1500          # Jarak scroll ke bawah per putaran (dalam pixel)
SCROLL_DELAY = 2.0            # Waktu jeda setelah tiap scroll (detik) untuk render/lazy-load
EMPTY_SCROLL_LIMIT = 5        # Berhenti jika N scroll berturut-turut tidak ada postingan baru

# --- Filtering Options ---
ONLY_COUNT_WITH_CAPTION = True  # Hanya post yang memiliki caption yang dihitung ke target 100
MIN_CAPTION_LENGTH = 1          # Panjang karakter minimal caption

# --- Browser & Session ---
HEADLESS = False              # Wajib False agar user dapat login manual & melihat feed
BROWSER_PROFILE_DIR = Path("data/browser_profile")  # Lokasi persistent context

# --- Output Paths ---
DATA_DIR = Path("data")
OUTPUT_CSV = Path("data/threads_fyp_100.csv")
OUTPUT_JSON = Path("data/threads_fyp_100.json")

# --- Summarizer Configuration (Groq AI) ---
OUTPUT_SUMMARY_MD = Path("data/threads_fyp_summary.md")
OUTPUT_SUMMARY_JSON = Path("data/threads_fyp_summary.json")

# Groq API Settings (dibaca dari environment variable atau .env)
GROQ_API_KEY = os.getenv("GROQ_API_KEY", "")
GROQ_MODEL = os.getenv("GROQ_MODEL", "openai/gpt-oss-120b")
GROQ_API_URL = "https://api.groq.com/openai/v1/chat/completions"

# URL Dasar Threads
THREADS_URL = "https://www.threads.net"
