<x-filament-panels::page>
    <x-filament::section>
        <h2>Ekspor data peminjaman dalam rentang waktu tertentu</h2>
        <form method="POST" action="{{ route('report.filtered') }}" class="space-y-4">
            @csrf
    
            <div class="flex gap-2">
                <input type="date" name="start">
                <input type="date" name="end">
            </div>
    
            <select name="status">
                <option value="">All Status</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="cancelled">Cancelled</option>
            </select>
    
            <input type="number" name="limit" placeholder="Max records (e.g. 10)">
    
            <x-filament::button type="submit">
                Export
            </x-filament::button>
        </form>
    </x-filament::section>

    <form method="POST" action="{{ route('report.summary') }}">
        @csrf
        <x-filament::button type="submit">
            Export Summary
        </x-filament::button>
    </form>
</x-filament-panels::page>
