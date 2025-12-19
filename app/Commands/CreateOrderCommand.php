<?php
namespace App\Commands;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CreateOrderCommand{
     public function handle(array $data, \Closure $next)
    {
        $order = DB::transaction(function () use ($data) {
        
            $cartItems = session('cart', []);
            
        
            $amount = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
            
            $order = Order::query()->create([
                'customer_id' => $data['customer_id'],
                'customer_type' => $data['customer_type'],
                'payment_method' => $data['payment_method'],
                'address' => $data['address'],
                'amount' => $amount,
                'status' => \App\Enums\OrderStatus::PENDING,
            ]);

            
            $order->items()->attach(
            collect($cartItems)->mapWithKeys(fn($item, $productId) => [
                    $productId => [
                        'quantity' => $item['quantity'],
                        'amount' => ($item['price'] * $item['quantity']),
                    ]
                 ])->toArray()
             );

            return $order;
        });

        session()->forget('cart');


        $data['order'] = $order;

        return $next($data);
    }
}