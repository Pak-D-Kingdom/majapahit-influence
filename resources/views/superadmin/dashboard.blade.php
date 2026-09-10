@extends('superadmin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Ringkasan Operasional')

@section('content')
    {{-- Header Sambutan & Quick Actions --}}
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-medium text-[#765f58]">Selamat datang kembali di Workspace Superadmin.</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#421b13] font-heading">
                Pantau Aktivitas Agensi Hari Ini
            </h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.reports.index') }}" class="btn-majapahit-secondary">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan & Ekspor</span>
            </a>
            <a href="{{ route('superadmin.campaigns.create') }}" class="btn-majapahit-primary">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Buat Campaign</span>
            </a>
        </div>
    </div>

    {{-- 5 Kartu Statistik Utama --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-dashboard.stat-card label="KOL Aktif" :value="number_format($stats['activeKols'])" icon="bi-people-fill" hint="Kreator terverifikasi" accent="orange"/>
        <x-dashboard.stat-card label="Endorsement Berjalan" :value="number_format($stats['activeEndorsements'])" icon="bi-briefcase-fill" hint="Project aktif" accent="amber"/>
        <x-dashboard.stat-card label="Pendaftaran Pending" :value="number_format($stats['pendingRegistrations'])" icon="bi-person-plus-fill" hint="Perlu verifikasi" accent="amber"/>
        <x-dashboard.stat-card label="Pencairan Pending" :value="number_format($stats['pendingDisbursements'])" icon="bi-wallet2" hint="Menunggu persetujuan" accent="rose"/>
        <x-dashboard.stat-card label="Komisi Belum Cair" :value="'Rp ' . number_format($stats['unpaidCommission'], 0, ',', '.')" icon="bi-cash-stack" hint="Total hak kreator" accent="emerald"/>
    </div>

    {{-- Visualisasi Tren & Notifikasi Terbaru --}}
    <div class="mt-8 grid gap-6 xl:grid-cols-[1.6fr_1fr]">
        {{-- Grafik Tren Endorsement & Perputaran Nilai Komisi (Dual-Metric Visualization + Multi-Period Filter) --}}
        <section class="flex flex-col justify-between rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#421b13]/8 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-heading text-lg font-bold text-[#421b13]">Tren Kinerja & Nilai Komisi</h3>
                            <span id="summary-period-badge" class="rounded-md bg-[#d57028]/10 px-2 py-0.5 text-[11px] font-bold text-[#d57028] font-heading">6 Bulan</span>
                        </div>
                        <p class="mt-0.5 text-xs text-[#765f58]">Volume endorsement vs perputaran nilai komisi (Rp)</p>
                    </div>
                    
                    {{-- Filter Periode Waktu (Pill Switcher) --}}
                    <div class="flex items-center">
                        <div class="inline-flex rounded-xl bg-[#f7eee8] p-1 border border-[#421b13]/8 text-xs font-medium text-[#765f58]">
                            <button type="button" data-period="weekly" class="period-filter-btn rounded-lg px-2.5 py-1 transition hover:text-[#421b13]">
                                Mingguan
                            </button>
                            <button type="button" data-period="1m" class="period-filter-btn rounded-lg px-2.5 py-1 transition hover:text-[#421b13]">
                                1 Bulan
                            </button>
                            <button type="button" data-period="6m" class="period-filter-btn active-filter rounded-lg bg-white px-2.5 py-1 font-bold text-[#d57028] shadow-xs transition">
                                6 Bulan
                            </button>
                            <button type="button" data-period="1y" class="period-filter-btn rounded-lg px-2.5 py-1 transition hover:text-[#421b13]">
                                1 Tahun
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Mini Summary Metric Bar --}}
                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4 rounded-xl bg-[#fbf7f4] p-3 border border-[#421b13]/5">
                    <div>
                        <p class="text-[11px] font-medium text-[#765f58]">Total Volume</p>
                        <p class="mt-0.5 text-sm font-bold text-[#421b13] font-heading">
                            <span id="summary-total-volume">{{ number_format($trendSummary['totalEndorsements']) }}</span> 
                            <span class="text-xs font-normal text-[#765f58]">Proyek</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-[#765f58]">Total Komisi</p>
                        <p class="mt-0.5 text-sm font-bold text-[#d5282d] font-heading">
                            <span id="summary-total-commission">Rp {{ number_format($trendSummary['totalCommission'], 0, ',', '.') }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-[#765f58]">Rata-rata Volume</p>
                        <p class="mt-0.5 text-sm font-bold text-[#421b13] font-heading">
                            <span id="summary-avg-volume">{{ $trendSummary['avgEndorsements'] }}</span> 
                            <span id="summary-volume-unit" class="text-xs font-normal text-[#765f58]">{{ $trendSummary['unitLabel'] ?? '/bln' }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-[#765f58]">Rata-rata Komisi</p>
                        <p class="mt-0.5 text-sm font-bold text-[#d57028] font-heading">
                            <span id="summary-avg-commission">Rp {{ number_format($trendSummary['avgCommission'], 0, ',', '.') }}</span>
                            <span id="summary-commission-unit" class="text-xs font-normal text-[#765f58]">{{ $trendSummary['unitLabel'] ?? '/bln' }}</span>
                        </p>
                    </div>
                </div>

                {{-- Chart Canvas Container --}}
                <div class="mt-4 relative h-60 sm:h-64 w-full">
                    <canvas id="dualMetricTrendChart"></canvas>
                </div>
            </div>

            {{-- Custom Legend Footer --}}
            <div class="mt-3 flex flex-wrap items-center justify-between gap-3 border-t border-[#421b13]/8 pt-3 text-xs text-[#765f58]">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="size-3 rounded bg-[#d57028]"></span>
                        <span class="font-medium text-[#421b13]">Volume Endorsement (Kiri)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block h-1 w-4 rounded-full bg-[#d5282d]"></span>
                        <span class="font-medium text-[#421b13]">Total Komisi Rp (Kanan)</span>
                    </div>
                </div>
                <div class="text-[11px] text-[#765f58]/80">
                    <i class="bi bi-info-circle mr-1"></i> Arahkan kursor ke titik grafik untuk detail
                </div>
            </div>
        </section>

        {{-- Notifikasi Terbaru --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading text-lg font-bold text-[#421b13]">Notifikasi Terbaru</h3>
                        <p class="mt-0.5 text-xs text-[#765f58]">Pemberitahuan aktivitas sistem penting</p>
                    </div>
                    <a href="{{ route('superadmin.notifications.index') }}" class="text-xs font-bold text-[#d57028] hover:text-[#b86021] transition hover:underline">
                        Lihat semua
                    </a>
                </div>

                @if ($notifications->isEmpty())
                    <div class="flex h-48 flex-col items-center justify-center text-center text-sm text-[#765f58]">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-[#f7eee8] text-[#765f58] mb-3">
                            <i class="bi bi-bell-slash text-xl"></i>
                        </div>
                        <p class="font-medium">Belum ada notifikasi baru.</p>
                        <p class="text-xs text-[#765f58]/80 mt-1">Aktivitas penting akan muncul otomatis di sini.</p>
                    </div>
                @else
                    <div class="mt-5 space-y-3.5">
                        @foreach ($notifications->take(4) as $notification)
                            <div class="flex items-start gap-3 rounded-xl p-2.5 transition hover:bg-[#fbf7f4]">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-[#d57028]/10 text-[#d57028]">
                                    <i class="bi bi-bell-fill text-sm"></i>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-[#421b13] font-heading">{{ $notification->title }}</p>
                                    <p class="mt-0.5 text-xs text-[#765f58] line-clamp-2">{{ $notification->body }}</p>
                                    <span class="mt-1 block text-[10px] text-[#765f58]/70">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if (!$notifications->isEmpty())
                <div class="mt-4 pt-4 border-t border-[#421b13]/8">
                    <a href="{{ route('superadmin.notifications.index') }}" class="block text-center text-xs font-bold text-[#d57028] hover:text-[#b86021]">
                        Buka Pusat Notifikasi Lengkap &rarr;
                    </a>
                </div>
            @endif
        </section>
    </div>

    {{-- Tabel Ringkasan Operasional --}}
    <div class="mt-8 grid gap-6 xl:grid-cols-2">
        {{-- Endorsement Mendekati Deadline --}}
        <section class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
            <div class="flex items-center justify-between p-5 border-b border-[#421b13]/8">
                <div>
                    <h3 class="font-heading text-base font-bold text-[#421b13]">Endorsement Mendekati Deadline</h3>
                    <p class="text-xs text-[#765f58]">Perlu pemantauan progres konten kreator</p>
                </div>
                <a href="{{ route('superadmin.endorsements.index') }}" class="text-xs font-bold text-[#d57028] hover:text-[#b86021] hover:underline">
                    Lihat semua
                </a>
            </div>

            @if ($upcomingEndorsements->isEmpty())
                <div class="flex h-44 flex-col items-center justify-center text-center text-sm text-[#765f58] p-6">
                    <div class="flex size-11 items-center justify-center rounded-xl bg-[#f7eee8] text-[#765f58] mb-2">
                        <i class="bi bi-calendar-check text-xl"></i>
                    </div>
                    <p class="font-medium">Tidak ada endorsement mendekati deadline saat ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-[#421b13]/8 bg-[#fbf7f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                            <tr>
                                <th class="px-5 py-3.5 font-semibold">KOL / Brand</th>
                                <th class="px-5 py-3.5 font-semibold">Tenggat Waktu</th>
                                <th class="px-5 py-3.5 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#421b13]/6">
                            @foreach ($upcomingEndorsements as $endorsement)
                                <tr class="transition hover:bg-[#fff9f4]/60">
                                    <td class="px-5 py-3.5">
                                        <p class="font-bold text-[#421b13] font-heading">{{ $endorsement->kolProfile->user->name ?? 'KOL' }}</p>
                                        <p class="text-xs text-[#765f58]">{{ $endorsement->campaign->brand->name ?? 'Brand' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-xs font-semibold text-[#421b13]">
                                        <i class="bi bi-clock mr-1 text-[#d57028]"></i>
                                        {{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <x-dashboard.status-badge :status="$endorsement->status"/>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Pendaftaran KOL Terbaru --}}
        <section class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
            <div class="flex items-center justify-between p-5 border-b border-[#421b13]/8">
                <div>
                    <h3 class="font-heading text-base font-bold text-[#421b13]">Pendaftaran KOL Terbaru</h3>
                    <p class="text-xs text-[#765f58]">Kreator baru yang menunggu review verifikasi</p>
                </div>
                <a href="{{ route('superadmin.registrations.index') }}" class="text-xs font-bold text-[#d57028] hover:text-[#b86021] hover:underline">
                    Lihat semua
                </a>
            </div>

            @if ($recentRegistrations->isEmpty())
                <div class="flex h-44 flex-col items-center justify-center text-center text-sm text-[#765f58] p-6">
                    <div class="flex size-11 items-center justify-center rounded-xl bg-[#f7eee8] text-[#765f58] mb-2">
                        <i class="bi bi-person-plus text-xl"></i>
                    </div>
                    <p class="font-medium">Belum ada pengajuan pendaftaran baru.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-[#421b13]/8 bg-[#fbf7f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                            <tr>
                                <th class="px-5 py-3.5 font-semibold">Nama Kreator</th>
                                <th class="px-5 py-3.5 font-semibold">Tanggal Daftar</th>
                                <th class="px-5 py-3.5 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#421b13]/6">
                            @foreach ($recentRegistrations as $registration)
                                <tr class="transition hover:bg-[#fff9f4]/60">
                                    <td class="px-5 py-3.5">
                                        <p class="font-bold text-[#421b13] font-heading">{{ $registration->full_name }}</p>
                                        <p class="text-xs text-[#765f58]">{{ $registration->city ?: 'Lokasi belum diisi' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-xs text-[#765f58]">
                                        {{ $registration->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <x-dashboard.status-badge :status="$registration->status"/>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dualMetricTrendChart');
            if (!ctx) return;

            const trendDatasets = @json($trendDatasets);
            let currentPeriod = '6m';

            const activeBtnClasses = ['active-filter', 'bg-white', 'text-[#d57028]', 'shadow-xs', 'font-bold'];
            const inactiveBtnClasses = ['text-[#765f58]', 'hover:text-[#421b13]', 'font-medium'];

            const formatRupiah = (number) => {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            };

            const initialData = trendDatasets[currentPeriod] || trendDatasets['6m'];
            const maxVolume = Math.max(...initialData.volumes, 5);
            const maxCommission = Math.max(...initialData.commissions, 1000000);

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: initialData.labels,
                    datasets: [
                        {
                            label: 'Volume Endorsement',
                            data: initialData.volumes,
                            type: 'bar',
                            yAxisID: 'y',
                            backgroundColor: 'rgba(213, 112, 40, 0.85)',
                            hoverBackgroundColor: '#d57028',
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.45,
                            categoryPercentage: 0.7,
                            order: 2
                        },
                        {
                            label: 'Total Komisi',
                            data: initialData.commissions,
                            type: 'line',
                            yAxisID: 'y1',
                            borderColor: '#d5282d',
                            backgroundColor: 'rgba(213, 40, 45, 0.08)',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#d5282d',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#d5282d',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 2,
                            tension: 0.35,
                            fill: true,
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(66, 27, 19, 0.95)',
                            titleColor: '#ffffff',
                            bodyColor: '#f7eee8',
                            titleFont: {
                                family: '"Plus Jakarta Sans", sans-serif',
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                family: '"DM Sans", sans-serif',
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 10,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                title: function(tooltipItems) {
                                    if (!tooltipItems.length) return '';
                                    const rawLabel = tooltipItems[0].label || '';
                                    if (currentPeriod === 'weekly') {
                                        return 'Rentang: ' + rawLabel;
                                    }
                                    return rawLabel;
                                },
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }

                                    const isCommission = context.dataset.yAxisID === 'y1';
                                    const currentValue = context.parsed.y;
                                    const dataIndex = context.dataIndex;
                                    const datasetData = context.dataset.data;

                                    if (isCommission) {
                                        label += formatRupiah(currentValue);
                                    } else {
                                        label += currentValue + ' Proyek';
                                    }

                                    // Persentase kenaikan/penurunan dibanding titik sebelumnya
                                    if (dataIndex > 0) {
                                        const prevValue = datasetData[dataIndex - 1];
                                        let diffPct = 0;
                                        if (prevValue > 0) {
                                            diffPct = ((currentValue - prevValue) / prevValue) * 100;
                                        } else if (currentValue > 0) {
                                            diffPct = 100;
                                        }

                                        const formattedPct = (diffPct >= 0 ? '+' : '') + (diffPct % 1 === 0 ? diffPct.toFixed(0) : diffPct.toFixed(1)) + '%';
                                        label += ` (${formattedPct})`;
                                    }

                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: '"DM Sans", sans-serif',
                                    size: 11,
                                    weight: '600'
                                },
                                color: '#765f58'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            suggestedMax: Math.ceil(maxVolume * 1.25),
                            grid: {
                                color: 'rgba(66, 27, 19, 0.06)',
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0,
                                font: {
                                    family: '"DM Sans", sans-serif',
                                    size: 11
                                },
                                color: '#765f58',
                                callback: function(value) {
                                    return value + ' prj';
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            suggestedMax: maxCommission > 0 ? maxCommission * 1.25 : 1000000,
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                font: {
                                    family: '"DM Sans", sans-serif',
                                    size: 11
                                },
                                color: '#d5282d',
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(value % 1000000 === 0 ? 0 : 1) + ' jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });

            function updateSummaryUI(periodData) {
                const summary = periodData.summary;

                const periodBadge = document.getElementById('summary-period-badge');
                if (periodBadge) periodBadge.textContent = periodData.period_label;

                const totalVolume = document.getElementById('summary-total-volume');
                if (totalVolume) totalVolume.textContent = new Intl.NumberFormat('id-ID').format(summary.totalEndorsements);

                const totalCommission = document.getElementById('summary-total-commission');
                if (totalCommission) totalCommission.textContent = formatRupiah(summary.totalCommission);

                const avgVolume = document.getElementById('summary-avg-volume');
                if (avgVolume) avgVolume.textContent = summary.avgEndorsements;

                const avgCommission = document.getElementById('summary-avg-commission');
                if (avgCommission) avgCommission.textContent = formatRupiah(summary.avgCommission);

                const volumeUnit = document.getElementById('summary-volume-unit');
                if (volumeUnit) volumeUnit.textContent = summary.unitLabel || '/bln';

                const commissionUnit = document.getElementById('summary-commission-unit');
                if (commissionUnit) commissionUnit.textContent = summary.unitLabel || '/bln';
            }

            const filterButtons = document.querySelectorAll('.period-filter-btn');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const period = this.getAttribute('data-period');
                    if (!trendDatasets[period] || period === currentPeriod) return;

                    currentPeriod = period;
                    const periodData = trendDatasets[period];

                    filterButtons.forEach(b => {
                        b.classList.remove(...activeBtnClasses);
                        b.classList.add(...inactiveBtnClasses);
                    });
                    this.classList.remove(...inactiveBtnClasses);
                    this.classList.add(...activeBtnClasses);

                    chart.data.labels = periodData.labels;
                    chart.data.datasets[0].data = periodData.volumes;
                    chart.data.datasets[1].data = periodData.commissions;

                    const newMaxVol = Math.max(...periodData.volumes, 5);
                    const newMaxComm = Math.max(...periodData.commissions, 1000000);

                    chart.options.scales.y.suggestedMax = Math.ceil(newMaxVol * 1.25);
                    chart.options.scales.y1.suggestedMax = newMaxComm > 0 ? newMaxComm * 1.25 : 1000000;

                    chart.update();

                    updateSummaryUI(periodData);
                });
            });
        });
    </script>
@endpush
