<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'phone' => '081234567890',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('pastibisa'),
            ],
            [
                'name' => 'User',
                'username' => 'user',
                'phone' => '081234567891',
                'email' => 'user@gmail.com',
                'password' => bcrypt('yakaligamagang'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
