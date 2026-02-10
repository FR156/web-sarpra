<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Notifications\Notification;


class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'staff' => 'warning',
                        'borrower' => 'success',
                    }),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                        'borrower' => 'Borrower',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All User')
                    ->trueLabel('Only Active User')
                    ->falseLabel('Only Inactive User')
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->label('Deactivate')
                    ->modalHeading('Deactivate User')
                    ->modalDescription('Are you sure you want to deactivate this user? The data will not be deleted in the database.')
                    ->modalSubmitActionLabel('Yes, deactivate user')
                    ->action(function ($record) {
                        $record->update(['is_active' => false]);

                        Notification::make()
                            ->title('User Deactivated')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn ($record) => $record->id === auth()->id()|| $record->id === 1),
            ])
            ->toolbarActions([
                ActionGroup::make([
                    BulkAction::make('deactivate_selected')
                        ->label('Deactivate Selected User')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $filteredRecords = $records->filter(function ($record) {
                                return $record->id !== auth()->id() && $record->id !== 1;
                            });

                            $filteredRecords->each->update(['is_active' => false]);
                            $count = $filteredRecords->count();
                            $skipped = $records->count() - $count;
                            Notification::make()
                                ->title('User Deactivated')
                                ->body("{$count} user deactivated." . ($skipped > 0 ? " Your own account is not deactivated." : ""))
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('activate_selected')
                        ->label('Activate Selected User')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->after(fn () => Notification::make()->title('User Activated')->success()->send()),
                ])
                ->label('Bulk Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('gray')
                ->button(),
        ]);
    }
}