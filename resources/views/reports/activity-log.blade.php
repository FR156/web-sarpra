<!DOCTYPE html>
<html>
<head>
    <title>Laporan Sarpra</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .summary-box { border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">LAPORAN AKTIVITAS SARPRAS SEKOLAH</h2>
    <p>Periode: {{ $start }} s/d {{ $end }}</p>

    <div class="summary-box">
        <strong>Ringkasan Aktivitas:</strong><br>
        - Peminjaman Disetujui: {{ $stats['approved'] }}<br>
        - Peminjaman Ditolak: {{ $stats['rejected'] }}<br>
        - Barang Rusak Tercatat: {{ $stats['rusak'] }}<br>
        - Barang Hilang Tercatat: {{ $stats['hilang'] }}
    </div>

    <table class="table">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Waktu</th>
                <th>User (Role)</th>
                <th>Aktivitas</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $log->user->name }} ({{ $log->user->role }})</td>
                <td>{{ strtoupper(str_replace('_', ' ', $log->activity)) }}</td>
                <td>{{ $log->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>