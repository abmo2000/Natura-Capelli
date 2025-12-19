<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $guarded = ['id' , 'created_at' , 'updated_at'];


    public function items():BelongsToMany{
          return $this->belongsToMany(Product::class , 'order_items')->withTimestamps()->withPivot(['quantity' , 'amount']);
    }

    public function customer(){
        return $this->morphTo();
    }
}
