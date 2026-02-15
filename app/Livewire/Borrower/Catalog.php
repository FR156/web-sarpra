<?php

namespace App\Livewire\Borrower;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Loan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class Catalog extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedItem = null;
    public $dueDate;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.borrower.catalog', [
            'items' => Item::where('name', 'like', '%' . $this->search . '%')
                ->withCount(['itemUnits' => function ($query) {
                    $query->where('status', 'available');
                }])
                ->paginate(9)
        ]);
    }

    public function selectItem($itemId)
    {
        $this->selectedItem = Item::with(['itemUnits' => function($q) {
            $q->where('status', 'available');
        }])->find($itemId);
    }

    public function requestLoan()
    {
        $this->validate([
            'dueDate' => 'required|after:today',
        ]);

        // Ambil 1 unit yang tersedia secara otomatis
        $unit = $this->selectedItem->itemUnits->first();

        DB::transaction(function () use ($unit) {
            $loan = Loan::create([
                'user_id' => auth()->id(),
                'start_date' => now(), // Default request saat ini
                'due_date' => $this->dueDate,
                'status' => 'pending',
            ]);

            // Hubungkan ke tabel pivot loan_items
            $loan->itemUnits()->attach($unit->id);
            
            // Opsional: Langsung tandai unit jadi 'maintenance' atau status lain 
            // agar tidak dipesan orang lain saat 'pending'
            $unit->update(['status' => 'available']); 
        });

        session()->flash('message', 'Permintaan peminjaman berhasil dikirim!');
        $this->selectedItem = null;
    }

    public $quantity = [];

    // Tambahkan di dalam class Catalog
    public function addToCart($itemId)
    {
        $qty = (int) ($this->quantity[$itemId] ?? 1);

        if ($qty < 1) {
            session()->flash('error', 'Jumlah minimal adalah 1 unit.');
            return;
        }

        $item = Item::find($itemId);
        
        // old code
        // Cek apakah stok unit yang 'available' cukup
        // $availableCount = $item->itemUnits()->where('status', 'available')->count();
        
        // if ($qty > $availableCount) {
        //     session()->flash('error', "Stok tidak mencukupi! Hanya ada $availableCount unit.");
        //     return;
        // }
        
        // Ambil data cart yang sudah ada di session, kalau belum ada set array kosong
        $cart = session()->get('cart', []);

        // $item = Item::find($itemId);

        // Cek apakah barang sudah ada di keranjang
        // if (isset($cart[$itemId])) {
        //     session()->flash('error', 'Barang sudah ada di keranjang!');
        //     return;
        // }

        // // Masukkan data barang ke array cart
        // $cart[$itemId] = [
        //     'id' => $item->id,
        //     'name' => $item->name,
        //     'category' => $item->category->name ?? 'Umum',
        //     'qty' => $qty,
        // ];

        if (isset($cart[$itemId])) {
            $cartQty = $cart[$itemId]['qty'];
            $availableQty = $item->itemUnits()->where('status', 'available')->count();

            // Check if adding the quantity will exceed the available stock
            if (($cartQty + $qty) > $availableQty) {
                session()->flash('error', "Stok tidak mencukupi! Hanya tersedia $availableQty unit.");
                return;
            }
            
            $cart[$itemId]['qty'] += $qty;
        } else {
            $availableQty = $item->itemUnits()->where('status', 'available')->count();

            if ($qty > $availableQty) {
                session()->flash('error', "Stok tidak mencukupi! Hanya tersedia $availableQty unit.");
                return;
            }

            $cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category->name ?? 'Umum',
                'qty' => $qty,
            ];
        }

        // Simpan kembali ke session
        session()->put('cart', $cart);

        // Kirim sinyal (event) agar komponen lain (navbar) tahu ada update
        $this->dispatch('cart-updated'); 

        session()->flash('message', 'Berhasil ditambah ke keranjang!');
    }
}