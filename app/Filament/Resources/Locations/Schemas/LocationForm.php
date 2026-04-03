<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(static::getComponents());
    }

    public static function getComponents(): array
    {
        return [
            Tabs::make('Translations')
                ->tabs([
                    Tab::make('English')
                        ->schema([
                            TextInput::make('en.name')
                                ->label('Name (EN)')
                                ->required()
                                ->maxLength(255),
                        ]),
                    Tab::make('Arabic')
                        ->schema([
                            TextInput::make('ar.name')
                                ->label('Name (AR)')
                                ->required()
                                ->maxLength(255),
                        ]),
                ]),
            Section::make()
                ->components([
                    TextInput::make('price')
                        ->label('Shipping Price')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    Toggle::make('has_discussion_for_delivery')
                        ->label('Open For Discuss Delivery Method')
                        ->required(),
                ]),
        ];
    }
}
