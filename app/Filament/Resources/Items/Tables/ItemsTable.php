<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->badge()
                    ->color('gray'),
                // Menampilkan jumlah unit yang available
                TextColumn::make('item_units_count')
                    ->counts('itemUnits', fn ($query) => $query->where('status', 'available'))
                    ->label('Stok Tersedia')
                    ->suffix(' Unit'),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ]);
    }
}