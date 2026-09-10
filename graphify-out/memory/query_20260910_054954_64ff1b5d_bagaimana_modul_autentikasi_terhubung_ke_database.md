---
type: "query"
date: "2026-09-10T05:49:54.612845+00:00"
question: "Bagaimana modul autentikasi terhubung ke database?"
contributor: "graphify"
source_nodes: ["AuthController.php", "LoginRequest.php", "Illuminate\\Support\\Facades\\Auth", "database.php"]
---

# Q: Bagaimana modul autentikasi terhubung ke database?

## Answer

Expanded from original query via vocab: [auth, login, database]. Modul autentikasi terhubung ke database melalui alur berikut: (1) LoginRequest memvalidasi input dan rate limit. (2) AuthController (.login()) memanggil Auth::attempt() dari fasad Illuminate\Support\Facades\Auth. (3) Auth::attempt menggunakan model User (Eloquent) yang mengakses koneksi ke tabel database seperti yang diatur pada config/database.php.

## Source Nodes

- AuthController.php
- LoginRequest.php
- Illuminate\Support\Facades\Auth
- database.php