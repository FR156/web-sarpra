<?php

namespace App\Filament\Resources\Items\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

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
                        'available' => 'success',
                        'booked' => 'info',
                        'on_loan' => 'warning',
                        'maintenance' => 'danger',
                        'unavailable' => 'danger',
                    }),
            ])
            ->headerActions([
                Action::make('createBulkUnits')
                    ->label('Add Unit')
                    ->icon('heroicon-o-plus')
                    ->schema([
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
                EditAction::make()
                    ->schema([
                        TextInput::make('unit_code')
                            ->label('Kode Unit')
                            ->disabled(),

                        Select::make('condition')
                            ->options([
                                'good' => 'Good',
                                'minor_damage' => 'Minor Damage',
                                'major_damage' => 'Major Damage',
                            ])
                            ->required(),

                        Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'booked' => 'Booked',
                                'on_loan' => 'On Loan',
                                'maintenance' => 'Maintenance',
                                'unavailable' => 'Unavailable',
                            ])
                            ->required(),
                    ]),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}