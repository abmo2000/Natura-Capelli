<?php

namespace App\Models\Translations;

use App\Models\Product;
use App\Models\Scopes\InStockScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductTranslation extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);

    }

    protected static function booted()
    {
        static::saved(function ($translation) {
            if ($translation->locale === 'en') {
                $product = $translation->product()
                    ->withoutGlobalScope(InStockScope::class)
                    ->where('id', $translation->product_id)
                    ->first();
                $slug = Str::slug($translation->name.'-'.$product->id);
                $product->slug = $slug;
                $product->saveQuietly();
            }

        });
    }
}
