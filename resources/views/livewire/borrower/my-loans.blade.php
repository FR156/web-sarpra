<div class="py-12">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Peminjaman') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-card-dark overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium mb-6">Daftar Pengajuan Peminjaman</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="">
                            <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-400">ID</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-400">Alasan</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-400">Barang</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-400">Tgl Pinjam</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-400">Tgl Kembali</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($loans as $loan)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300">#{{ $loan->id }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $loan->reason }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <ul class="list-disc list-inside">
                                            @foreach($loan->loanItems as $unit)
                                                <li>{{ $unit->item->name }} ({{ $unit->quantity }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $loan->start_date }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $loan->due_date }}</td>
                                    <td class="px-4 py-4 text-sm text-center">
                                        @php
                                            $statusColor = match($loan->status) {
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-blue-100 text-blue-800',
                                                'on_going' => 'bg-indigo-100 text-indigo-800',
                                                'returned' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                                {{ ucfirst($loan->status) }}
                                            </span>

                                            @if($loan->status === 'pending')
                                                <button 
                                                    wire:click="cancelLoan({{ $loan->id }})"
                                                    wire:confirm="Yakin ingin membatalkan pesanan ini?"
                                                    class="text-xs text-red-600 dark:text-red-700 hover:underline font-semibold">
                                                    Batalkan Request
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500 italic">
                                        Belum ada riwayat peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>