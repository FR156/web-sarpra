<?php

namespace App\Livewire\Borrower;

use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\LoanItemUnit;
use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Events\ActivityLogged;

#[Layout('layouts.app')]
class Cart extends Component
{
    public $cart = [];
    public $reason;
    public $startDate;
    public $dueDate;

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    public function incrementQty($itemId)
    {
        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['qty']++;
            session()->put('cart', $this->cart);
            $this->dispatch('cart-updated');
        }
    }

    public function decrementQty($itemId)
    {
        if (isset($this->cart[$itemId])) {

            if ($this->cart[$itemId]['qty'] > 1) {
                $this->cart[$itemId]['qty']--;
            } else {
                unset($this->cart[$itemId]); // kalau 1 dikurang jadi hapus
            }

            session()->put('cart', $this->cart);
            $this->dispatch('cart-updated');
        }
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
            'reason' => 'required|string|min:5',
            'startDate' => 'required|date|after_or_equal:today',
            'dueDate' => 'required|date|after:startDate',
        ], [
            'reason.required' => 'Alasan wajib diisi.',
            'reason.min' => 'Alasan minimal 5 karakter.',
            'startDate.required' => 'Tanggal mulai wajib diisi.',
            'dueDate.required' => 'Tanggal kembali wajib diisi.',
            'startDate.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'dueDate.after' => 'Tanggal dan waktu kembali harus setelah tanggal mulai.',
        ]);

        if (empty($this->cart)) return;

        DB::transaction(function () {
            // Buat satu record Loan (satu grup peminjaman)
            $loan = Loan::create([
                'user_id' => auth()->id(),
                'reason' => $this->reason,
                'start_date' => $this->startDate,
                'due_date' => $this->dueDate,
                'status' => 'pending',
            ]);

            foreach ($this->cart as $cartItem) {
                $loan->loanItems()->create([
                    'item_id' => $cartItem['id'],
                    'quantity' => $cartItem['qty'],
                ]);
            }
            
            $record = $loan;
            ActivityLogged::dispatch('request', "Membuat permintaan peminjaman (id peminjaman:{$record->id})", $record);
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