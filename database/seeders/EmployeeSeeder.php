<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory(100)->create([
            'country_id' => 101, //India
            'state_id' => 12,    //Gujarat
            'city_id' => 1041,     //Surat
            'department_id' => 1,   //BCA
        ]);
    }
}
