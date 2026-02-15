<?php

namespace App\Filament\Resources\Items\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema; // Gunakan Schema sesuai versi v4 lo
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ItemUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'itemUnits';
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
Action::make('createBulkUnits')
                ->label('Add Unit')
                ->icon('heroicon-o-plus')
                ->form([
                    TextInput::make('qty')
                        ->label('Jumlah Unit')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required(),
                ])
                ->action(function (array $data, $livewire) {
                    $item = $livewire->getOwnerRecord();

                    for ($i = 0; $i < $data['qty']; $i++) {
                        $item->itemUnits()->create([
                        ]);
                    }
                })
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