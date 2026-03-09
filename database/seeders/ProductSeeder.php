<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Routine;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::query()->get();
        $routines = Routine::query()->get();

        if ($categories->isEmpty() || $routines->isEmpty()) {
            return;
        }

        $products = [
            [
                'image' => 'products/hydration-shampoo.jpg',
                'price' => 320,
                'capacity' => 300,
                'in_stock' => true,
                'featured' => true,
                'category_index' => 0,
                'routine_indexes' => [0],
                'translations' => [
                    'en' => [
                        'name' => 'Hydration Shampoo',
                        'description' => 'Gentle cleansing shampoo for dry hair.',
                    ],
                    'ar' => [
                        'name' => 'شامبو الترطيب',
                        'description' => 'شامبو لطيف لتنظيف الشعر الجاف.',
                    ],
                ],
            ],
            [
                'image' => 'products/nourish-conditioner.jpg',
                'price' => 340,
                'capacity' => 300,
                'in_stock' => true,
                'featured' => true,
                'category_index' => 1,
                'routine_indexes' => [0, 1],
                'translations' => [
                    'en' => [
                        'name' => 'Nourish Conditioner',
                        'description' => 'Conditioner that softens and detangles.',
                    ],
                    'ar' => [
                        'name' => 'بلسم التغذية',
                        'description' => 'بلسم يساعد على النعومة وفك التشابك.',
                    ],
                ],
            ],
            [
                'image' => 'products/repair-mask.jpg',
                'price' => 480,
                'capacity' => 250,
                'in_stock' => true,
                'featured' => false,
                'category_index' => 2,
                'routine_indexes' => [1],
                'translations' => [
                    'en' => [
                        'name' => 'Repair Hair Mask',
                        'description' => 'Weekly intensive mask for damaged hair.',
                    ],
                    'ar' => [
                        'name' => 'ماسك الإصلاح',
                        'description' => 'ماسك أسبوعي مكثف للشعر المتضرر.',
                    ],
                ],
            ],
            [
                'image' => 'products/curl-cream.jpg',
                'price' => 410,
                'capacity' => 200,
                'in_stock' => true,
                'featured' => true,
                'category_index' => 2,
                'routine_indexes' => [2],
                'translations' => [
                    'en' => [
                        'name' => 'Curl Defining Cream',
                        'description' => 'Light cream to define curls and control frizz.',
                    ],
                    'ar' => [
                        'name' => 'كريم تحديد الكيرلي',
                        'description' => 'كريم خفيف لتحديد الكيرلي وتقليل الهيشان.',
                    ],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $translations = $productData['translations'];
            $category = $categories[$productData['category_index']] ?? null;

            $routineIndexes = $productData['routine_indexes'];

            unset($productData['translations'], $productData['category_index'], $productData['routine_indexes']);

            if (! $category) {
                continue;
            }

            $productData['category_id'] = $category->id;

            $product = Product::create($productData);

            foreach ($translations as $locale => $translation) {
                $product->translateOrNew($locale)
                    ->fill([
                        'locale' => $locale,
                        'name' => $translation['name'],
                        'description' => $translation['description'],
                    ])
                    ->save();
            }

            $routineIds = collect($routineIndexes)
                ->map(fn (int $index) => $routines[$index]->id ?? null)
                ->filter()
                ->values()
                ->all();

            if ($routineIds !== []) {
                $product->routines()->attach($routineIds);
            }
        }
    }
}
