<?php

namespace App\Filament\Resources\Loans\Schemas;

use App\Models\Loan;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LoanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('loan_code')
                    ->label('Kode Peminjaman'),

                TextEntry::make('user.name')
                    ->label('Nama Peminjam'),

                TextEntry::make('user.email')
                    ->label('Email Peminjam'),

                TextEntry::make('reason')
                    ->label('Alasan Peminjaman'),

                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'info',
                        'on_going' => 'warning',
                        'overdue' => 'danger',
                        'returned' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                    }),

                TextEntry::make('start_date')
                    ->label('Tanggal Mulai'),

                TextEntry::make('due_date')
                    ->label('Tanggal Selesai'),

                TextEntry::make('returned_at')
                    ->label('Tanggal Pengembalian')
                    ->placeholder('-'),

                TextEntry::make('fine_amount')
                    ->label('Denda')
                    ->placeholder('-'),

                TextEntry::make('fine_status')
                    ->label('Status Denda')
                    ->placeholder('-'),

                TextEntry::make('loanItems')
                    ->label('Daftar Barang')
                    ->state(function ($record) {
                        return $record->loanItems->map(function ($loanItem) {
                            return $loanItem->item->name . ' (' . $loanItem->quantity . ' unit)';
                        })->toArray();
                    })
                    ->listWithLineBreaks()
                    ->bulleted(),

                TextEntry::make('loanItems.loanItemunits.itemUnit.unit_code')
                    ->label('Daftar Unit')
                    ->placeholder('-')
                    ->listWithLineBreaks()
                    ->bulleted(),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                // TextEntry::make('deleted_at')
                //     ->dateTime()
                //     ->visible(fn (Loan $record): bool => $record->trashed()),
            ]);
    }
}