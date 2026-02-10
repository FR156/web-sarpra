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

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Peminjam')->searchable(),
                TextColumn::make('status')->badge()->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'info',
                        'on_going' => 'warning',
                        'returned' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                    }),
                TextColumn::make('start_date')->dateTime()->sortable(),
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
                        ActivityLogged::dispatch('approved', "Peminjaman diterima #{$record->id}", $record);
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
                        ActivityLogged::dispatch('rejected', "Peminjaman ditolak (id:{$record->id})", $record);
                    }),

                // 2. Tombol Mark as Returned
                Action::make('mark_returned')
                    ->label('Kembalikan Barang')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'approved' || $record->status === 'on_going')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'returned',
                            'returned_at' => now(),
                        ]);
                        $record->itemUnits()->update(['status' => 'available']);
                        ActivityLogged::dispatch('returned', "Barang peminjaman telah dikembalikan (id:{$record->id})", $record);
                    }),
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