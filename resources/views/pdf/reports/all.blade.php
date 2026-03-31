@extends('pdf.layout')

@section('title', 'Laporan Seluruh Data Peminjaman')

@section('content')
    <h2 style="text-align: center;">LAPORAN DATA PEMINJAMAN LENGKAP</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $index => $loan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $loan->loan_code }}</td>
                    <td>{{ $loan->user->name }}</td>
                    <td>{{ $loan->item->name }}</td>
                    <td>{{ $loan->created_at->format('d/m/Y') }}</td>
                    <td class="status-{{ $loan->status }}">{{ ucfirst($loan->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection