<div class="py-12">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Peminjaman') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-medium mb-6">Daftar Pengajuan Peminjaman</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">ID</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">Barang</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">Tgl Pinjam</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">Tgl Kembali</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($loans as $loan)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-500">#{{ $loan->id }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <ul class="list-disc list-inside">
                                            @foreach($loan->itemUnits as $unit)
                                                <li>{{ $unit->item->name }} ({{ $unit->unit_code }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $loan->start_date }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $loan->due_date }}</td>
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
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                                        </span>
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