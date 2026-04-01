@extends('pdf.layout')

@section('title', 'System Activity Log')

@section('content')
    <h2 style="text-align: center; text-transform: uppercase;">Log Aktivitas Sistem</h2>

    <table style="font-size: 10px;"> <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 80px;">User</th>
                <th style="width: 70px; text-align: center;">Aksi</th>
                <th style="width: 80px;">Model</th>
                <th>Keterangan</th>
                <th style="width: 90px;">IP Address</th>
                <th style="width: 100px;">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $index => $log)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $log->user->name ?? 'System' }}</strong><br>
                        <span class="text-muted">ID: {{ $log->user_id ?? '-' }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span style="
                            padding: 2px 5px; 
                            border-radius: 3px; 
                            background: {{ $log->action == 'delete' ? '#fee2e2' : ($log->action == 'create' ? '#dcfce7' : '#fef9c3') }};
                            color: {{ $log->action == 'delete' ? '#991b1b' : ($log->action == 'create' ? '#166534' : '#854d0e') }};
                            font-weight: bold;
                            font-size: 9px;
                        ">
                            {{ strtoupper($log->action) }}
                        </span>
                    </td>
                    <td>
                        {{-- Membersihkan Model Name (contoh: App\Models\Loan jadi Loan) --}}
                        {{ class_basename($log->model_type) }}
                    </td>
                    <td>
                        {{ $log->description }}
                        @if($log->model_id)
                            <span class="text-muted">(ID: {{ $log->model_id }})</span>
                        @endif
                    </td>
                    <td style="font-family: monospace;">{{ $log->ip_address }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada log aktivitas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection