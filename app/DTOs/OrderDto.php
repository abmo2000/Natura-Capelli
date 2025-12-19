<?php


namespace App\DTOs;


class OrderDto{


    public function __construct(private array $data)
    {
        
    }


    public function userData(){

         return [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
         ]
    }
}