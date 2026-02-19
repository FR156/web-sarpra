<?php

namespace App\Filament\Resources\Loans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class LoanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Peminjaman')
                ->schema([
                    Select::make('user_id')
                        ->label('Peminjam')
                        ->relationship('user', 'name', fn ($query) => $query->where('role', 'borrower'))
                        ->preload()
                        ->searchable()
                        ->required(),

                    TextInput::make('reason')
                        ->label('Alasan Peminjaman')
                        ->required(),

                    DateTimePicker::make('start_date')->required(),

                    DateTimePicker::make('due_date')->required(),

                    Select::make('item_units')
                        ->label('Barang yang Dipinjam')
                        ->multiple()
                        ->relationship('loanItems.loanItemUnits.itemUnit', 'unit_code', fn ($query) => $query->where('status', 'available')->orderBy('unit_code'))
                        ->preload()
                        ->required(),
                ])->columns(2),
        ]);
    }
}