<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\KolProfile;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Export Commissions to CSV download.
     */
    public function exportCommissions(array $filters = []): StreamedResponse
    {
        $filename = 'laporan-komisi-' . now()->format('Y-m-d_His') . '.csv';

        $query = Commission::with(['kolProfile.user', 'endorsement.campaign.brand']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['kol_profile_id'])) {
            $query->where('kol_profile_id', $filters['kol_profile_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $output = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($output, [
                'ID Komisi',
                'Nama KOL',
                'Email KOL',
                'Bank',
                'No Rekening',
                'Atas Nama',
                'Campaign',
                'Brand',
                'Fee Endorsement (Rp)',
                'Persentase Komisi (%)',
                'Nominal Komisi (Rp)',
                'Porsi Agensi (Rp)',
                'Status',
                'Tanggal Dibuat',
                'Tanggal Dicairkan',
            ]);

            $query->chunk(200, function ($commissions) use ($output) {
                foreach ($commissions as $comm) {
                    $kol = $comm->kolProfile;
                    $campaign = $comm->endorsement?->campaign;
                    $brand = $campaign?->brand;

                    fputcsv($output, [
                        $comm->id,
                        $kol?->user?->name ?? '-',
                        $kol?->user?->email ?? '-',
                        $kol?->bank_name ?? '-',
                        $kol?->bank_account_number ? "'" . $kol->bank_account_number : '-',
                        $kol?->bank_account_name ?? '-',
                        $campaign?->title ?? '-',
                        $brand?->name ?? '-',
                        number_format($comm->endorsement_fee, 0, ',', '.'),
                        $comm->commission_pct . '%',
                        number_format($comm->commission_amount, 0, ',', '.'),
                        number_format($comm->agency_amount, 0, ',', '.'),
                        ucfirst(str_replace('_', ' ', $comm->status)),
                        $comm->created_at ? $comm->created_at->format('Y-m-d H:i') : '-',
                        $comm->disbursed_at ? $comm->disbursed_at->format('Y-m-d') : '-',
                    ]);
                }
            });

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export KOL Profiles to CSV download.
     */
    public function exportKolProfiles(array $filters = []): StreamedResponse
    {
        $filename = 'laporan-data-kol-' . now()->format('Y-m-d_His') . '.csv';

        $query = KolProfile::with(['user', 'tier', 'niches', 'socialMedia']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['tier_id'])) {
            $query->where('tier_id', $filters['tier_id']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($output, [
                'ID KOL',
                'Nama Lengkap',
                'Nama Panggilan',
                'Email',
                'Kota',
                'Provinsi',
                'Tier',
                'Niche',
                'Komisi (%)',
                'Bank',
                'No Rekening',
                'Atas Nama',
                'NPWP',
                'Status',
                'Tanggal Bergabung',
            ]);

            $query->chunk(200, function ($kols) use ($output) {
                foreach ($kols as $kol) {
                    $niches = $kol->niches->pluck('name')->implode(', ');

                    fputcsv($output, [
                        $kol->id,
                        $kol->user?->name ?? '-',
                        $kol->nickname ?? '-',
                        $kol->user?->email ?? '-',
                        $kol->city ?? '-',
                        $kol->province ?? '-',
                        $kol->tier?->name ?? '-',
                        $niches ?: '-',
                        $kol->effective_commission_pct . '%',
                        $kol->bank_name ?? '-',
                        $kol->bank_account_number ? "'" . $kol->bank_account_number : '-',
                        $kol->bank_account_name ?? '-',
                        $kol->npwp ?? '-',
                        ucfirst($kol->status),
                        $kol->joined_at ? $kol->joined_at->format('Y-m-d') : ($kol->created_at ? $kol->created_at->format('Y-m-d') : '-'),
                    ]);
                }
            });

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
