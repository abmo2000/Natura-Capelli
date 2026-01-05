<?php

use App\Models\BuisnessSetting;
use App\Models\City;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getBuisnessSettings')) {
    function getBuisnessSettings($key = null, $default = null)
    {
        
        $settings = BuisnessSetting::query()->where('key' , $key)->first();
    
        if (! $settings) {
            return null;
        }

       $decodeValue = json_decode($settings->value , true);

       if(is_array($decodeValue)){
          return (object)$decodeValue;
       }
        
       return $default;
    
    }

}

if(!function_exists('getCities')){
  
    function getCities(){
         
       return Cache::remember('cities' , now()->addDay(),function(){
               return City::query()->get()->map(function ($city) {
                      return [
                        'id' => $city->id,
                        'value' => $city?->name,
                      ];
               });
        });

    }

}



