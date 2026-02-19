<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class RecentLoans extends BaseWidget
{
    // Mengatur urutan agar tabel muncul di bawah statistik
    protected static ?int $sort = 2;

    // Membuat widget ini memakan lebar penuh
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Loan::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('id')->label('ID'),

                TextColumn::make('user.name')->label('Peminjam'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'returned' => 'success',
                        'rejected', 'cancelled' => 'danger',
                        default => 'gray',
                    }),
                    
                TextColumn::make('created_at')->label('Waktu Request')->dateTime(),
            ]);
    }
}