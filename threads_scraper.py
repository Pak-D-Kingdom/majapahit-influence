"""
threads_scraper.py
Threads FYP Caption Scraper - Proof of Concept (PoC)
Menggunakan Playwright Sync API dengan persistent browser context.
"""

import csv
import hashlib
import json
import re
import sys
import time
from datetime import datetime
from pathlib import Path
from typing import Any, Dict, List, Optional, Set, Tuple

from playwright.sync_api import BrowserContext, ElementHandle, Page, sync_playwright

import config


# ==============================================================================
# 1. Caption Cleaning & Normalization
# ==============================================================================
UI_EXCLUDE_PHRASES = {
    "terjemahkan", "translate", "see translation", "lihat terjemahan",
    "balas", "reply", "suka", "like", "repost", "bagikan", "share",
    "ikuti", "follow", "mengikuti", "following", "log in", "masuk",
    "daftar", "sign up"
}


def clean_caption(text: Optional[str]) -> str:
    """
    Membersihkan teks caption secara ringan sesuai PRD §9:
    - Normalisasi line breaks berlebih (\n\n\n+ -> \n\n)
    - Hilangkan spasi berlebih
    - Pertahankan emoji, hashtag (#), dan mention (@)
    - Bersihkan jika ada teks UI nempel
    """
    if not text:
        return ""

    # Normalisasi spasi dan newline
    cleaned = text.strip()
    cleaned = re.sub(r"[ \t]+", " ", cleaned)
    cleaned = re.sub(r"\n\s*\n\s*\n+", "\n\n", cleaned)

    # Filter teks UI sederhana jika hanya berupa frase tombol
    if cleaned.lower() in UI_EXCLUDE_PHRASES:
        return ""

    return cleaned


# ==============================================================================
# 2. Deduplication Strategy
# ==============================================================================
def get_post_identifier(post: Dict[str, Any]) -> Optional[str]:
    """
    Menghasilkan identifier terbaik berjenjang untuk post (PRD §8):
    1. post_id
    2. url
    3. combo: username + 100 karakter pertama caption
    4. hash: sha256 dari caption
    """
    if post.get("post_id"):
        return f"id:{post['post_id']}"
    if post.get("url"):
        return f"url:{post['url']}"
    if post.get("username") and post.get("caption"):
        return f"combo:{post['username']}::{post['caption'][:100]}"
    if post.get("caption"):
        caption_hash = hashlib.sha256(post["caption"].encode("utf-8")).hexdigest()
        return f"hash:{caption_hash}"
    return None


def is_duplicate(post: Dict[str, Any], seen_identifiers: Set[str]) -> bool:
    """Mengecek apakah post sudah ada di set seen_identifiers."""
    ident = get_post_identifier(post)
    if ident is None:
        # Jika tidak ada identifier, anggap tidak duplikat tapi berisiko
        return False
    return ident in seen_identifiers


# ==============================================================================
# 3. Post Extraction Logic
# ==============================================================================
def extract_post_data(post_el: ElementHandle, feed_order: int) -> Optional[Dict[str, Any]]:
    """
    Mengekstrak data terstruktur dari satu elemen kartu postingan Threads.
    Dibungkus try/except agar error pada satu post tidak menghentikan keseluruhan run.
    """
    try:
        username = ""
        display_name = ""
        post_url = ""
        post_id = ""
        timestamp = ""
        caption = ""

        # --- A. Extract Username & Display Name ---
        try:
            profile_links = post_el.query_selector_all('a[href*="/@"]')
            for link in profile_links:
                href = link.get_attribute("href") or ""
                match = re.search(r"/@([a-zA-Z0-9._]+)", href)
                if match:
                    extracted_user = match.group(1).rstrip("/")
                    if not username:
                        username = extracted_user
                        text_content = (link.text_content() or "").strip()
                        if text_content and text_content != f"@{username}":
                            display_name = text_content
                        break
        except Exception:
            pass

        # --- B. Extract Post URL & Post ID ---
        try:
            post_links = post_el.query_selector_all('a[href*="/post/"]')
            for plink in post_links:
                phref = plink.get_attribute("href") or ""
                pmatch = re.search(r"/post/([a-zA-Z0-9_-]+)", phref)
                if pmatch:
                    post_id = pmatch.group(1)
                    if phref.startswith("http"):
                        post_url = phref.split("?")[0]
                    else:
                        post_url = f"{config.THREADS_URL}{phref}".split("?")[0]
                    break
        except Exception:
            pass

        # --- C. Extract Timestamp ---
        try:
            time_el = post_el.query_selector("time")
            if time_el:
                datetime_attr = time_el.get_attribute("datetime")
                time_text = (time_el.text_content() or "").strip()
                timestamp = datetime_attr if datetime_attr else time_text
        except Exception:
            pass

        # --- D. Extract Caption / Post Text ---
        try:
            text_elements = post_el.query_selector_all('div[dir="auto"], span[dir="auto"]')
            caption_candidates = []

            for tel in text_elements:
                t = (tel.text_content() or "").strip()
                if not t:
                    continue

                if username and (t == username or t == f"@{username}" or t == display_name):
                    continue

                if timestamp and (t == timestamp):
                    continue

                if re.fullmatch(r"[\d.,KMkm]+", t):
                    continue

                if t.lower() in UI_EXCLUDE_PHRASES:
                    continue

                caption_candidates.append(t)

            if caption_candidates:
                unique_candidates = []
                for cand in caption_candidates:
                    if cand not in unique_candidates and not any(cand in u for u in unique_candidates):
                        unique_candidates.append(cand)
                raw_caption = "\n\n".join(unique_candidates)
                caption = clean_caption(raw_caption)
        except Exception:
            pass

        # Fallback username jika belum dapat
        if not username:
            try:
                first_link = post_el.query_selector("a")
                if first_link:
                    first_href = first_link.get_attribute("href") or ""
                    f_match = re.search(r"/@([a-zA-Z0-9._]+)", first_href)
                    if f_match:
                        username = f_match.group(1)
            except Exception:
                pass

        scraped_now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

        return {
            "username": username,
            "display_name": display_name,
            "caption": caption,
            "post_id": post_id,
            "url": post_url,
            "timestamp": timestamp,
            "feed_order": feed_order,
            "scraped_at": scraped_now,
        }

    except Exception as e:
        return None


def extract_visible_posts(page: Page, start_order: int, retries: int = 3) -> List[Dict[str, Any]]:
    """
    Mengambil semua elemen post yang saat ini ada di DOM / Viewport dengan retry mechanism
    untuk menangani situasi SPA / re-render / navigasi yang sedang berjalan.
    """
    for attempt in range(retries):
        try:
            # Prioritas 1: tag <article>
            post_elements = page.query_selector_all("article")

            # Prioritas 2 fallback: data-pressable-container
            if not post_elements:
                post_elements = page.query_selector_all('div[data-pressable-container="true"]')

            # Prioritas 3 fallback: div yang mengandung link /@username
            if not post_elements:
                post_elements = page.query_selector_all('div:has(a[href*="/@"])')

            posts_data = []
            current_order = start_order
            for el in post_elements:
                extracted = extract_post_data(el, current_order)
                if extracted:
                    posts_data.append(extracted)
                    current_order += 1

            return posts_data

        except Exception as err:
            # Jika konteks DOM sedang dihancurkan/direfresh oleh SPA, tunggu dan coba lagi
            if attempt < retries - 1:
                time.sleep(1.0)
                continue
            else:
                return []


# ==============================================================================
# 4. Browser Management & Navigation
# ==============================================================================
def get_active_page(context: BrowserContext) -> Page:
    """Mendapatkan halaman/tab aktif dari browser context."""
    if not context.pages:
        return context.new_page()
    for p in reversed(context.pages):
        if not p.is_closed():
            return p
    return context.pages[0]


def launch_browser(playwright_inst) -> Tuple[BrowserContext, Page]:
    """Membuka browser Chromium dengan persistent context."""
    config.BROWSER_PROFILE_DIR.mkdir(parents=True, exist_ok=True)
    config.DATA_DIR.mkdir(parents=True, exist_ok=True)

    print(f"[INFO] Membuka browser Chromium (Headless: {config.HEADLESS})...")
    print(f"[INFO] Profile directory: {config.BROWSER_PROFILE_DIR.resolve()}")

    context = playwright_inst.chromium.launch_persistent_context(
        user_data_dir=str(config.BROWSER_PROFILE_DIR.resolve()),
        headless=config.HEADLESS,
        viewport={"width": 1280, "height": 900},
        args=[
            "--disable-blink-features=AutomationControlled",
            "--no-sandbox",
            "--start-maximized",
        ],
    )

    page = get_active_page(context)
    try:
        page.goto(config.THREADS_URL, wait_until="domcontentloaded", timeout=60000)
    except Exception as e:
        print(f"[WARNING] Goto initial page: {e}")

    return context, page


def wait_for_user_ready(context: BrowserContext) -> Page:
    """
    Menampilkan panduan di terminal agar user login dan membuka feed For You,
    lalu menunggu konfirmasi 'Enter' sebelum scraping dimulai.
    """
    print("\n" + "=" * 65)
    print("  PANDUAN PENGGUNA (MANUAL STEP):")
    print("  1. Periksa jendela browser yang terbuka.")
    print("  2. Jika belum login, silakan login akun Threads Anda secara manual.")
    print("  3. Pastikan Anda berada di feed 'For You' / 'Untuk Anda'.")
    print("  4. Jika sudah siap, kembali ke terminal ini dan tekan [ENTER].")
    print("=" * 65 + "\n")

    input("Tekan [ENTER] jika sudah berada di feed For You untuk memulai scraping... ")
    print("\n[INFO] Menyiapkan feed dan mulai mengekstrak postingan...\n")

    page = get_active_page(context)
    try:
        page.wait_for_load_state("domcontentloaded", timeout=5000)
    except Exception:
        pass
    time.sleep(2)
    return page


# ==============================================================================
# 5. Scrolling & Scraping Loop
# ==============================================================================
def scroll_feed(page: Page, pixels: int, delay: float) -> None:
    """Melakukan scroll ke bawah dan menunggu lazy-load render dengan proteksi error."""
    try:
        page.evaluate(f"window.scrollBy(0, {pixels});")
    except Exception:
        time.sleep(1.0)
    time.sleep(delay)


def run_scraper(context: BrowserContext) -> List[Dict[str, Any]]:
    """
    Looping utama scraping:
    Extract visible -> Deduplicate -> Check Target -> Scroll -> Repeat.
    """
    collected_posts: List[Dict[str, Any]] = []
    seen_identifiers: Set[str] = set()

    scroll_count = 0
    empty_scroll_count = 0
    feed_order_counter = 1

    print(f"[INFO] Target: {config.TARGET_POSTS} postingan unik.")
    print(f"[INFO] Memulai loop scraping...\n")

    page = get_active_page(context)

    while len(collected_posts) < config.TARGET_POSTS and scroll_count < config.MAX_SCROLLS:
        # Pastikan page yang aktif
        page = get_active_page(context)

        # 1. Ekstrak post yang terlihat di viewport
        visible_raw_posts = extract_visible_posts(page, feed_order_counter)
        new_posts_in_this_round = 0

        for post in visible_raw_posts:
            # Cek duplikasi
            if is_duplicate(post, seen_identifiers):
                continue

            # Cek kriteria caption jika ONLY_COUNT_WITH_CAPTION aktif
            has_caption = bool(post.get("caption") and len(post["caption"]) >= config.MIN_CAPTION_LENGTH)

            if config.ONLY_COUNT_WITH_CAPTION and not has_caption:
                # Catat identifier agar tidak diproses berulang, tapi tidak masuk ke target
                ident = get_post_identifier(post)
                if ident:
                    seen_identifiers.add(ident)
                continue

            # Tambahkan identifier ke set
            ident = get_post_identifier(post)
            if ident:
                seen_identifiers.add(ident)

            # Assign nomor urut penyimpanan
            post["no"] = len(collected_posts) + 1
            collected_posts.append(post)
            new_posts_in_this_round += 1
            feed_order_counter += 1

            # Log post singkat
            username_display = post["username"] or "unknown"
            snippet = (post["caption"][:50] + "...") if len(post["caption"]) > 50 else post["caption"]
            snippet_clean = snippet.replace("\n", " ")
            print(f"  [+] #{post['no']:03d} | @{username_display:<15} | {snippet_clean}")

            if len(collected_posts) >= config.TARGET_POSTS:
                break

        # 2. Log status putaran scroll
        scroll_count += 1
        print(
            f"[INFO] Scroll #{scroll_count:02d} | Post Baru: {new_posts_in_this_round:02d} | "
            f"Terkumpul: {len(collected_posts)}/{config.TARGET_POSTS}"
        )

        # 3. Cek apakah feed stuck / tidak menghasilkan post baru
        if new_posts_in_this_round == 0:
            empty_scroll_count += 1
            if empty_scroll_count >= config.EMPTY_SCROLL_LIMIT:
                print(
                    f"\n[WARNING] {config.EMPTY_SCROLL_LIMIT}x scroll berturut-turut tidak menemukan post baru. "
                    f"Menghentikan scraping lebih awal..."
                )
                break
        else:
            empty_scroll_count = 0

        # 4. Scroll jika target belum tercapai
        if len(collected_posts) < config.TARGET_POSTS:
            scroll_feed(page, config.SCROLL_PIXELS, config.SCROLL_DELAY)

    if scroll_count >= config.MAX_SCROLLS:
        print(f"\n[INFO] Mencapai batas maksimum scroll ({config.MAX_SCROLLS}).")

    return collected_posts


# ==============================================================================
# 6. Storage & Validation
# ==============================================================================
def save_to_csv(posts: List[Dict[str, Any]], filepath: Path) -> None:
    """Menyimpan hasil list post ke CSV ber-encoding utf-8-sig."""
    filepath.parent.mkdir(parents=True, exist_ok=True)
    fields = ["no", "username", "display_name", "caption", "post_id", "url", "timestamp", "feed_order", "scraped_at"]

    with open(filepath, mode="w", newline="", encoding="utf-8-sig") as f:
        writer = csv.DictWriter(f, fieldnames=fields, quoting=csv.QUOTE_MINIMAL)
        writer.writeheader()
        for p in posts:
            row = {field: p.get(field, "") for field in fields}
            writer.writerow(row)

    print(f"[SUCCESS] CSV berhasil disimpan ke: {filepath.resolve()}")


def save_to_json(posts: List[Dict[str, Any]], filepath: Path) -> None:
    """Menyimpan hasil list post ke format JSON untuk keperluan debugging."""
    filepath.parent.mkdir(parents=True, exist_ok=True)
    with open(filepath, mode="w", encoding="utf-8") as f:
        json.dump(posts, f, ensure_ascii=False, indent=2)

    print(f"[SUCCESS] JSON berhasil disimpan ke: {filepath.resolve()}")


def validate_dataset(posts: List[Dict[str, Any]], target: int) -> None:
    """Mencetak ringkasan validasi dataset di akhir eksekusi (PRD §18)."""
    total = len(posts)
    unique_ids = set()
    with_caption = 0
    without_caption = 0

    for p in posts:
        ident = get_post_identifier(p)
        if ident:
            unique_ids.add(ident)
        if p.get("caption") and p["caption"].strip():
            with_caption += 1
        else:
            without_caption += 1

    print("\n" + "=" * 55)
    print("         DATASET VALIDATION SUMMARY")
    print("=" * 55)
    print(f"  Target Postingan        : {target}")
    print(f"  Total Post Terkumpul    : {total}")
    print(f"  Total Post Unik         : {len(unique_ids)}")
    print(f"  Post Dengan Caption     : {with_caption}")
    print(f"  Post Tanpa Caption      : {without_caption}")
    print(f"  Output CSV File         : {config.OUTPUT_CSV}")
    print(f"  Output JSON File        : {config.OUTPUT_JSON}")
    print("=" * 55 + "\n")


# ==============================================================================
# 7. Main Function
# ==============================================================================
def main():
    print("=" * 65)
    print("       THREADS FYP CAPTION SCRAPER (PROOF OF CONCEPT)")
    print("=" * 65)

    with sync_playwright() as playwright_inst:
        context = None
        try:
            context, _ = launch_browser(playwright_inst)
            wait_for_user_ready(context)

            # Mulai scraping
            posts = run_scraper(context)

            if posts:
                # Simpan dataset
                save_to_csv(posts, config.OUTPUT_CSV)
                save_to_json(posts, config.OUTPUT_JSON)
                # Validasi dataset
                validate_dataset(posts, config.TARGET_POSTS)
            else:
                print("\n[WARNING] Tidak ada postingan yang berhasil dikumpulkan.")

        except KeyboardInterrupt:
            print("\n[INFO] Proses dihentikan oleh pengguna (Ctrl+C).")
        except Exception as err:
            print(f"\n[ERROR FATAL] Terjadi kesalahan: {err}")
            import traceback
            traceback.print_exc()
        finally:
            if context:
                print("[INFO] Menutup browser session...")
                context.close()
            print("[INFO] Selesai.")


if __name__ == "__main__":
    main()
