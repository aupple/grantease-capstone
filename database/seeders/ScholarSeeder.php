<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholar;
use App\Models\User;
use App\Models\ApplicationForm;

class ScholarSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'qualifier',
            'not_availing',
            'deferred',
            'graduated_ontime',
            'graduated_ext',
            'on_extension_complete_fa',
            'on_extension_with_fa',
            'on_extension_monitoring',
            'gs_on_track',
            'loa',
            'suspended',
            'no_report',
            'non_compliance',
            'terminated',
            'withdrew',
        ];

        foreach ($statuses as $status) {
            $user = User::factory()->create();

            $application = ApplicationForm::create([
                'user_id' => $user->user_id,
                'program' => fake()->randomElement(['DOST', 'CHED']),
                'school' => 'Test University',
                'year_level' => '2nd Year',
                'reason' => 'For Testing',
                'status' => 'approved',
                'submitted_at' => now()->subDays(rand(2, 7)),
            ]);

            Scholar::create([
                'user_id' => $user->user_id,
                'application_form_id' => $application->application_form_id,
                'status' => $status,
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
            ]);
        }
    }
}
