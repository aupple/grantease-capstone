<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Test Admin',
                'username' => 'admin123',
                'first_name' => 'Test',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'contact_number' => '09123456789',
                'application_no' => 'APP001'
            ],
            [
                'name' => 'Test Applicant',
                'username' => 'applicant123',
                'first_name' => 'Test',
                'last_name' => 'Applicant',
                'email' => 'applicant@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'contact_number' => '09987654321',
                'application_no' => 'APP002'
            ]
        ]);
    }
}
