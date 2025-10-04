<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarMonitoring;

class ScholarMonitoringSeeder extends Seeder
{
    public function run(): void
    {
        $degrees = ['Bachelors', 'Masters', 'Doctorate'];
        $years = range(2023, now()->year);
        $statusCodes = [1, 2, 3, '4a', '4b', '5a', '5b', '5c', '6a', '6b', '6c', '6d', 7, 8, 9, 10];

        foreach ($degrees as $degree) {
            foreach ($years as $year) {
                foreach ($statusCodes as $code) {
                    ScholarMonitoring::create([
    'scholar_id' => $scholar->id,
    'last_name' => fake()->lastName(),
    'first_name' => fake()->firstName(),
    'middle_name' => fake()->lastName(),
    'level' => fake()->randomElement(['MS','PhD']),
    'course' => 'Computer Science',
    'school' => 'Test University',
    'new_or_lateral' => fake()->randomElement(['NEW','LATERAL']),
    'enrollment_type' => fake()->randomElement(['FULL TIME','PART TIME']),
    'scholarship_duration' => '2 years',
    'date_started' => now()->subYear()->format('Y-m-d'),
    'expected_completion' => now()->addYear()->format('Y-m-d'),
    'year_awarded' => 2024,
    'degree_type' => 'Masters',
    'status_code' => '3',
    'total' => 1,
    'status' => 'active',
    'remarks' => 'Seeder test data',
]);

                }
            }
        }
    }
}
