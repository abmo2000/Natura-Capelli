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

        // User::factory()->create([
        //     'name' => 'Heidi',
        //     'email' => 'heidi@gmail.com',
        //     'password' => 'password@123',
        //     'role_name'=> 'admin',
        // ]);

        $this->call([
            CitySeeder::class,
        ]);
    }
}
