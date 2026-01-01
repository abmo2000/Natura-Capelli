<?php

use App\Models\BuisnessSetting;

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