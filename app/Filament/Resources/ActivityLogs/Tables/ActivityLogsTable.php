<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Components\TextEntry;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                    
                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'request' => 'warning',
                        'approved' => 'info',
                        'rejected', 'cancelled' => 'danger',
                        'returned' => 'gray',
                        'added' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'login' => 'success',
                        'logout' => 'warning',
                        'login_failed' => 'danger',
                        default => 'indigo',
                    }),

                TextColumn::make('description')
                    ->label('Detail Aktivitas')
                    ->searchable()
                    ->visibleFrom('md'),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->options([
                        'created' => 'Created',
                        'request' => 'Requested',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'returned' => 'Returned',
                        'cancelled' => 'Cancelled',
                        'added' => 'Added',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                        'login' => 'Login',
                        'logout' => 'Logout',
                        'login_failed' => 'Login Failed',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->label('Detail')
                    ->modalHeading('Detail Aktivitas')
                    ->modalWidth('lg')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Deskripsi Lengkap')
                            ->prose()
                            ->columnSpanFull(),
                    ]),
            ])
            ->headerActions([
                //
            ]);
    }
}
