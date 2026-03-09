<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTrial;
use Illuminate\Database\Seeder;

class ProductTrialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::query()->get();

        if ($products->isEmpty()) {
            return;
        }

        $trials = [
            [
                'product_index' => 0,
                'capacity' => 60,
                'price' => 90,
            ],
            [
                'product_index' => 1,
                'capacity' => 60,
                'price' => 95,
            ],
            [
                'product_index' => 3,
                'capacity' => 50,
                'price' => 110,
            ],
        ];

        foreach ($trials as $trialData) {
            $product = $products[$trialData['product_index']] ?? null;

            if (! $product) {
                continue;
            }

            ProductTrial::create([
                'product_id' => $product->id,
                'capacity' => $trialData['capacity'],
                'price' => $trialData['price'],
            ]);
        }
    }
}
