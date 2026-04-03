<?php

namespace App\Filament\Resources\Locations\Pages;

use App\Filament\Resources\Locations\LocationResource;
use App\Filament\Resources\Locations\Schemas\LocationForm;
use App\Models\Traits\HandleTranslationsTrait;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ListRecords;

class ListLocations extends ListRecords
{
    use HandleTranslationsTrait;

    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false)
                ->schema(fn () => LocationForm::getComponents())
                ->mutateFormDataUsing($this->mutateTranslations())
                ->after(fn ($record) => $this->saveTranslations($record)),
        ];
    }

    public static function editAction(): EditAction
    {
        return EditAction::make()
            ->schema(fn () => LocationForm::getComponents())
            ->fillForm(fn (array $data, $record) => static::fillTranslations($data, $record))
            ->mutateFormDataUsing(fn (array $data) => static::extractTranslations($data)['data'])
            ->after(fn ($record, array $data) => $record->translateOrNew('en') && static::saveEditTranslations($record, $data));
    }
}
