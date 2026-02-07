<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')
                    ->description('Manage staff and borrower credentials.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                            
                        Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'staff' => 'Staff',
                                'borrower' => 'Borrower',
                            ])
                            ->required()
                            ->native(false)
                            ->disabled(fn ($record) => $record && $record->id === auth()->id() || $record && $record->id === 1)
                            ->helperText(fn ($record) => $record && $record->id === auth()->id() || $record && $record->id === 1
                                ? 'You can not change your own and super admin role.' 
                                : ''),
                                
                        Toggle::make('is_active')
                            ->label('Akun Aktif')
                            ->default(true)
                            ->disabled(fn ($record) => $record && $record->id === auth()->id() || $record && $record->id === 1)
                            ->helperText(fn ($record) => $record && $record->id === auth()->id() || $record && $record->id === 1
                                ? 'You can not change your own and super admin status.' 
                                : ''),

                        TextInput::make('password')
                            ->password()
                            ->default('sarpra1234')
                            ->helperText('Default password is sarpra1234. Please change after login.')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->required(fn ($record) => $record === null)
                            ->visible(fn ($record) => $record === null),
                    ])->columns(2),
            ]);
    }
}