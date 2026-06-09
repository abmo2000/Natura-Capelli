<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'translations' => [
                    'en' => ['title' => 'Shampoo'],
                    'ar' => ['title' => 'شامبو'],
                ],
            ],
            [
                'translations' => [
                    'en' => ['title' => 'Conditioner'],
                    'ar' => ['title' => 'بلسم'],
                ],
            ],
            [
                'translations' => [
                    'en' => ['title' => 'Treatments'],
                    'ar' => ['title' => 'علاجات'],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $translations = $categoryData['translations'];
            unset($categoryData['translations']);

            $category = Category::create($categoryData);

            foreach ($translations as $locale => $translation) {
                $category->translateOrNew($locale)
                    ->fill(['locale' => $locale, 'title' => $translation['title']])
                    ->save();
            }
        }
    }
}
