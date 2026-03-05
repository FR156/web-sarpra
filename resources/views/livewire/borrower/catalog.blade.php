<div class="py-12 z-0">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Katalog Barang') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="text-2xl font-semibold dark:text-gray-200 text-gray-800">
                    {{ __('Katalog Inventaris') }}
                </h2>
                <div class="w-full md:w-1/3">
                    <input wire:model.live="search" type="text" 
                        placeholder="Cari barang..." 
                        class="w-full dark:bg-card-dark border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>
            </div>
        </div>
        
        @if (session()->has('error'))
            <div id="error-message" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative z-0">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (session()->has('message'))
            <div id="success-message" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative z-0">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($items as $item)
                <div class="bg-white dark:bg-card-dark overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 flex flex-col">
                    {{-- Image --}}
                    <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        @if($item->image_path)
                            <img 
                                src="{{ asset('storage/' . $item->image_path) }}" 
                                alt="{{ $item->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400 text-sm">
                                No Image
                            </div>
                        @endif
                    </div>

                    <div class="p-6 flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                {{ $item->category->name ?? 'Umum' }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-200 mb-2">{{ $item->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">{{ $item->description }}</p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div class="text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Tersedia:</span>
                            <span class="font-semibold {{ $item->item_units_count > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $item->item_units_count }} Unit
                            </span>
                        </div>
                        
                        @if($item->item_units_count > 0)
                            <div class="flex items-center gap-2">
                                <input type="number" wire:model="quantity.{{ $item->id }}" min="1" 
                                    class="w-16 bg-white dark:bg-gray-800 dark:text-gray-100 border-gray-300 dark:border-gray-700 rounded-md text-sm" placeholder="1">
                                <button wire:click="addToCart({{ $item->id }})" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-800 dark:hover:bg-indigo-900 text-white px-4 py-2 rounded-md">
                                    + Keranjang
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white p-12 text-center shadow-sm sm:rounded-lg">
                    <p class="text-gray-500 italic">Barang tidak ditemukan atau stok kosong.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $items->links() }}
        </div>
    </div>

    @if($selectedItem)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('selectedItem', null)"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Ajukan Peminjaman: {{ $selectedItem->name }}
                        </h3>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengembalian</label>
                            <input type="date" wire:model="dueDate" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @error('dueDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="requestLoan" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Kirim Request
                        </button>
                        <button wire:click="$set('selectedItem', null)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>