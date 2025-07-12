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
            'good_standing',
            'graduated_ext',
            'on_extension',
            'leave_of_absence',
            'non_compliance',
            'no_report',
            'withdrawn',
            'terminated',
        ];

        foreach ($statuses as $status) {
            // Create a user
            $user = User::factory()->create();

            // Create application form
            $application = ApplicationForm::create([
                'user_id' => $user->user_id,  // <-- use user_id, not id
                'program' => 'DOST','CHED',
                'school' => 'Test University',
                'year_level' => '2nd Year',
                'reason' => 'For Testing',
                'status' => 'approved',
                'submitted_at' => now()->subDays(rand(2, 7)),
            ]);

            // Create scholar with status
            Scholar::create([
                'user_id' => $user->user_id,  // <-- use user_id, not id
                'application_form_id' => $application->application_form_id,  // <-- use application_form_id, not id
                'status' => $status,
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
            ]);
        }
    }
}
