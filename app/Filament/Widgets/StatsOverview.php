<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use App\Models\ItemUnit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Peminjaman Baru', Loan::where('status', 'pending')->count())
                ->description('Perlu segera di-approve')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Barang Sedang Dipinjam', Loan::whereIn('status', ['approved', 'on_going'])->count())
                ->description('Total transaksi aktif')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),

            Stat::make('Stok Tersedia', ItemUnit::where('status', 'available')->count())
                ->description('Unit siap dipinjam')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}