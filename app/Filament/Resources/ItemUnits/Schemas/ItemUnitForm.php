<?php

namespace App\Filament\Resources\ItemUnits\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ItemUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('unit_code')
                    ->disabled(),

                Select::make('condition')
                    ->options([
                        'good' => 'Baik',
                        'minor_damage' => 'Rusak Ringan',
                        'major_damage' => 'Rusak Berat',
                        'lost' => 'Hilang'
                    ]),

                Select::make('status')
                    ->options([
                        'available' => 'Tersedia',
                        'booked' => 'Dipesan',
                        'on_loan' => 'Pinjam',
                        'maintenance' => 'Perbaikan',
                        'unavailable' => 'Tidak Tersedia'
                ])
            ]);
    }
}
