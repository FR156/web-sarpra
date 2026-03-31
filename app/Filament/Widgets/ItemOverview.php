<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\ItemUnit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ItemOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 4;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Barang', Item::count()),
            Stat::make('Total Unit', ItemUnit::count()),
            Stat::make('Unit Bagus', ItemUnit::where('condition', 'good')->count()),
            Stat::make('Unit Rusak Ringan', ItemUnit::where('condition', 'minor_damage')->count()),
            Stat::make('Unit Rusak Berat', ItemUnit::where('condition', 'major_damage')->count()),
            Stat::make('Unit Hilang', ItemUnit::where('condition', 'lost')->count()),
        ];
    }
}
