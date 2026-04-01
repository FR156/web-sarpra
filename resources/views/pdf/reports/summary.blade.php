@extends('pdf.layout')

@section('title', 'Laporan Ringkas')

@section('content')
    <h2 style="text-align: center;">RINGKASAN INVENTARIS & PEMINJAMAN</h2>

    <!-- LOAN SUMMARY -->
    <h2>Laporan Ringkas</h2>

    <table class="grid">
        <tr>
            <td>
                <div class="card">
                    Total Peminjaman
                    <div class="value">{{ $loans->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Selesai
                    <div class="value">{{ $loans->where('status','returned')->count() }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="card">
                    Ditolak
                    <div class="value">{{ $loans->where('status','rejected')->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Dibatalkan
                    <div class="value">{{ $loans->where('status','cancelled')->count() }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- ITEM SUMMARY -->
    <h2>Laporan Inventaris</h2>

    <table class="grid">
        <tr>
            <td>
                <div class="card">
                    Total Barang
                    <div class="value">{{ $items->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Total Unit
                    <div class="value">{{ $itemUnits->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Unit Bagus
                    <div class="value">{{ $itemUnits->where('condition','good')->count() }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="card">
                    Unit Rusak Ringan
                    <div class="value">{{ $itemUnits->where('condition', 'minor_damage')->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Unit Rusak Berat
                    <div class="value">{{ $itemUnits->where('condition', 'major_damage')->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Unit Hilang
                    <div class="value">{{ $itemUnits->where('condition','lost')->count() }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DETAIL TABLE -->
    <h2>Peminjaman Terbaru</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Peminjaman</th>
                <th>Nama Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans->take(10) as $index => $loan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $loan->loan_code }}</td>
                    <td>{{ $loan->user->name ?? '-' }}</td>
                    <td class="status-{{ $loan->status }}">
                        {{ ucfirst($loan->status) }}
                    </td>
                    <td>{{ $loan->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection