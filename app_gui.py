"""
app_gui.py
Desktop UI Sederhana untuk Threads FYP Scraper & Summarizer
Dibuat dengan Python Tkinter (Native GUI).
"""

import csv
import json
import os
import subprocess
import sys
import threading
import time
import tkinter as tk
from pathlib import Path
from tkinter import messagebox, ttk

import config
import summarizer
from playwright.sync_api import sync_playwright

# File paths
CSV_PATH = config.OUTPUT_CSV
MD_PATH = config.OUTPUT_SUMMARY_MD
DATA_DIR = config.DATA_DIR


class ScraperGUI(tk.Tk):
    def __init__(self):
        super().__init__()

        self.title("Threads FYP Scraper & AI Summarizer")
        self.geometry("960x680")
        self.minsize(800, 550)

        # State
        self.is_scraping = False
        self.stop_requested = False

        self._setup_style()
        self._build_ui()
        self._load_existing_data()

    def _setup_style(self):
        style = ttk.Style(self)
        if "clam" in style.theme_names():
            style.theme_use("clam")

        style.configure("Header.TLabel", font=("Segoe UI", 16, "bold"), foreground="#1e293b")
        style.configure("SubHeader.TLabel", font=("Segoe UI", 9), foreground="#64748b")
        style.configure("Card.TFrame", background="#f8fafc")
        style.configure("Accent.TButton", font=("Segoe UI", 10, "bold"), background="#0284c7", foreground="white")
        style.map("Accent.TButton", background=[("active", "#0369a1")])

    def _build_ui(self):
        # 1. Header Banner
        header_frame = ttk.Frame(self, padding=(15, 10))
        header_frame.pack(fill=tk.X)

        title_lbl = ttk.Label(header_frame, text="🧵 Threads FYP Scraper & Summarizer", style="Header.TLabel")
        title_lbl.pack(anchor="w")

        subtitle_lbl = ttk.Label(
            header_frame,
            text="Otomasi pengumpulan 100 caption feed FYP Threads dan analisis kesimpulan berbasis AI.",
            style="SubHeader.TLabel"
        )
        subtitle_lbl.pack(anchor="w")

        ttk.Separator(self, orient="horizontal").pack(fill=tk.X, pady=(0, 5))

        # 2. Tabs Container
        self.notebook = ttk.Notebook(self)
        self.notebook.pack(fill=tk.BOTH, expand=True, padx=10, pady=5)

        # Tab 1: Scraping
        self.tab_scraping = ttk.Frame(self.notebook, padding=10)
        self.notebook.add(self.tab_scraping, text=" 🚀 1. Scraper ")

        # Tab 2: Rangkuman
        self.tab_summary = ttk.Frame(self.notebook, padding=10)
        self.notebook.add(self.tab_summary, text=" 📑 2. Rangkuman & Kesimpulan ")

        # Tab 3: Data Table
        self.tab_data = ttk.Frame(self.notebook, padding=10)
        self.notebook.add(self.tab_data, text=" 📊 3. Tabel Data (CSV) ")

        self._build_tab_scraping()
        self._build_tab_summary()
        self._build_tab_data()

        # 3. Status Bar
        self.status_var = tk.StringVar(value="Siap. Klik 'Mulai Scraping' untuk memulai.")
        status_bar = ttk.Label(self, textvariable=self.status_var, relief=tk.SUNKEN, anchor="w", padding=(10, 4))
        status_bar.pack(side=tk.BOTTOM, fill=tk.X)

    # --------------------------------------------------------------------------
    # TAB 1: SCRAPER
    # --------------------------------------------------------------------------
    def _build_tab_scraping(self):
        ctrl_frame = ttk.LabelFrame(self.tab_scraping, text=" Pengaturan Scraping ", padding=10)
        ctrl_frame.pack(fill=tk.X, pady=(0, 10))

        row1 = ttk.Frame(ctrl_frame)
        row1.pack(fill=tk.X)

        ttk.Label(row1, text="Target Postingan:").pack(side=tk.LEFT, padx=(0, 5))
        self.target_entry = ttk.Entry(row1, width=8)
        self.target_entry.insert(0, str(config.TARGET_POSTS))
        self.target_entry.pack(side=tk.LEFT, padx=(0, 20))

        ttk.Label(row1, text="Jeda Scroll (detik):").pack(side=tk.LEFT, padx=(0, 5))
        self.delay_entry = ttk.Entry(row1, width=6)
        self.delay_entry.insert(0, str(config.SCROLL_DELAY))
        self.delay_entry.pack(side=tk.LEFT, padx=(0, 20))

        self.btn_scrape = tk.Button(
            row1, text="▶ Mulai Scraping", bg="#0284c7", fg="white", font=("Segoe UI", 9, "bold"),
            padx=12, pady=3, relief=tk.FLAT, cursor="hand2", command=self._start_scraping_thread
        )
        self.btn_scrape.pack(side=tk.LEFT, padx=(0, 10))

        self.btn_open_folder = ttk.Button(row1, text="📂 Buka Folder Data", command=self._open_data_folder)
        self.btn_open_folder.pack(side=tk.RIGHT)

        # Progress bar
        self.progress_var = tk.DoubleVar(value=0.0)
        self.progress_bar = ttk.Progressbar(self.tab_scraping, variable=self.progress_var, maximum=100)
        self.progress_bar.pack(fill=tk.X, pady=(0, 10))

        # Log Output
        log_frame = ttk.LabelFrame(self.tab_scraping, text=" Terminal Log Real-time ", padding=5)
        log_frame.pack(fill=tk.BOTH, expand=True)

        self.log_text = tk.Text(log_frame, bg="#0f172a", fg="#f8fafc", font=("Consolas", 9), wrap=tk.WORD)
        scrollbar = ttk.Scrollbar(log_frame, orient="vertical", command=self.log_text.yview)
        self.log_text.configure(yscrollcommand=scrollbar.set)

        scrollbar.pack(side=tk.RIGHT, fill=tk.Y)
        self.log_text.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)

        self._log("Aplikasi siap. Klik tombol 'Mulai Scraping' di atas.\nBrowser Chromium akan terbuka untuk login manual jika belum.")

    def _log(self, text: str):
        self.log_text.insert(tk.END, text + "\n")
        self.log_text.see(tk.END)

    # --------------------------------------------------------------------------
    # TAB 2: RANGKUMAN
    # --------------------------------------------------------------------------
    def _build_tab_summary(self):
        ctrl_frame = ttk.LabelFrame(self.tab_summary, text=" Pengaturan AI Summarizer (Groq AI) ", padding=10)
        ctrl_frame.pack(fill=tk.X, pady=(0, 10))

        row = ttk.Frame(ctrl_frame)
        row.pack(fill=tk.X)

        ttk.Label(row, text="Groq API Key:").pack(side=tk.LEFT, padx=(0, 5))
        self.groq_key_entry = ttk.Entry(row, width=35, show="*")
        saved_key = config.GROQ_API_KEY or os.getenv("GROQ_API_KEY", "")
        if saved_key:
            self.groq_key_entry.insert(0, saved_key)
        self.groq_key_entry.pack(side=tk.LEFT, padx=(0, 15))

        self.btn_summarize = tk.Button(
            row, text="✨ Buat Rangkuman Sekarang", bg="#10b981", fg="white", font=("Segoe UI", 9, "bold"),
            padx=12, pady=3, relief=tk.FLAT, cursor="hand2", command=self._start_summary_thread
        )
        self.btn_summarize.pack(side=tk.LEFT, padx=(0, 10))

        btn_copy = ttk.Button(row, text="📋 Salin Teks", command=self._copy_summary)
        btn_copy.pack(side=tk.RIGHT)

        # Summary Display Area
        view_frame = ttk.LabelFrame(self.tab_summary, text=" Isi Rangkuman (Markdown View) ", padding=5)
        view_frame.pack(fill=tk.BOTH, expand=True)

        self.summary_text = tk.Text(view_frame, bg="#ffffff", fg="#1e293b", font=("Segoe UI", 10), wrap=tk.WORD)
        scroll_sum = ttk.Scrollbar(view_frame, orient="vertical", command=self.summary_text.yview)
        self.summary_text.configure(yscrollcommand=scroll_sum.set)

        scroll_sum.pack(side=tk.RIGHT, fill=tk.Y)
        self.summary_text.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)

    # --------------------------------------------------------------------------
    # TAB 3: DATA TABLE
    # --------------------------------------------------------------------------
    def _build_tab_data(self):
        top_bar = ttk.Frame(self.tab_data)
        top_bar.pack(fill=tk.X, pady=(0, 5))

        self.data_count_lbl = ttk.Label(top_bar, text="Total Postingan: 0", font=("Segoe UI", 9, "bold"))
        self.data_count_lbl.pack(side=tk.LEFT)

        btn_refresh = ttk.Button(top_bar, text="🔄 Refresh Data", command=self._load_existing_data)
        btn_refresh.pack(side=tk.RIGHT, padx=(5, 0))

        btn_open_csv = ttk.Button(top_bar, text="📊 Buka File CSV", command=self._open_csv_file)
        btn_open_csv.pack(side=tk.RIGHT)

        table_frame = ttk.Frame(self.tab_data)
        table_frame.pack(fill=tk.BOTH, expand=True)

        columns = ("no", "username", "caption", "url", "scraped_at")
        self.tree = ttk.Treeview(table_frame, columns=columns, show="headings", selectmode="browse")

        self.tree.heading("no", text="No")
        self.tree.heading("username", text="Username")
        self.tree.heading("caption", text="Caption Postingan")
        self.tree.heading("url", text="URL Postingan")
        self.tree.heading("scraped_at", text="Waktu Scrap")

        self.tree.column("no", width=45, anchor="center")
        self.tree.column("username", width=120)
        self.tree.column("caption", width=480)
        self.tree.column("url", width=150)
        self.tree.column("scraped_at", width=120)

        scroll_tree = ttk.Scrollbar(table_frame, orient="vertical", command=self.tree.yview)
        self.tree.configure(yscrollcommand=scroll_tree.set)

        scroll_tree.pack(side=tk.RIGHT, fill=tk.Y)
        self.tree.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)

    # --------------------------------------------------------------------------
    # LOGIC: DATA LOADING & ACTIONS
    # --------------------------------------------------------------------------
    def _load_existing_data(self):
        if CSV_PATH.exists():
            for item in self.tree.get_children():
                self.tree.delete(item)

            count = 0
            with open(CSV_PATH, mode="r", encoding="utf-8-sig") as f:
                reader = csv.DictReader(f)
                for r in reader:
                    cap_snip = r.get("caption", "").replace("\n", " ")
                    self.tree.insert("", tk.END, values=(
                        r.get("no", ""),
                        "@" + r.get("username", ""),
                        cap_snip,
                        r.get("url", ""),
                        r.get("scraped_at", "")
                    ))
                    count += 1
            self.data_count_lbl.config(text=f"Total Postingan di CSV: {count}")

        if MD_PATH.exists():
            with open(MD_PATH, mode="r", encoding="utf-8") as f:
                content = f.read()
                self.summary_text.delete("1.0", tk.END)
                self.summary_text.insert(tk.END, content)

    def _open_data_folder(self):
        DATA_DIR.mkdir(parents=True, exist_ok=True)
        if sys.platform == "win32":
            os.startfile(DATA_DIR.resolve())
        else:
            subprocess.run(["open", str(DATA_DIR.resolve())])

    def _open_csv_file(self):
        if CSV_PATH.exists():
            if sys.platform == "win32":
                os.startfile(CSV_PATH.resolve())
            else:
                subprocess.run(["open", str(CSV_PATH.resolve())])
        else:
            messagebox.showwarning("File Belum Ada", "File CSV belum ditemukan. Jalankan scraping terlebih dahulu.")

    def _copy_summary(self):
        content = self.summary_text.get("1.0", tk.END).strip()
        if content:
            self.clipboard_clear()
            self.clipboard_append(content)
            messagebox.showinfo("Berhasil Disalin", "Teks rangkuman berhasil disalin ke clipboard!")

    # --------------------------------------------------------------------------
    # LOGIC: SCRAPING THREAD
    # --------------------------------------------------------------------------
    def _start_scraping_thread(self):
        if self.is_scraping:
            return

        try:
            target = int(self.target_entry.get().strip())
            delay = float(self.delay_entry.get().strip())
        except ValueError:
            messagebox.showerror("Input Salah", "Target dan Jeda Scroll harus berupa angka.")
            return

        self.is_scraping = True
        self.btn_scrape.config(state=tk.DISABLED, bg="#94a3b8")
        self.progress_var.set(0)
        self._log("\n" + "=" * 50)
        self._log("🚀 MEMULAI SESI SCRAPING THREADS FYP")
        self._log("=" * 50)

        threading.Thread(target=self._run_scraper_task, args=(target, delay), daemon=True).start()

    def _run_scraper_task(self, target: int, delay: float):
        import threads_scraper

        try:
            with sync_playwright() as pw:
                self.status_var.set("Membuka browser Chromium...")
                self._log("[INFO] Membuka jendela Chromium...")

                context, page = threads_scraper.launch_browser(pw)

                self.status_var.set("Menunggu konfirmasi posisi feed For You di browser...")
                self._log("\n[PENTING] Silakan login manual di browser jika belum, lalu pastikan berada di feed 'For You'.")

                res = messagebox.askokcancel(
                    "Siap Mulai Scraping?",
                    "1. Pastikan Anda sudah login akun Threads di browser.\n"
                    "2. Pastikan Anda sudah membuka tab 'For You' (Untuk Anda).\n\n"
                    "Klik OK jika sudah siap untuk memulai scraping otomatis!"
                )

                if not res:
                    self._log("[INFO] Scraping dibatalkan oleh pengguna.")
                    context.close()
                    self._reset_scraper_ui()
                    return

                self.status_var.set(f"Scraping berjalan... Target: {target}")
                self._log("[INFO] Memulai ekstraksi postingan...")

                collected = []
                seen_ids = set()
                scroll_count = 0
                empty_scrolls = 0
                feed_order = 1

                while len(collected) < target and scroll_count < config.MAX_SCROLLS:
                    curr_page = threads_scraper.get_active_page(context)
                    visible = threads_scraper.extract_visible_posts(curr_page, feed_order)
                    new_in_round = 0

                    for p in visible:
                        if threads_scraper.is_duplicate(p, seen_ids):
                            continue

                        has_cap = bool(p.get("caption") and len(p["caption"]) >= config.MIN_CAPTION_LENGTH)
                        if config.ONLY_COUNT_WITH_CAPTION and not has_cap:
                            ident = threads_scraper.get_post_identifier(p)
                            if ident:
                                seen_ids.add(ident)
                            continue

                        ident = threads_scraper.get_post_identifier(p)
                        if ident:
                            seen_ids.add(ident)

                        p["no"] = len(collected) + 1
                        collected.append(p)
                        new_in_round += 1
                        feed_order += 1

                        user = p["username"] or "unknown"
                        snip = (p["caption"][:60] + "...").replace("\n", " ")
                        self._log(f"  [+] #{p['no']:03d} | @{user:<14} | {snip}")

                        pct = (len(collected) / target) * 100
                        self.progress_var.set(pct)
                        self.status_var.set(f"Terkumpul: {len(collected)}/{target} post ({int(pct)}%)")

                        if len(collected) >= target:
                            break

                    scroll_count += 1
                    self._log(f"[INFO] Scroll #{scroll_count:02d} | Post Baru: {new_in_round} | Total: {len(collected)}/{target}")

                    if new_in_round == 0:
                        empty_scrolls += 1
                        if empty_scrolls >= config.EMPTY_SCROLL_LIMIT:
                            self._log(f"[WARNING] Feed kosong berturut-turut {config.EMPTY_SCROLL_LIMIT}x. Berhenti.")
                            break
                    else:
                        empty_scrolls = 0

                    if len(collected) < target:
                        threads_scraper.scroll_feed(curr_page, config.SCROLL_PIXELS, delay)

                if collected:
                    threads_scraper.save_to_csv(collected, config.OUTPUT_CSV)
                    threads_scraper.save_to_json(collected, config.OUTPUT_JSON)
                    self._log("\n[SUCCESS] Scraping selesai dan data berhasil disimpan!")
                    self.after(500, self._load_existing_data)
                else:
                    self._log("\n[WARNING] Tidak ada data yang berhasil dikumpulkan.")

                context.close()

        except Exception as e:
            self._log(f"\n[ERROR] Terjadi kesalahan: {e}")
            messagebox.showerror("Error Scraping", str(e))
        finally:
            self._reset_scraper_ui()

    def _reset_scraper_ui(self):
        self.is_scraping = False
        self.btn_scrape.config(state=tk.NORMAL, bg="#0284c7")
        self.status_var.set("Selesai.")

    # --------------------------------------------------------------------------
    # LOGIC: SUMMARIZER THREAD
    # --------------------------------------------------------------------------
    def _start_summary_thread(self):
        if not CSV_PATH.exists():
            messagebox.showwarning("Data Kosong", "File dataset CSV belum ada. Jalankan scraping terlebih dahulu.")
            return

        key = self.groq_key_entry.get().strip()
        self.btn_summarize.config(state=tk.DISABLED, bg="#94a3b8")
        self.status_var.set("Sedang menyusun rangkuman...")

        threading.Thread(target=self._run_summary_task, args=(key,), daemon=True).start()

    def _run_summary_task(self, api_key: str):
        try:
            posts = summarizer.load_dataset(config.OUTPUT_CSV, config.OUTPUT_JSON)
            if not posts:
                messagebox.showwarning("Data Kosong", "Tidak ada postingan yang ditemukan di dataset.")
                return

            ai_summary = None
            if api_key:
                prompt = summarizer.build_llm_prompt(posts)
                ai_summary = summarizer.call_groq_api(prompt, api_key, config.GROQ_MODEL)

            if ai_summary:
                final_summary = ai_summary
            else:
                final_summary = summarizer.generate_direct_summary(posts)

            summarizer.save_summary_files(final_summary)

            self.after(0, lambda: self._update_summary_ui(final_summary))
        except Exception as e:
            messagebox.showerror("Error Summarizer", str(e))
        finally:
            self.after(0, lambda: self.btn_summarize.config(state=tk.NORMAL, bg="#10b981"))
            self.status_var.set("Rangkuman selesai dibuat.")

    def _update_summary_ui(self, md_content: str):
        self.summary_text.delete("1.0", tk.END)
        self.summary_text.insert(tk.END, md_content)
        self.notebook.select(self.tab_summary)
        messagebox.showinfo("Rangkuman Selesai", "Laporan rangkuman berhasil dibuat dan diperbarui!")


if __name__ == "__main__":
    app = ScraperGUI()
    app.mainloop()
