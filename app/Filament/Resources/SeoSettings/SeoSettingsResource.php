<?php

namespace App\Filament\Resources\SeoSettings;

use App\Filament\Resources\SeoSettings\Pages\EditSeoSettings;
use App\Filament\Resources\SeoSettings\Pages\ListSeoSettings;
use App\Filament\Resources\SeoSettings\Schemas\SeoSettingsForm;
use App\Filament\Resources\SeoSettings\Tables\SeoSettingsTable;
use App\Models\BuisnessSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SeoSettingsResource extends Resource
{
    protected static ?string $model = BuisnessSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    public static function form(Schema $schema): Schema
    {
        return SeoSettingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeoSettingsTable::configure($table);
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
            'index' => ListSeoSettings::route('/'),
            'edit' => EditSeoSettings::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('key', 'seo-settings');
    }
}
