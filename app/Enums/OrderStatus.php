<?php

namespace App\Enums;


enum OrderStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

   public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public static function toAssociativeArray():array
    {
         return array_column(self::cases(), 'value' , 'value');
    }
}