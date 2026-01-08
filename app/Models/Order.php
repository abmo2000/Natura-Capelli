<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    protected $guarded = ['id' , 'created_at' , 'updated_at'];


    public function customer(){
        return $this->morphTo();
    }

       public function products()
    {
        return $this->morphedByMany(Product::class, 'typeable', 'order_items')
            ->using(OrderItem::class)
            ->withPivot([ 'quantity', 'amount'])
            ->withTimestamps();
    }

    public function packages()
    {
        return $this->morphedByMany(Package::class, 'typeable', 'order_items')
            ->using(OrderItem::class)
            ->withPivot([ 'quantity', 'amount'])
            ->withTimestamps();
    }

    public function productTrials()
    {
        return $this->morphedByMany(ProductTrial::class, 'typeable', 'order_items')
            ->using(OrderItem::class)
            ->withPivot(['quantity', 'amount'])
            ->withTimestamps();
    }

   
    public function items():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
