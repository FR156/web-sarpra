<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LoanOverview extends BaseWidget
{
    protected static ?int $sort = 3;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Peminjaman', Loan::count()),
            Stat::make('Pinjaman disetujui', Loan::where('status', 'approved')->count()),
            Stat::make('Pinjaman ditolak', Loan::where('status', 'rejected')->count()),
            Stat::make('Dibatalkan user', Loan::where('status', 'cancelled')->count()),
        ];
    }
}