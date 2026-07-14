<?php

namespace App\Filament\Resources\Items;

use App\Filament\Resources\Items\Pages\CreateItems;
use App\Filament\Resources\Items\Pages\EditItems;
use App\Filament\Resources\Items\Pages\ListItems;
use App\Filament\Resources\Items\Schemas\ItemsForm;
use App\Filament\Resources\Items\Tables\ItemsTable;
use App\Models\Items;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ItemsResource extends Resource
{
    protected static ?string $model = Items::class;

    protected static string | UnitEnum | null $navigationGroup = 'Shop';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $recordTitleAttribute = 'Items';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 5 ? 'warning' : 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return ItemsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemsTable::configure($table);
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
            'index' => ListItems::route('/'),
            'create' => CreateItems::route('/create'),
            'edit' => EditItems::route('/{record}/edit'),
        ];
    }
}
