<?php

namespace App\Filament\Resources\Items\Tables;

use App\Filament\Resources\Items\ItemResource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;

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

                TextColumn::make('item_units_count')
                    ->counts('itemUnits', fn ($query) => $query->where('status', 'available'))
                    ->label('Stok Tersedia')
                    ->suffix(' Unit'),    
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->recordUrl(fn ($record) => ItemResource::getUrl('view', ['record' => $record]));
    }
}