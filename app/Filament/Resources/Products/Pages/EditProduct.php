<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected array $translations = [];

    protected array $saleData = [];

     protected array $trialData = [];
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }


              protected function mutateFormDataBeforeFill(array $data): array
    {
       
        // Load translations into form
        $data['en'] = $this->record->translate('en')?->toArray() ?? [];
        $data['ar'] = $this->record->translate('ar')?->toArray() ?? [];
        
        return $data;
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
      
        // Extract translations
        $translations = [];
        foreach (['en', 'ar'] as $locale) {
            if (isset($data[$locale])) {
                $translations[$locale] = $data[$locale];
                unset($data[$locale]);
            }
        }
        
        // Store translations separately
        $this->translations = $translations;

         $data['_sale'] = [
            'enabled' => $data['has_sale'] ?? false,
            'price' => $data['sale_price'] ?? null,
        ];

        unset(
            $data['has_sale'],
            $data['sale_price'],
        );
        $this->saleData = $data['_sale'];
         $this->trialData = [
            'price' => $data['trial_price'],
            'capacity' => $data['trial_capacity']
        ];
        return $data;
    }
    
    protected function afterSave(): void
    {
        // Save translations
        if (isset($this->translations)) {
            foreach ($this->translations as $locale => $translation) {
                $this->record->translateOrNew($locale)->fill($translation)->save();
            }
        }

        $sale = $this->saleData;

        if ($sale['enabled']) {
            $this->record->sale()->updateOrCreate(
                [],
                [
                    'sale_price' => $sale['price'],
                ]
            );
        } else {
            $this->record->sale()?->delete();
        }

        $this->record->trial()->create([
           'price' => $this->trialData['price'] ?? 0,
           'capacity' => $this->trialData['capacity'] ?? 0
        ]);
    }
}
