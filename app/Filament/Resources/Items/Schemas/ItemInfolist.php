<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Models\Item;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('prefix'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('image_path')
                    ->label('Gambar')
                    ->disk('public')
                    // ->visibility('public')
                    // ->getStateUsing(fn ($record) => asset('storage/' . $record->image_path))
                    // ->getStateUsing(fn ($record) => $record->image_path)
                    ->getStateUsing(function ($record) {
                        if ($record->image_path) {
                            return $record->image_path;
                        }

                        return $record->item?->image_path;
                    })
                    ->placeholder('No Image')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                ->dateTime()
                ->placeholder('-'),
                TextEntry::make('updated_at')
                ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Item $record): bool => $record->trashed()),
            ]);
    }
}