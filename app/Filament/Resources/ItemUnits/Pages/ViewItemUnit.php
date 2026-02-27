<?php

namespace App\Filament\Resources\ItemUnits\Pages;

use App\Filament\Resources\ItemUnits\ItemUnitResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewItemUnit extends ViewRecord
{
    protected static string $resource = ItemUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
