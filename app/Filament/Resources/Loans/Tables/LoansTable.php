<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Events\ActivityLogged;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use App\Models\Loan;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')->label('Peminjam')->searchable(),
                TextColumn::make('itemUnits.unit_code')
                    ->label('Daftar Barang')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2) 
                    ->expandableLimitedList(),
                TextColumn::make('computed_status')->label('status')->badge()->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'info',
                        'on_going' => 'warning',
                        'overdue' => 'danger',
                        'returned' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                    }),
                TextColumn::make('start_date')->dateTime()->sortable(),
                TextColumn::make('due_date')->dateTime()->sortable(),
                TextColumn::make('returned_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'approver_id' => auth()->id(),
                        ]);
                        $record->itemUnits()->update(['status' => 'on_loan']);
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
                    ->visible(fn ($record) => $record->status === 'approved' || $record->status === 'on_going')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'returned',
                            'returned_at' => now(),
                        ]);
                        $record->itemUnits()->update(['status' => 'available']);
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