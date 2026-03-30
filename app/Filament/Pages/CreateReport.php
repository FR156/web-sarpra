<?php

namespace App\Filament\Pages;

use App\Models\Loan;
use App\Models\Item;
use App\Models\ItemUnit;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;
use UnitEnum;
use BackedEnum;

class CreateReport extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan';
    protected string $view = 'filament.pages.create-report';

    public function getViewData(): array
    {
        return [
            'totalLoans' => Loan::count(),
            'approved' => Loan::where('status', 'approved')->count(),
            'rejected' => Loan::where('status', 'rejected')->count(),
            'cancelled' => Loan::where('status', 'cancelled')->count(),

            'totalItems' => Item::count(),
            'totalUnits' => ItemUnit::count(),
            'minorDamage' => ItemUnit::where('condition', 'minor_damage')->count(),
            'majorDamage' => ItemUnit::where('condition', 'major_damage')->count(),
            'lostUnit' => ItemUnit::where('condition', 'lost')->count(),
        ];
    }
}
