<?php

namespace App\Filament\Resources\Loans\Schemas;

use Dom\Text;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Str;

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
                                ->options(\App\Models\Item::pluck('name', 'id'))
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
                                        $available = \App\Models\ItemUnit::where('item_id', $itemId)
                                            ->where('status', 'available')
                                            ->count();
                                        
                                        if ($state > $available) {
                                            $set('quantity', $available);
                                            // Optionally show notification
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
            
            // Section::make('Detail Barang & Unit')
            //     ->schema([
            //         Tabs::make('Loans')
            //             ->tabs([
            //                 Tab::make('Daftar Barang')
            //                     ->schema([
            //                         Repeater::make('loanItems')
            //                             ->relationship()
            //                             ->schema([
            //                                 TextInput::make('item.name')
            //                                     ->label('Nama Barang')
            //                                     ->disabled()
            //                                     ->dehydrated(false),

            //                                 TextInput::make('quantity')
            //                                     ->label('Jumlah')
            //                                     ->disabled()
            //                                     ->dehydrated(false),
            //                             ])
            //                             ->columns(2)
            //                             ->disabled()
            //                             ->dehydrated(false),
            //                     ]),

            //                 Tab::make('Unit yang Dipinjam')
            //                     ->schema([
            //                         Repeater::make('loanItems')
            //                             ->relationship()
            //                             ->schema([
            //                                 TextEntry::make('item_name')
            //                                     ->label('Barang')
            //                                     ->schema(fn ($get, $record) => $record?->item->name),

            //                                 Repeater::make('loanItemUnits')
            //                                     ->relationship()
            //                                     ->schema([
            //                                         TextInput::make('itemUnit.unit_code')
            //                                             ->label('Kode Unit')
            //                                             ->disabled()
            //                                             ->dehydrated(false),

            //                                         TextInput::make('itemUnit.condition')
            //                                             ->label('Kondisi')
            //                                             ->disabled()
            //                                             ->dehydrated(false),
            //                                     ])
            //                                     ->columns(2)
            //                                     ->disabled()
            //                                     ->dehydrated(false)
            //                                     ->label('Unit'),
            //                             ])
            //                             ->disabled()
            //                             ->dehydrated(false),
            //                     ]),
            //             ]),
            //     ]),
        ]);
    }
}