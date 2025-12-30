<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Astrotomic\Translatable\Contracts\Translatable;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Astrotomic\Translatable\Translatable as AstrotomicTranslatable;

class Package extends Model implements Translatable
{
    use AstrotomicTranslatable;

  public $translationModel = \App\Models\Translations\PackageTranslation::class;
    protected $guarded = ['id' , 'created_at' , 'updated_at'];

    public $translatedAttributes = ['title' , 'description'];

     protected $appends = ['images'];

    public function products():BelongsToMany{
        return $this->belongsToMany(Product::class , 'package_products');
    }

    public function recalculateOriginalPrice(): void
    {
        $this->updateQuietly([
            'original_price' => $this->products()->sum('price'),
        ]);
    }

    public function images():Attribute{
          return Attribute::make(
        get: function () {
            $this->loadMissing('products');
            return $this->products
                ->map(fn ($product) => (object)[
                    'image' => $product->image,
                ])
                ->values()
                ->toArray();
        }
    );   
    }
}
