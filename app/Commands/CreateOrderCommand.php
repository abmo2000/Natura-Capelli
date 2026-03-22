<?php
namespace App\Commands;

use App\Models\City;
use App\Models\Order;
use App\Models\Coupon;
use App\Events\OrderCreated;
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

            $user = (($data['customer_type'] ?? null) === 'user' && Auth::check())
                ? Auth::user()
                : null;

            $cartService = new CartService();

             $cartItems =  $cartService->getItems();

             $city = City::query()->findOrFail($data['city_id']);
             
                 if($user && is_null($user->city_id)){
                 $user->city_id = $city->id;
                 
             }
             
                 if($user && is_null($user->phone)){
                $user->phone = $data['phone'];
             }

                 if($user && is_null($user->address)){
                $user->address = $data['address'];
             }

                 if($user && array_key_exists('insta_account' , $data)){
                 $user->insta_account = $data['insta_account'];
             }

                 if($user){
                     $user->save();
                 }
        
            $amount = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);

             $data['delivery_price'] = $city->price;

             $Totalamount = $amount +  $city->price;


            if(getBuisnessSettings('order_settings')?->has_delivery_option && $data['delivery_option'] === 'discuss'){
                $Totalamount =  $amount;
                $data['delivery_price'] = 0;
            }


            if($user && getBuisnessSettings('order_settings')?->allow_first_order_for_free && ! $user->orders()->exists() ){
                $Totalamount = $amount;
                 $data['delivery_price'] = 0;
            }

            // Apply coupon discount if a valid code was provided
            $couponId = null;
            $adminCreatorId = null;
            if (! empty($data['coupon_code'])) {
                $couponCode = strtoupper(trim((string) $data['coupon_code']));

                $coupon = Coupon::query()
                    ->whereRaw('UPPER(code) = ?', [$couponCode])
                    ->where('is_active', true)
                    ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
                    ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
                    ->first();

                if ($coupon) {
                    $discount = round($amount * ($coupon->discount_percentage / 100), 2);
                    $Totalamount = max(0, $Totalamount - $discount);
                    $couponId = $coupon->id;
                    $adminCreatorId = $coupon->created_by;
                }
            }

            
            $order = Order::query()->create([
                'customer_id' => $data['customer_id'],
                'customer_type' => $data['customer_type'],
                'payment_method' => $data['payment_method'],
                'customer_address' => $data['address'],
                'amount' => $Totalamount,
                'delivery_option' => $data['delivery_option'],
                'delivery_price' =>  $data['delivery_price'] ,
                'notes' => $data['notes'] ?? null,
                'coupon_id' => $couponId,
                'admin_creator_id' => $adminCreatorId,
                'status' => \App\Enums\OrderStatus::PENDING,
            ]);

            $this->storeItems($cartItems , $order);
              
              $cartService->clear();

               event(new OrderCreated($cartItems , $amount , 
               $order));

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