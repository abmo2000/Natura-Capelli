<?php

namespace App\Filament\Resources\Products\Pages;

use App\Models\Brand;
use App\Filament\Resources\Products\ProductResource;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;


     protected array $translations = [];

     protected array $saleData = [];
      
     protected array $trialData = [];

    protected ?string $brandImage = null;

    protected ?string $brandName = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
       
        // Extract translations
        $translations = [];
        foreach (['en', 'ar'] as $locale) {
            if (isset($data[$locale])) {
                $translations[$locale] = $data[$locale];
                unset($data[$locale]);
            }
        }
        
        // Store translations separately to be handled after creation
        $this->translations = $translations;

         $data['_sale'] = [
            'enabled' => $data['has_sale'] ?? false,
            'price' => $data['sale_price'] ?? null,
        ];

        unset(
            $data['has_sale'],
            $data['sale_price'],
        );

        $data['_trial'] = [
            'enabled' => $data['has_trial'] ?? false,
             'price' => $data['trial_price']??null,
            'capacity' => $data['trial_capacity']??null,
            'image' => $data['trial_image']??null,
        ];

        unset(
            $data['has_trial'],
             $data['trial_price'],
            $data['trial_capacity'],
        );

        $this->brandImage = $data['brand_image'] ?? null;
        $this->brandName = ! empty($data['brand']) ? trim((string) $data['brand']) : null;
        unset($data['brand_image']);
        
        $this->saleData = $data['_sale'];
        $this->trialData = $data['_trial'];

        return $data;
        
    }
    
    protected function afterCreate(): void
    {
        // Save translations
        if (isset($this->translations)) {
            foreach ($this->translations as $locale => $translation) {
                $this->record->translateOrNew($locale)->fill($translation)->save();
            }
        }

        $sale = $this->saleData;

        if ($sale['enabled']) {
            $this->record->sale()->create([
                'sale_price' => $sale['price'],
            ]);
        }
           
       $trial = $this->trialData;
       if($trial['enabled']){
           $this->record->trial()->create([
              'price' => $this->trialData['price'] ?? 0,
              'capacity' => $this->trialData['capacity'] ?? 0,
              'image' => $trial['image'] ?? null
           ]);

           
       }

       $this->syncSharedBrandImage();
    }

    protected function syncSharedBrandImage(): void
    {
        if (empty($this->brandName)) {
            return;
        }

        $brand = Brand::query()->firstOrCreate(
            ['name' => $this->brandName],
            ['image' => null],
        );

        if (empty($this->brandImage)) {
            return;
        }

        $oldImage = $brand->image;

        $brand->update([
            'image' => $this->brandImage,
        ]);

        if (! empty($oldImage) && $oldImage !== $this->brandImage) {
            Storage::disk('public')->delete($oldImage);
        }
    }
}
