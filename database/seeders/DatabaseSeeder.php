<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->updateOrCreate(
            ['email' => 'hanyqaid63@gmail.com'],
            [
                'name' => 'Hany Qaid',
                'password' => 'RB*I3I#xKXk.X^Q6ki:*:#*@:+#1N/031`(',
                'role_name'=> 'super_admin',
                'is_approved' => true,
            ]
        );

        $this->call([
            CitySeeder::class,
        ]);
    }
}
