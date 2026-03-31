<?php

namespace App\Filament\Pages;

use App\Models\Loan;
use App\Models\Item;
use App\Models\ItemUnit;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Widgets\LoanOverview;
use App\Filament\Widgets\ItemOverview;
use UnitEnum;
use BackedEnum;

class CreateReport extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan';
    protected string $view = 'filament.pages.create-report';

    // public function getViewData(): array
    // {
    //     return [
    //         'totalLoans' => Loan::count(),
    //         'approved' => Loan::where('status', 'approved')->count(),
    //         'rejected' => Loan::where('status', 'rejected')->count(),
    //         'cancelled' => Loan::where('status', 'cancelled')->count(),

    //         'totalItems' => Item::count(),
    //         'totalUnits' => ItemUnit::count(),
    //         'minorDamage' => ItemUnit::where('condition', 'minor_damage')->count(),
    //         'majorDamage' => ItemUnit::where('condition', 'major_damage')->count(),
    //         'lostUnit' => ItemUnit::where('condition', 'lost')->count(),
    //     ];
    // }

    // Method sakti untuk manggil widget
    protected function getHeaderWidgets(): array
    {
        return [
            LoanOverview::class,
            ItemOverview::class,
        ];
    }
    
    // Opsional: Atur jumlah kolom widget (default 2)
    public function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }
}
