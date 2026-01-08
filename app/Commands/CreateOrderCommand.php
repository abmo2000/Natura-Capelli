<?php
namespace App\Commands;

use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateOrderCommand{

    const RELATION_TYPES = [
        'product' => 'products',
        'package' => 'packages',
        'producttrial' => 'productTrials',
    ];

     public function handle(array $data, \Closure $next)
    {
        $order = DB::transaction(function () use ($data) {
        
            $user =  User::query()->where('role_name' , 'customer')->findOrFail(Auth::id());

            $cartService = new CartService();

             $cartItems =  $cartService->getItems();

             $city = City::query()->findOrFail($data['city_id']);
             
             if(is_null($user->city_id)){     
                 $user->city_id = $city->id;
                 $user->save();
             }            
        
            $amount = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);

            if(getBuisnessSettings('order_settings')?->has_delivery_option && $data['delivery_option'] === 'proceed'){
                $amount += $city->price;
            }

            
            $order = Order::query()->create([
                'customer_id' => $data['customer_id'],
                'customer_type' => $data['customer_type'],
                'payment_method' => $data['payment_method'],
                'address' => $data['address'],
                'amount' => $amount,
                'status' => \App\Enums\OrderStatus::PENDING,
            ]);

            $this->storeItems($cartItems , $order);
              
              $cartService->clear();

            return $order;
        });

         
        $data['order'] = $order;

        return $next($data);
    }


    private function storeItems(Collection $items , Order $order){
        $items
        ->groupBy('product_type')
        ->each(function ($items, $type) use ($order) {

            if (! isset(self::RELATION_TYPES[$type])) {
                return;
            }
          
        $arr = $items->mapWithKeys(function ($item) {
            return [
                $item['product_id'] => [
                    'amount'   => $item['subtotal'],
                    'quantity' => $item['quantity'],
                ]
            ];
        })->toArray();

        
        $order->{self::RELATION_TYPES[$type]}()->attach($arr);
        });         
    }
}