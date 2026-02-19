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

        $cart = session()->get('cart', []);

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