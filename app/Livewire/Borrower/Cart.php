<?php

namespace App\Livewire\Borrower;

use App\Models\Loan;
use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Events\ActivityLogged;

#[Layout('layouts.app')]
class Cart extends Component
{
    public $cart = [];
    public $dueDate;

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    public function removeFromCart($itemId)
    {
        unset($this->cart[$itemId]);
        session()->put('cart', $this->cart);
        $this->dispatch('cart-updated');
    }

    public function submitRequest()
    {
        $this->validate([
            'dueDate' => 'required|after:today',
        ]);

        if (empty($this->cart)) return;

        DB::transaction(function () {
            // Buat satu record Loan (satu grup peminjaman)
            $loan = Loan::create([
                'user_id' => auth()->id(),
                'start_date' => now(),
                'due_date' => $this->dueDate,
                'status' => 'pending',
            ]);

            // Loop setiap barang di cart untuk dihubungkan ke loan
            foreach ($this->cart as $cartItem) {
                $item = Item::find($cartItem['id']);
                // Ambil satu unit yang tersedia
                $units = $item->itemUnits()
                    ->where('status', 'available')
                    ->limit($cartItem['qty'] ?? 1)
                    ->get();
                    
                foreach ($units as $unit) {
                    $loan->itemUnits()->attach($unit->id);
                    // tandai unit agar tidak dipinjam orang lain (pending)
                    $unit->update(['status' => 'on_loan']);
                }
            }
            
            $record = $loan;
            ActivityLogged::dispatch('request', "Membuat permintaan peminjaman (id:{$record->id})", $record);
        });

        session()->forget('cart');
        session()->flash('message', 'Semua permintaan peminjaman berhasil dikirim!');
        return redirect()->to('/my-loans');
    }

    public function render()
    {
        return view('livewire.borrower.cart');
    }
}