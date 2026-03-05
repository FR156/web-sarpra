<?php

namespace App\Filament\Resources\ItemUnits\Schemas;

use App\Models\ItemUnit;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemUnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('unit_code')
                    ->label('Kode Unit'),
                TextEntry::make('item.name')
                    ->label('Nama Barang'),
                TextEntry::make('condition')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'good' => 'success',
                        'minor_damage' => 'warning',
                        'major_damage' => 'danger',
                        'lost' => 'danger',
                    }),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'booked' => 'info',
                        'on_loan' => 'warning',
                        'maintenance' => 'danger',
                        'unavailable' => 'danger',
                    }),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ItemUnit $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}