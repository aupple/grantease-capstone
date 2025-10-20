<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholar;
use App\Models\ScholarMonitoring;

class ScholarMonitoringSeeder extends Seeder
{
    public function run(): void
    {
        $degrees = ['Masters', 'Doctoral'];
        $levels = ['MS', 'PhD'];
        $statusCodes = ['ENR', 'GRD', 'LVW', 'DIS', 'END', 'RET']; // DOST codes
        $enrollmentTypes = ['FULL TIME', 'PART TIME'];
        $newOrLateral = ['NEW', 'LATERAL'];
        $schools = ['USTP', 'UP Diliman', 'Ateneo de Manila University', 'De La Salle University', 'Mindanao State University'];
        $courses = ['Computer Science', 'Engineering', 'Biology', 'Mathematics'];
        $applicantTypes = ['New', 'Old', 'Transfer', 'Continuing'];

        $scholars = Scholar::all();

        if ($scholars->isEmpty()) {
            $this->command->warn('⚠️ No scholars found. Run ScholarSeeder first.');
            return;
        }

        foreach ($scholars as $scholar) {
            ScholarMonitoring::create([
                'scholar_id' => $scholar->id,
                'last_name' => fake()->lastName(),
                'first_name' => fake()->firstName(),
                'middle_name' => fake()->lastName(),

                'level' => fake()->randomElement($levels),
                'course' => fake()->randomElement($courses),
                'school' => fake()->randomElement($schools),

                'new_or_lateral' => fake()->randomElement($newOrLateral),
                'enrollment_type' => fake()->randomElement($enrollmentTypes),
                'scholarship_duration' => fake()->randomElement(['2 years', '3 years', '4 years']),
                'date_started' => fake()->dateTimeBetween('-3 years', '-1 years')->format('Y-m-d'),
                'expected_completion' => fake()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),

                'year_awarded' => fake()->numberBetween(2020, 2025),
                'degree_type' => fake()->randomElement($degrees),
                'status_code' => fake()->randomElement($statusCodes),
                'total' => fake()->numberBetween(1, 10),

                'status' => ucfirst(strtolower(fake()->randomElement(['Active', 'Graduated', 'Discontinued', 'On Leave']))),
                'remarks' => 'Auto-generated for report testing.',

                // 🆕 New fields
                'new_applicant_course' => fake()->randomElement($courses),
                'applicant_type' => fake()->randomElement($applicantTypes),
                'declaration_date' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                'remark' => fake()->sentence(6),
            ]);
        }

        $this->command->info('✅ ScholarMonitoringSeeder completed successfully with new fields.');
    }
}
