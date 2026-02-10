<?php

namespace App\Filament\Resources\Loans\Tables;

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
                        
                        // Kembalikan semua unit yang terkait jadi available lagi
                        $record->itemUnits()->update(['status' => 'available']);
                    }),
            ]);
    }
}