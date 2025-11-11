<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Jampong',
            'middle_name' => null,
            'last_name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
        ]);

        user::create([
            'first_name' => 'Uno',
            'middle_name' => null,
            'last_name' => 'macapayad',
            'email' => 'uno@gmail.com',
            'password' => Hash::make('blabla'),
            'role_id' => 2,
            'program_type' => 'CHED',
        ]);
    }
}
