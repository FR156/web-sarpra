<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\CreateActivityLog;
use App\Filament\Resources\ActivityLogs\Pages\EditActivityLog;
use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLogs\Pages\ViewActivityLog;
use App\Filament\Resources\ActivityLogs\Schemas\ActivityLogForm;
use App\Filament\Resources\ActivityLogs\Schemas\ActivityLogInfolist;
use App\Filament\Resources\ActivityLogs\Tables\ActivityLogsTable;
use App\Models\ActivityLog;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Components\TextEntry;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-finger-print';
    protected static string|UnitEnum|null $navigationGroup = 'System';

    public static function form(Schema $schema): Schema
    {
        return ActivityLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
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
                \Filament\Actions\ViewAction::make()
                    ->label('Detail')
                    ->modalHeading('Detail Aktivitas')
                    ->modalWidth('lg')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Deskripsi Lengkap')
                            ->prose() // Font lebih enak dibaca untuk teks panjang
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    // Menghilangkan tombol "New"
    public static function canCreate(): bool
    {
        return false;
    }

    // Memastikan tidak bisa diedit meskipun lewat URL manual
    public static function canEdit($record): bool
    {
        return false;
    }

    // Memastikan log tidak bisa dihapus
    public static function canDelete($record): bool
    {
        return false;
    }
}
