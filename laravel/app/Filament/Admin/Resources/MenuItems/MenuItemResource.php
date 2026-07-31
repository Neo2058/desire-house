<?php

namespace App\Filament\Admin\Resources\MenuItems;

use App\Filament\Admin\Resources\MenuItems\Pages\CreateMenuItem;
use App\Filament\Admin\Resources\MenuItems\Pages\EditMenuItem;
use App\Filament\Admin\Resources\MenuItems\Pages\ListMenuItems;
use App\Filament\Admin\Resources\MenuItems\Schemas\MenuItemForm;
use App\Filament\Admin\Resources\MenuItems\Tables\MenuItemsTable;
use App\Models\MenuItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Настройки';

    protected static ?string $navigationLabel = 'Меню';

    protected static ?string $modelLabel = 'Пункт меню';

    protected static ?string $pluralModelLabel = 'Меню';

    protected static ?int $navigationSort = 4;

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-bars-3';

    public static function form(Schema $schema): Schema
    {
        return MenuItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenuItemsTable::configure($table);
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
            'index' => ListMenuItems::route('/'),
            'create' => CreateMenuItem::route('/create'),
            'edit' => EditMenuItem::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('sort');
    }
}
