<?php

namespace App\Filament\Admin\Resources\HeroSections;

use App\Filament\Admin\Resources\HeroSections\Pages\CreateHeroSection;
use App\Filament\Admin\Resources\HeroSections\Pages\EditHeroSection;
use App\Filament\Admin\Resources\HeroSections\Pages\ListHeroSections;
use App\Filament\Admin\Resources\HeroSections\Schemas\HeroSectionForm;
use App\Filament\Admin\Resources\HeroSections\Tables\HeroSectionsTable;
use App\Models\HeroSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HeroSectionResource extends Resource
{
    protected static ?string $model = HeroSection::class;

    protected static ?string $navigationLabel = 'Hero';

    protected static ?string $modelLabel = 'Hero';

    protected static ?string $pluralModelLabel = 'Hero';

    protected static string|null|\UnitEnum $navigationGroup = 'Контент';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return HeroSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeroSectionsTable::configure($table);
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
            'index' => ListHeroSections::route('/'),
            'create' => CreateHeroSection::route('/create'),
            'edit' => EditHeroSection::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
