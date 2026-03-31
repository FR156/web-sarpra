<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Laporan')</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; margin: 0; padding: 0; }
        
        /* Kop Surat Styles */
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; width: 100%; }
        .kop-logo { width: 80px; text-align: center; }
        .kop-text { text-align: center; }
        .kop-text h1 { font-size: 18px; margin: 0; text-transform: uppercase; }
        .kop-text p { margin: 2px 0; font-size: 11px; }

        /* Typography & Tables */
        h2 { font-size: 15px; margin-top: 20px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
        .text-muted { color: #6b7280; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f3f4f6; text-align: left; padding: 8px; font-size: 11px; border: 1px solid #e5e7eb; }
        td { padding: 8px; border: 1px solid #e5e7eb; vertical-align: top; }

        /* Utility */
        .card { border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; text-align: center; }
        .value { font-size: 16px; font-weight: bold; margin-top: 4px; }
        .status-approved { color: #16a34a; }
        .status-rejected { color: #dc2626; }
        
        @page { margin: 1cm; }
    </style>
</head>
<body>
    <table class="kop-surat" style="border: none;">
        <tr style="border: none;">
            <td class="kop-logo" style="border: none;">
                {{-- Gunakan public_path() agar dompdf bisa baca filenya --}}
                <img src="{{ public_path('images/logo-sekolah.png') }}" width="80">
            </td>
            <td class="kop-text" style="border: none;">
                <h1>PEMERINTAH PROVINSI ANU</h1>
                <h1>SMK NEGERI CONTOH</h1>
                <p>Jl. Teknik Komputer No. 404, Kota Digital</p>
                <p>Email: admin@smkcontoh.sch.id | Telp: (021) 123456</p>
            </td>
        </tr>
    </table>

    <div style="text-align: right;">
        <p class="text-muted">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

    @yield('content')

    <div style="margin-top: 30px; float: right; width: 200px; text-align: center;">
        <p>Mengetahui,</p>
        <p style="margin-top: 60px;"><strong>Kepala Sarana Prasarana</strong></p>
        <p>NIP. 19820301XXXXXXXX</p>
    </div>
</body>
</html>