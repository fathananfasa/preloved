<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

         User::updateOrCreate(
            ['email' => 'buyer@gmail.com'],
            [
                'name' => 'Buyer',
                'password' => Hash::make('buyer123'),
                'role' => 'buyer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'buyer2@gmail.com'],
            [
                'name' => 'Buyer2',
                'password' => Hash::make('buyer321'),
                'role' => 'buyer',
            ]
        );
    }
}
