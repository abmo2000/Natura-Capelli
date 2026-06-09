<?php

namespace App\Observers;

use App\Models\ProductTrial;
use Illuminate\Support\Facades\Storage;

class ProductTrialObserver
{
    const STORAGE_DISK = 'local';

    // Fires when record is updated — deletes old image if changed
    public function updating(ProductTrial $product): void
    {
        if ($product->isDirty('image')) {
            $oldImage = $product->getOriginal('image');
            if ($oldImage && Storage::disk(self::STORAGE_DISK)->exists($oldImage)) {
                Storage::disk(self::STORAGE_DISK)->delete($oldImage);
            }
        }
    }

    // Fires when record is deleted — deletes image from storage
    public function deleted(ProductTrial $product): void
    {
        if ($product->image && Storage::disk(self::STORAGE_DISK)->exists($product->image)) {
            Storage::disk(self::STORAGE_DISK)->delete($product->image);
        }
    }
}
