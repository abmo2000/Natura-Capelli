<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable;
use Astrotomic\Translatable\Translatable as AstrotomicTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model implements Translatable
{
    use AstrotomicTranslatable , HasFactory;

     protected $guarded = ['id' , 'created_at' , 'updated_at'];

      public $translatedAttributes = ['name'];
     public $translationModel = \App\Models\Translations\CityTranslation::class;


}
