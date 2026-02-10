<?php

namespace App\Livewire\Borrower;

use App\Models\Loan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Events\ActivityLogged;

#[Layout('layouts.app')]
class MyLoans extends Component
{
    use WithPagination;

    public function render()
    {
        // Ambil peminjaman milik user yang sedang login, urutkan dari yang terbaru
        $loans = Loan::where('user_id', auth()->id())
            ->with(['itemUnits.item']) // Load relasi sampai ke nama barangnya
            ->latest()
            ->paginate(10);

        return view('livewire.borrower.my-loans', [
            'loans' => $loans
        ]);
    }

    public function cancelLoan($loanId)
    {
        $loan = \App\Models\Loan::where('id', $loanId)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($loan) {
            $loan->itemUnits()->update(['status' => 'available']);
            $loan->update(['status' => 'cancelled']);
            
            $record = $loan;
            ActivityLogged::dispatch('cancelled', "Peminjaman dibatalkan borrower #{$record->id}", $record);

            session()->flash('message', 'Peminjaman berhasil dibatalkan.');
        } else {
            session()->flash('error', 'Peminjaman tidak bisa dibatalkan karena sudah diproses.');
        }
    }
}