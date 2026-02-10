<?php

namespace App\Livewire\Borrower;

use App\Models\Loan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

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
}