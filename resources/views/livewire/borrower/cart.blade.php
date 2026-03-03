<div class="py-12">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-card-dark overflow-hidden shadow-sm sm:rounded-lg p-6">
            @if(empty($cart))
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Keranjang masih kosong.</p>
                    <a href="{{ route('catalog') }}" class="text-indigo-600 dark:text-indigo-400 underline">Kembali ke Katalog</a>
                </div>
            @else
                <table class="w-full text-left mb-6">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                            <th class="py-2">Nama Barang</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2 text-center">Jumlah</th> 
                            <th class="py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $details)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-3 text-gray-500 dark:text-gray-400 font-medium max-w-4">{{ $details['name'] }}</td>
                                <td class="py-3 text-gray-500 dark:text-gray-400">{{ $details['category'] }}</td>
                                <td class="py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="decrementQty({{ $id }})" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded">
                                            -
                                        </button>

                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded font-bold">
                                            {{ $details['qty'] ?? 1 }}
                                        </span>

                                        <button wire:click="incrementQty({{ $id }})" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded">
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 text-right">
                                    <button wire:click="removeFromCart({{ $id }})" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-600">
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Alasan Meminjam</label>
                            <input type="text" wire:model="reason" class="mt-1 block w-sm lg:w-lg bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-50 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-indigo-500 mb-4">
                            
                            @error('reason')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Rencana Tanggal Pinjam</label>
                            <input type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}" wire:model="startDate" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-50 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-indigo-500 mb-4">
                            
                            @error('startDate')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Rencana Tanggal Kembali</label>
                            <input type="datetime-local" min="{{ $startDate }}" wire:model="dueDate" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-50 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-indigo-500 mb-4">

                            @error('dueDate')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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