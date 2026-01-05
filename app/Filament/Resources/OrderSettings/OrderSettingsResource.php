<?php

namespace App\Filament\Resources\OrderSettings;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\OrderSettings;
use App\Models\BuisnessSetting;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderSettings\Pages\EditOrderSettings;
use App\Filament\Resources\OrderSettings\Pages\ListOrderSettings;
use App\Filament\Resources\OrderSettings\Pages\CreateOrderSettings;
use App\Filament\Resources\OrderSettings\Schemas\OrderSettingsForm;
use App\Filament\Resources\OrderSettings\Tables\OrderSettingsTable;

class OrderSettingsResource extends Resource
{
    protected static ?string $model = BuisnessSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrderSettingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderSettingsTable::configure($table);
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
            'index' => ListOrderSettings::route('/'),
            'create' => CreateOrderSettings::route('/create'),
            'edit' => EditOrderSettings::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('key', 'order_settings');
    }
}
