<?php

namespace App\Filament\Resources\Loans\Schemas;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\LoanItem;
use Dom\Text;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
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

                    TextInput::make('loan_code')
                        ->label('Kode Peminjaman')
                        ->disabled(),

                    TextInput::make('reason')
                        ->label('Alasan Peminjaman')
                        ->required(),

                    DateTimePicker::make('start_date')
                        ->required()
                        ->native(false),

                    DateTimePicker::make('due_date')
                        ->required()
                        ->native(false)
                        ->after('start_date'),
                ])->columns(2),

            Section::make('Barang yang Dipinjam')
                ->schema([
                    Repeater::make('loanItems')
                        ->label('')
                        ->relationship()
                        ->schema([
                            Select::make('item_id')
                                ->label('Pilih Barang')
                                ->options(Item::pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $set) => $set('quantity', null)),

                            TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $itemId = $get('item_id');
                                    if ($itemId) {
                                        $available = ItemUnit::where('item_id', $itemId)
                                            ->where('status', 'available')
                                            ->count();
                                        
                                        if ($state > $available) {
                                            $set('quantity', $available);
                                        }
                                    }
                                }),

                            // Hidden field untuk menyimpan unit yang dipilih nantinya
                            Hidden::make('selected_units'),
                        ])
                        ->columns(2)
                        ->minItems(1)
                        ->maxItems(10)
                        ->defaultItems(1)
                        ->addActionLabel('Tambah Barang')
                        ->reorderable(false)
                        ->collapsible(),
                ]),
        ]);
    }
}