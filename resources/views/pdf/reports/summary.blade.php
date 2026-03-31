@extends('pdf.layout')

@section('title', 'Laporan Summary Ringkas')

@section('content')
    <h2 style="text-align: center;">RINGKASAN INVENTARIS & PEMINJAMAN</h2>

    <table style="border: none; margin-bottom: 20px;">
        <tr style="border: none;">
            <td style="border: none; padding: 5px;">
                <div class="card">Total Pinjam<div class="value">{{ $loans->count() }}</div></div>
            </td>
            <td style="border: none; padding: 5px;">
                <div class="card">Disetujui<div class="value">{{ $loans->where('status','approved')->count() }}</div></div>
            </td>
            <td style="border: none; padding: 5px;">
                <div class="card">Ditolak<div class="value">{{ $loans->where('status','rejected')->count() }}</div></div>
            </td>
        </tr>
    </table>

    <h2>Daftar Barang Terbaru</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Total Unit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items->take(10) as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category }}</td>
                <td>{{ $item->units_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection