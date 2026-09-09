@extends('superadmin.layouts.app')
@section('title', 'Pengaturan')
@section('eyebrow', 'Superadmin')
@section('content')

<div class="sa-page-head"><div><h1>Pengaturan</h1><p>Konfigurasi operasional yang tersedia di PRD.</p></div><button class="sa-btn primary" data-toast="Pengaturan disimpan">Simpan Perubahan</button></div>
<div class="sa-grid sa-two">
<section class="sa-card"><h2>Skema Komisi per Tier</h2><p class="muted">Default dapat dikonfigurasi oleh Superadmin.</p><table class="sa-table"><thead><tr><th>Tier</th><th>Followers</th><th>Komisi KOL</th><th>Agensi</th></tr></thead><tbody>
<tr><td>Nano</td><td>&lt; 10K</td><td><input value="60" class="mini-input"> %</td><td>40%</td></tr>
<tr><td>Micro</td><td>10K–100K</td><td><input value="65" class="mini-input"> %</td><td>35%</td></tr>
<tr><td>Macro</td><td>100K–1M</td><td><input value="70" class="mini-input"> %</td><td>30%</td></tr>
<tr><td>Mega</td><td>&gt; 1M</td><td><input value="75" class="mini-input"> %</td><td>25%</td></tr>
</tbody></table></section>
<section class="sa-card"><h2>Lookup & Notifikasi</h2><label>Daftar niche<textarea>lifestyle, beauty, gaming, food, travel, tech, fashion, health, education, parenting, automotive, sports, entertainment, finance</textarea></label><label>Daftar platform<textarea>Instagram, TikTok, YouTube, Twitter/X</textarea></label><label>Pengingat deadline<select><option>H-3 dan H-1</option></select></label><label>Jam scheduler<input value="08:00 WIB"></label></section>
</div>

@endsection
