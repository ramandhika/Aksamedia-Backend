<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = [
            [
                'name' => 'Admin',
                'phone' => '081234567890',
                'division_id' => 1,
                'position' => 'Admin',
                'image' => 'images/admin.jpg',
            ],
            [
                'name' => 'User',
                'phone' => '081234567891',
                'division_id' => 2,
                'position' => 'User',
                'image' => 'images/user.jpg',
            ],
        ];

        foreach ($employee as $employee) {
            Employee::create($employee);
        }
    }
}
