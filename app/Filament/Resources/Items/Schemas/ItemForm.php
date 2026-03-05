<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Forms\Components\FileUpload;
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

                        TextInput::make('prefix')
                            ->label('Kode Prefix')
                            ->required(),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                            
                        Textarea::make('description')
                            ->columnSpanFull(),

                        FileUpload::make('image_path')
                            ->image()
                            ->label('Gambar Barang')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor(),
                    ])->columns(2),
            ]);
    }
}