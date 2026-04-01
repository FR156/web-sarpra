@extends('pdf.layout')

@section('title', 'Laporan Peminjaman Terfilter')

@section('content')
    <h2 style="text-align: center; text-transform: uppercase;">Laporan Peminjaman Terfilter</h2>

    <div style="margin-bottom: 15px; background: #f9fafb; padding: 10px; border-radius: 5px; border: 1px solid #e5e7eb;">
        <table style="border: none; margin-top: 0;">
            <tr style="border: none;">
                <td style="border: none; padding: 2px; width: 100px;">Periode</td>
                <td style="border: none; padding: 2px; width: 10px;">:</td>
                <td style="border: none; padding: 2px;">
                    <strong>{{ request('start', 'Awal') }}</strong> s/d <strong>{{ request('end', 'Sekarang') }}</strong>
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px;">Total Record</td>
                <td style="border: none; padding: 2px;">:</td>
                <td style="border: none; padding: 2px;">{{ $loans->count() }} Data ditemukan</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th>Kode Peminjaman</th>
                <th>Nama Peminjam</th>
                <th style="text-align: center;">Tgl Pinjam</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loans as $index => $loan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><code style="font-family: monospace;">{{ $loan->loan_code }}</code></td>
                    <td>{{ $loan->user->name ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $loan->created_at->format('d/m/Y') }}</td>
                    <td style="text-align: center;" class="status-{{ $loan->status }}">
                        {{ ucfirst($loan->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #9ca3af;">
                        Tidak ada data peminjaman yang sesuai dengan filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection