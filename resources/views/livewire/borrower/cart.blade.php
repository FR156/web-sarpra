<div class="py-12">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @if(empty($cart))
                <div class="text-center py-8">
                    <p class="text-gray-500">Keranjang masih kosong.</p>
                    <a href="{{ route('catalog') }}" class="text-indigo-600 underline">Kembali ke Katalog</a>
                </div>
            @else
                <table class="w-full text-left mb-6">
                    <thead>
                        <tr class="border-b text-gray-600">
                            <th class="py-2">Nama Barang</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2 text-center">Jumlah</th> <th class="py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $details)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $details['name'] }}</td>
                                <td class="py-3 text-gray-500">{{ $details['category'] }}</td>
                                <td class="py-3 text-center">
                                    <span class="bg-gray-100 px-3 py-1 rounded-full font-bold">
                                        {{ $details['qty'] ?? 1 }} </span>
                                </td>
                                <td class="py-3 text-right">
                                    <button wire:click="removeFromCart({{ $id }})" class="text-red-500 hover:text-red-700">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="max-w-xs">
                    <div class="grid lg:flex gap-4">
                        <div class="grid">
                            <label class="block text-sm font-medium text-gray-700">Rencana Tanggal Pinjam</label>
                            <input type="date" wire:model="startDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 mb-4">
                        </div>
                        <div class="grid">
                            <label class="block text-sm font-medium text-gray-700">Rencana Tanggal Kembali</label>
                            <input type="date" wire:model="dueDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 mb-4">
                        </div>
                    </div>
                    
                    <button wire:click="submitRequest" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">
                        Ajukan Peminjaman
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>