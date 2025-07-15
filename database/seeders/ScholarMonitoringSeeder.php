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
                        'degree_type' => $degree,
                        'year_awarded' => $year,
                        'status_code' => $code,
                        'total' => rand(1, 5),
                    ]);
                }
            }
        }
    }
}
