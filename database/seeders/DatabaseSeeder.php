<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'name' => 'Nehal Patel',
            'email' => 'iamnehalpatel@gmail.com',
            'password' => 'nehal123'
        ]);

        \App\Models\User::factory(10)->create();

        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(EmployeeSeeder::class);
    }
}
