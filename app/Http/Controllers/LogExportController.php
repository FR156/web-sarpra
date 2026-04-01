<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LogExportController extends Controller
{
    public function exportLog(Request $request)
    {
        // 1. Ambil data dengan Eager Loading 'user' agar ringan
        // 2. Limit data (misal 500 terbaru) biar load PDF gak berat kali
        $logs = ActivityLog::with('user')
                    ->latest()
                    ->take(500) 
                    ->get();

        // 3. Masukkan ke array $data. 
        // Nama KEY di sini (kiri) akan jadi nama VARIABEL di Blade (kanan).
        $data = [
            'logs' => $logs, 
        ];

        $pdf = Pdf::loadView('pdf.logs', $data);

        return $pdf->download('log-aktivitas-' . now()->format('Y-m-d') . '.pdf');
    }

}
