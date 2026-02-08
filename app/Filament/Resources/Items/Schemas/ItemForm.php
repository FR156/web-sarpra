<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Barang')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('category_id')
                            ->relationship('category', 'name') // Otomatis ambil dari model Category
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}