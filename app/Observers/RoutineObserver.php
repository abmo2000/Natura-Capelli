<?php

namespace App\Observers;

use App\Models\Routine;
use Illuminate\Support\Facades\Storage;

class RoutineObserver
{
    const STORAGE_DISK = 'local';

    // Fires when record is updated — deletes old image if changed
    public function updating(Routine $routine): void
    {
        if ($routine->isDirty('image')) {
            $oldImage = $routine->getOriginal('image');
            if ($oldImage && Storage::disk(self::STORAGE_DISK)->exists($oldImage)) {
                Storage::disk(self::STORAGE_DISK)->delete($oldImage);
            }
        }
    }

    // Fires when record is deleted — deletes image from storage
    public function deleted(Routine $routine): void
    {
        if ($routine->image && Storage::disk(self::STORAGE_DISK)->exists($routine->image)) {
            Storage::disk(self::STORAGE_DISK)->delete($routine->image);
        }
    }
}
