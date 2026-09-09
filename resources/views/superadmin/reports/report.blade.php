@extends('superadmin.layouts.app')
@section('title','Laporan')
@section('page-title','Laporan')
@section('content')
<div class="sa-page-head"><div><h2>Laporan</h2><p>Export laporan komisi, endorsement, data KOL, dan performa campaign.</p></div></div>
<div class="sa-grid sa-grid-2">
<section class="sa-card sa-section"><div class="sa-section-head"><h3 class="sa-section-title">Laporan Komisi</h3></div><p style="font-size:11px;color:var(--muted);margin-bottom:16px">Laporan komisi per periode bulanan / kuartalan.</p><div class="sa-form-grid"><div class="sa-field"><label>Periode Mulai</label><input type="date"></div><div class="sa-field"><label>Periode Selesai</label><input type="date"></div></div><div class="sa-form-actions"><button class="sa-btn sa-btn-primary" data-demo-alert="Export laporan komisi siap dihubungkan.">Export Excel</button></div></section>
<section class="sa-card sa-section"><div class="sa-section-head"><h3 class="sa-section-title">Laporan Endorsement</h3></div><p style="font-size:11px;color:var(--muted);margin-bottom:16px">Export endorsement berdasarkan campaign.</p><div class="sa-field"><label>Campaign</label><select><option>Semua campaign</option><option>Glow Up September</option><option>Kopi Lokal Challenge</option></select></div><div class="sa-form-actions"><button class="sa-btn sa-btn-primary" data-demo-alert="Export laporan endorsement siap dihubungkan.">Export CSV</button></div></section>
<section class="sa-card sa-section"><div class="sa-section-head"><h3 class="sa-section-title">Laporan Data KOL</h3></div><p style="font-size:11px;color:var(--muted);margin-bottom:16px">Export data KOL sesuai filter yang dipilih.</p><button class="sa-btn sa-btn-primary" data-demo-alert="Export data KOL siap dihubungkan.">Export CSV</button></section>
<section class="sa-card sa-section"><div class="sa-section-head"><h3 class="sa-section-title">Performa Campaign</h3></div><p style="font-size:11px;color:var(--muted);margin-bottom:16px">Ringkasan jumlah endorsement dan completion rate.</p><button class="sa-btn sa-btn-primary" data-demo-alert="Export performa campaign siap dihubungkan.">Export Excel</button></section>
</div>
@endsection
