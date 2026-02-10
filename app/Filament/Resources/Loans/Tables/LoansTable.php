<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Events\ActivityLogged;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use App\Models\Loan;
use App\Models\User;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Peminjam')->sortable(),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'info',
                        'on_going' => 'warning',
                        'returned' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                    }),
                TextColumn::make('start_date')->dateTime(),
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
                        ActivityLogged::dispatch('approved', "Peminjaman diterima oleh staff #{$record->id}", $record);
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
                        ActivityLogged::dispatch('rejected', "Peminjaman ditolak oleh staff #{$record->id}", $record);
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
                        ActivityLogged::dispatch('returned', "Peminjaman barang dikembalikan oleh borrower #{$record->id}", $record);
                    }),
            ]);
    }
}