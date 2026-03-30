<?php

namespace App\Services\ActivityLog;

use App\Models\ActivityLog; // Panggil model custom kamu
use Carbon\Carbon;

class ReportService
{
    public function generateSarpraReport($startDate, $endDate)
    {
        // 1. Ambil log dengan relasi user (biar ga berat pas looping/N+1)
        $logs = ActivityLog::with('user') 
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->beginOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->get();

        // 2. Filter data secara spesifik untuk statistik
        // Kita gunakan collection method 'where' agar tidak perlu query database berkali-kali
        $stats = [
            'approved' => $logs->where('activity', 'approve_peminjaman')->count(),
            'rejected' => $logs->where('activity', 'reject_peminjaman')->count(),
            'rusak'    => $logs->where('activity', 'update_kondisi')->where('description', 'rusak')->count(),
            'hilang'   => $logs->where('activity', 'update_kondisi')->where('description', 'hilang')->count(),
        ];

        return [
            'logs'  => $logs,
            'stats' => $stats,
            'start' => $startDate,
            'end'   => $endDate,
        ];
    }
}