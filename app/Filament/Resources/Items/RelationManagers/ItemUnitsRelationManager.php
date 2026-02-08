<?php

namespace App\Filament\Resources\Items\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema; // Gunakan Schema sesuai versi v4 lo
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ItemUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'itemUnits';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('unit_code')
                    ->label('Kode Unit / No. Aset')
                    ->required()
                    ->unique(ignoreRecord: true) // Supaya kode unit gak kembar
                    ->maxLength(255),

                Select::make('condition')
                    ->label('Kondisi Barang')
                    ->options([
                        'good' => 'Bagus',
                        'minor_damage' => 'Rusak Ringan',
                        'major_damage' => 'Rusak Berat',
                    ])
                    ->default('good')
                    ->required()
                    ->native(false),

                Select::make('status')
                    ->label('Status Ketersediaan')
                    ->options([
                        'available' => 'Tersedia',
                        'on_loan' => 'Dipinjam',
                        'maintenance' => 'Dalam Perbaikan',
                    ])
                    ->default('available')
                    ->required()
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('unit_code')
            ->columns([
                TextColumn::make('unit_code')
                    ->label('Kode Unit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'good' => 'success',
                        'minor_damage' => 'warning',
                        'major_damage' => 'danger',
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'info',
                        'on_loan' => 'warning',
                        'maintenance' => 'danger',
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Unit Baru'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}