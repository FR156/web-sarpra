<?php

namespace App\Filament\Resources\ItemUnits\Pages;

use App\Filament\Resources\ItemUnits\ItemUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListItemUnits extends ListRecords
{
    protected static string $resource = ItemUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
