<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Astrotomic\Translatable\Contracts\Translatable;
use Astrotomic\Translatable\Translatable as AstrotomicTranslatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model implements Translatable
{
    use AstrotomicTranslatable;

    protected $guarded = ['id' , 'created_at' , 'updated_at'];

    public $translatedAttributes = ['name' , 'description'];
     public $translationModel = \App\Models\Translations\ProductTranslation::class;
    public function routines():BelongsToMany{
        return $this->belongsToMany(Routine::class , 'products_routines');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

     protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            // Delete the image file when the routine is deleted
            if ($product->image) {
                Storage::disk('local')->delete($product->image);
            }
        });

    }
}
