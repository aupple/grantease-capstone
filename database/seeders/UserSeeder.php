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
            'last_name' => 'Scholarship Staff',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
        ]);

        user::create([
            'first_name' => 'Thresha',
            'middle_name' => 'Calinga',
            'last_name' => 'macapayad',
            'email' => 'villaro.threshamelle@gmail.com',
            'password' => Hash::make('shang123'),
            'role_id' => 2,
            'program_type' => 'CHED',
        ]);
    }
}
