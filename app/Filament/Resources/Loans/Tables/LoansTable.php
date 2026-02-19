<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Models\ItemUnit;
use App\Events\ActivityLogged;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->tooltip(fn ($record) => $record->user ? $record->user->email : null)
                    ->searchable(),

                TextColumn::make('reason')
                    ->label('Alasan Peminjaman')
                    ->searchable(),

                TextColumn::make('loanItems.loanItemunits.itemUnit.unit_code')
                    ->label('Daftar Barang')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2) 
                    ->expandableLimitedList(),

                TextColumn::make('status')
                    ->label('status')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'info',
                        'on_going' => 'warning',
                        'overdue' => 'danger',
                        'returned' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                    }),

                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('returned_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                // Action::make('approve')
                //     ->label('Setujui')
                //     ->icon('heroicon-o-check-circle')
                //     ->color('success')
                //     ->visible(fn ($record) => $record->status === 'pending')
                //     ->action(function ($record) {
                //         $record->update([
                //             'status' => 'approved',
                //             'approver_id' => auth()->id(),
                //         ]);
                //         $record->itemUnits()->update(['status' => 'on_loan']);
                //         ActivityLogged::dispatch('approved', "Peminjaman diterima (id peminjaman:{$record->id})", $record);
                //     }),

                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->schema([
                        Radio::make('assign_mode')
                            ->label('Mode Assign Unit')
                            ->options([
                                'auto' => 'Auto (FIFO)',
                                'manual' => 'Manual Pilih Unit',
                            ])
                            ->default('auto')
                            ->required()
                            ->reactive(),

                        Select::make('selected_units')
                            ->label('Pilih Unit')
                            ->multiple()
                            ->options(function ($record) {
                                if (!$record?->loanItems) {
                                    return [];
                                }
                                $itemIds = $record->loanItems->pluck('item_id');
                                return ItemUnit::whereIn('item_id', $itemIds)
                                    ->where('status', 'available')
                                    ->pluck('unit_code', 'id');
                            })
                            ->visible(fn ($record) => $record->assign_mode === 'manual')
                            ->required(fn ($record) => $record->assign_mode === 'manual'),
                    ])
                    ->action(function ($record, array $data) {

                        DB::transaction(function () use ($record, $data) {

                            foreach ($record->loanItems as $loanItem) {

                                if ($data['assign_mode'] === 'auto') {

                                    $units = ItemUnit::where('item_id', $loanItem->item_id)
                                        ->where('status', 'available')
                                        ->orderByRaw('last_used_at IS NULL DESC')
                                        ->orderBy('last_used_at', 'asc')
                                        ->limit($loanItem->quantity)
                                        ->lockForUpdate()
                                        ->get();

                                    if ($units->count() < $loanItem->quantity) {
                                        throw new \Exception('Stok tidak mencukupi.');
                                    }

                                    foreach ($units as $unit) {
                                        $loanItem->loanItemUnits()->create([
                                            'item_unit_id' => $unit->id,
                                        ]);

                                        $unit->update([
                                            'status' => 'on_loan'
                                        ]);
                                    }

                                } else {

                                    foreach ($data['selected_units'] as $unitId) {

                                        $unit = ItemUnit::lockForUpdate()->find($unitId);

                                        if ($unit->item_id != $loanItem->item_id) {
                                            continue;
                                        }

                                        $loanItem->loanItemUnits()->create([
                                            'item_unit_id' => $unit->id,
                                        ]);

                                        $unit->update([
                                            'status' => 'on_loan'
                                        ]);
                                    }
                                }
                            }

                            $record->update([
                                'status' => 'approved',
                                'approver_id' => auth()->id(),
                            ]);
                        });

                        ActivityLogged::dispatch('approved', "Peminjaman diterima (id peminjaman:{$record->id})", $record);
                    }),
                
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'rejected',
                            'approver_id' => auth()->id(),
                        ]);
                        $record->itemUnits()->update(['status' => 'available']);
                        ActivityLogged::dispatch('rejected', "Peminjaman ditolak (id peminjaman:{$record->id})", $record);
                    }),

                Action::make('mark_on_going')
                    ->label('Mulai Peminjaman')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'approved')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'on_going',
                        ]);
                        // $record->itemUnits()->update(['status' => 'on_loan']); // sudah ada dalam action approve
                        ActivityLogged::dispatch('on_going', "Peminjaman dimulai (id peminjaman:{$record->id})", $record);
                    }),

                Action::make('mark_returned')
                    ->label('Kembalikan Barang')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('success')
                    ->schema([
                        TextInput::make('fine_amount')
                            ->label('Denda (Rp)')
                            ->numeric()
                            ->default(0),
                        Select::make('fine_reason')
                            ->label('Alasan Denda')
                            ->options([
                                'damaged' => 'Rusak / Kurang',
                                'late' => 'Terlambat',
                                'other' => 'Lainnya',
                            ])
                    ])
                    ->visible(fn ($record) => $record->status === 'on_going')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'returned',
                            'returned_at' => now(),
                        ]);
                        foreach ($record->loanItems as $loanItem) {
                            foreach ($loanItem->loanItemUnits as $assigned) {
                                $assigned->itemUnit->update([
                                    'status' => 'available',
                                    'last_used_at' => now(),
                                ]);
                            }
                        }

                        ActivityLogged::dispatch('returned', "Barang peminjaman telah dikembalikan (id peminjaman:{$record->id})", $record);
                    }),

                Action::make('fine_status')
                    ->label('Status Denda')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'returned')
                    ->schema([
                        Select::make('fine_status')
                            ->label('Status Denda')
                            ->options([
                                'paid' => 'Lunas',
                                'unpaid' => 'Belum Lunas',
                            ])
                    ])
                    ->action(function ($record) {
                        $record->update([
                            'fine_status' => $record->fine_status,
                        ]);
                        ActivityLogged::dispatch('fine_status', "Status denda peminjaman telah diubah (id peminjaman:{$record->id})", $record);
                    })
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'on_going' => 'On Going',
                        'returned' => 'Returned',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Status'),
            ]);
    }
}