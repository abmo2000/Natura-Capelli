<?php

namespace Database\Seeders;

use App\Models\Routine;
use Illuminate\Database\Seeder;

class RoutineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routines = [
            [
                'image' => 'routines/daily-care.jpg',
                'translations' => [
                    'en' => [
                        'title' => 'Daily Care Routine',
                        'description' => 'A simple routine for everyday hydration and shine.',
                    ],
                    'ar' => [
                        'title' => 'روتين العناية اليومي',
                        'description' => 'روتين بسيط للترطيب اليومي ولمعان الشعر.',
                    ],
                ],
            ],
            [
                'image' => 'routines/repair-routine.jpg',
                'translations' => [
                    'en' => [
                        'title' => 'Repair Routine',
                        'description' => 'Focused care for dry and damaged hair.',
                    ],
                    'ar' => [
                        'title' => 'روتين الإصلاح',
                        'description' => 'عناية مركزة للشعر الجاف والمتضرر.',
                    ],
                ],
            ],
            [
                'image' => 'routines/curly-routine.jpg',
                'translations' => [
                    'en' => [
                        'title' => 'Curly Hair Routine',
                        'description' => 'Defines curls while reducing frizz.',
                    ],
                    'ar' => [
                        'title' => 'روتين الشعر الكيرلي',
                        'description' => 'يساعد على تحديد الكيرلي وتقليل الهيشان.',
                    ],
                ],
            ],
        ];

        foreach ($routines as $routineData) {
            $translations = $routineData['translations'];
            unset($routineData['translations']);

            $routine = Routine::create($routineData);

            foreach ($translations as $locale => $translation) {
                $routine->translateOrNew($locale)
                    ->fill([
                        'locale' => $locale,
                        'title' => $translation['title'],
                        'description' => $translation['description'],
                    ])
                    ->save();
            }
        }
    }
}
