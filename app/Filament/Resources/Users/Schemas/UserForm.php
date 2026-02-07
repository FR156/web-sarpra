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
                Section::make('Informasi Akun')
                    ->description('Kelola kredensial petugas dan peminjam.')
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
                                'staff' => 'Petugas (Staff)',
                                'borrower' => 'Peminjam (Borrower)',
                            ])
                            ->required()
                            ->native(false)
                            ->disabled(fn ($record) => $record && $record->id === auth()->id())
                            ->helperText(fn ($record) => $record && $record->id === auth()->id() 
                                ? 'Anda tidak dapat mengubah role akun Anda sendiri demi keamanan.' 
                                : ''),
                                
                        Toggle::make('is_active')
                            ->label('Akun Aktif')
                            ->default(true)
                            ->disabled(fn ($record) => $record && $record->id === auth()->id())
                            ->helperText(fn ($record) => $record && $record->id === auth()->id() 
                                ? 'Anda tidak dapat mengubah status akun Anda sendiri demi keamanan.' 
                                : ''),

                        TextInput::make('password')
                            ->password()
                            ->default('sarpra1234')
                            ->helperText('Password default adalah: sarpra1234. Harap segera diganti.')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->required(fn ($record) => $record === null) // Wajib hanya saat create
                            ->visible(fn ($record) => $record === null) // Hanya muncul saat create
                            ->confirmed(),
                    ])->columns(2),
            ]);
    }
}