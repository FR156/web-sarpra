<?php

namespace App\Filament\Resources\ItemUnits;

use App\Filament\Resources\ItemUnits\Pages\CreateItemUnit;
use App\Filament\Resources\ItemUnits\Pages\EditItemUnit;
use App\Filament\Resources\ItemUnits\Pages\ListItemUnits;
use App\Filament\Resources\ItemUnits\Pages\ViewItemUnit;
use App\Filament\Resources\ItemUnits\Schemas\ItemUnitForm;
use App\Filament\Resources\ItemUnits\Schemas\ItemUnitInfolist;
use App\Filament\Resources\ItemUnits\Tables\ItemUnitsTable;
use App\Models\ItemUnit;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemUnitResource extends Resource
{
    protected static ?string $model = ItemUnit::class;

    protected static ?string $navigationLabel = 'Unit';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Barang';

    public static function form(Schema $schema): Schema
    {
        return ItemUnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ItemUnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemUnitsTable::configure($table);
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
            'index' => ListItemUnits::route('/'),
            'create' => CreateItemUnit::route('/create'),
            'view' => ViewItemUnit::route('/{record}'),
            'edit' => EditItemUnit::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
