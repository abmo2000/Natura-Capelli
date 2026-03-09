<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
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

        $packages = [
            [
                'price' => 599,
                'is_active' => true,
                'product_indexes' => [0, 1],
                'translations' => [
                    'en' => [
                        'name' => 'Daily Essentials Bundle',
                        'description' => 'Starter bundle for daily hair care.',
                    ],
                    'ar' => [
                        'name' => 'باقة العناية اليومية',
                        'description' => 'باقة أساسية للاستخدام اليومي.',
                    ],
                ],
            ],
            [
                'price' => 799,
                'is_active' => true,
                'product_indexes' => [2, 3],
                'translations' => [
                    'en' => [
                        'name' => 'Repair and Style Bundle',
                        'description' => 'Combines treatment and styling support.',
                    ],
                    'ar' => [
                        'name' => 'باقة الإصلاح والتصفيف',
                        'description' => 'تجمع بين العلاج والتصفيف اليومي.',
                    ],
                ],
            ],
        ];

        foreach ($packages as $packageData) {
            $translations = $packageData['translations'];
            $productIndexes = $packageData['product_indexes'];

            unset($packageData['translations'], $packageData['product_indexes']);

            $package = Package::create($packageData);

            foreach ($translations as $locale => $translation) {
                $package->translateOrNew($locale)
                    ->fill([
                        'locale' => $locale,
                        'name' => $translation['name'],
                        'description' => $translation['description'],
                    ])
                    ->save();
            }

            // Keep slug generation stable even if translation observer logic changes.
            $package->slug = Str::slug(($translations['en']['name'] ?? 'package') . '-' . $package->id);
            $package->saveQuietly();

            $productIds = collect($productIndexes)
                ->map(fn (int $index) => $products[$index]->id ?? null)
                ->filter()
                ->values()
                ->all();

            if ($productIds !== []) {
                $package->products()->attach($productIds);
                $package->recalculateOriginalPrice();
            }
        }
    }
}
