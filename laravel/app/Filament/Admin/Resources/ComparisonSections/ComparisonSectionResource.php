<?php
//
//namespace App\Filament\Admin\Resources\ComparisonSections;
//
//use App\Filament\Admin\Resources\ComparisonSections\Pages\CreateComparisonSection;
//use App\Filament\Admin\Resources\ComparisonSections\Pages\EditComparisonSection;
//use App\Filament\Admin\Resources\ComparisonSections\Pages\ListComparisonSections;
//use App\Filament\Admin\Resources\ComparisonSections\Schemas\ComparisonSectionForm;
//use App\Filament\Admin\Resources\ComparisonSections\Tables\ComparisonSectionsTable;
//use App\Models\ComparisonSection;
//use BackedEnum;
//use Filament\Resources\Resource;
//use Filament\Schemas\Schema;
//use Filament\Support\Icons\Heroicon;
//use Filament\Tables\Table;
//
//class ComparisonSectionResource extends Resource
//{
//    protected static ?string $model = ComparisonSection::class;
//
//    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
//
//    public static function form(Schema $schema): Schema
//    {
//        return ComparisonSectionForm::configure($schema);
//    }
//
//    public static function table(Table $table): Table
//    {
//        return ComparisonSectionsTable::configure($table);
//    }
//
//    public static function getRelations(): array
//    {
//        return [
//            //
//        ];
//    }
//
//    public static function getPages(): array
//    {
//        return [
//            'index' => ListComparisonSections::route('/'),
//            'create' => CreateComparisonSection::route('/create'),
//            'edit' => EditComparisonSection::route('/{record}/edit'),
//        ];
//    }
//}
