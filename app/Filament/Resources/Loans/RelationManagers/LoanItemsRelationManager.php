<?php

namespace App\Filament\Resources\Loans\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;

class LoanItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'loanItems';

    protected static ?string $title = 'Barang yang Dipinjam';

    protected static ?string $recordTitleAttribute = 'item.name';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item.name')
            ->columns([
                TextColumn::make('item.name')
                    ->label('Barang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->badge()
                    ->sortable(),

                TextColumn::make('loanItems.loanItemunits.itemUnit.unit_code')
                    ->label('Daftar Unit')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->toggleable(isToggledHiddenByDefault: true) 
                    ->expandableLimitedList()
                    ->toggleable(),
            ])
            ->filters([])
            ->headerActions([
                // CreateAction::make()
                //     ->visible(fn ($livewire) => $livewire->getOwnerRecord()->status === 'pending'),
            ])
            ->recordActions([
                // EditAction::make()
                //     ->visible(fn ($record, $livewire) => $livewire->getOwnerRecord()->status === 'pending'),
                    
                // DeleteAction::make()
                //     ->visible(fn ($record, $livewire) => $livewire->getOwnerRecord()->status === 'pending'),
                    
                // // Action untuk manage units
                // Action::make('manageUnits')
                //     ->label('Manage Unit')
                //     ->icon('heroicon-o-cube')
                //     ->color('success')
                //     ->visible(fn ($record, $livewire) => in_array($livewire->getOwnerRecord()->status, ['approved', 'on_going']))
                //     ->modalHeading('Manage Unit untuk ' . fn ($record) => $record->item->name)
                //     ->modalWidth('lg')
                //     ->schema([
                //         Repeater::make('loanItemUnits')
                //             ->label('Unit')
                //             ->relationship()
                //             ->schema([
                //                 Select::make('item_unit_id')
                //                     ->label('Pilih Unit')
                //                     ->options(function ($record, $get, $livewire) {
                //                         $loanItem = $record ?? $get('../../record');
                //                         return \App\Models\ItemUnit::where('item_id', $loanItem->item_id)
                //                             ->whereIn('status', ['available', 'on_loan'])
                //                             ->pluck('unit_code', 'id');
                //                     })
                //                     ->required()
                //                     ->reactive()
                //                     ->searchable(),
                //             ])
                //             ->columns(1)
                //             ->minItems(fn ($record) => $record->quantity)
                //             ->maxItems(fn ($record) => $record->quantity)
                //             ->addActionLabel('Tambah Unit')
                //             ->reorderable(false)
                //             ->mutateRelationshipDataBeforeCreateUsing(function (array $data, $livewire) {
                //                 // Update status unit menjadi on_loan
                //                 \App\Models\ItemUnit::whereIn('id', [$data['item_unit_id']])
                //                     ->update(['status' => 'on_loan']);
                //                 return $data;
                //             })
                //             ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $record, $livewire) {
                //                 if ($record && $record->item_unit_id != $data['item_unit_id']) {
                //                     // Kembalikan status unit lama
                //                     \App\Models\ItemUnit::where('id', $record->item_unit_id)
                //                         ->update(['status' => 'available']);
                                    
                //                     // Set status unit baru
                //                     \App\Models\ItemUnit::where('id', $data['item_unit_id'])
                //                         ->update(['status' => 'on_loan']);
                //                 }
                //                 return $data;
                //             }),
                //     ])
                //     ->action(function (array $data, $record) {
                //         // Data sudah dihandle oleh mutateRelationshipData
                //     }),
                ]);
    }
}