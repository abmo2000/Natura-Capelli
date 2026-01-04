<?php

namespace App\Filament\Resources\Products\Pages;

use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Products\ProductResource;

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
        
          $data['_trial'] = [
            'enabled' => $data['has_trial'] ?? false,
             'price' => $data['trial_price']??null,
            'capacity' => $data['trial_capacity']??null,
            'trial_image' => $data['trial_image']??null,
        ];

        unset(
            $data['has_trial'],
             $data['trial_price'],
            $data['trial_capacity'],
        );
          $this->trialData = $data['_trial'];
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
        $trial = $this->trialData;

       

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


        $existingTrial = $this->record->trial;
        $oldImage = $existingTrial?->image;

        

     if($trial['enabled']){
            
            $this->record->trial()->updateOrCreate(
                [],
                [
                    'price' => $this->trialData['price'] ?? 0,
                    'capacity' => $this->trialData['capacity'] ?? 0,
                    'image' => $trial['trial_image'] ?? null
                ]
            );

        }else{
            $this->record->trial()->delete(); 
        }

          if ($oldImage) {
                Storage::disk('local')->delete($oldImage);
          }
    }
}
