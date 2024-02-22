<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->delete();
        $departments = array(
            'BCA','BCom','BBA'
        );
        foreach ($departments as $department) {
            Department::create([
                'name' => $department
            ]);
        }
    }
}
