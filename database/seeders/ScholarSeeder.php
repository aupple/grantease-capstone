<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ApplicationForm;
use App\Models\Scholar;
use Illuminate\Support\Facades\Hash;

class ScholarSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'qualifiers',
            'not_availing',
            'deferred',
            'graduated_on_time',
            'graduated_ext',
            'on_ext_complete_fa',
            'on_ext_with_fa',
            'on_ext_for_monitoring',
            'gs_on_track',
            'leave_of_absence',
            'suspended',
            'no_report',
            'non_compliance',
            'terminated',
            'withdrawn'
        ];

        $firstNames = [
            'Juan', 'Maria', 'Jose', 'Ana', 'Pedro', 
            'Sofia', 'Miguel', 'Isabella', 'Carlos', 'Lucia',
            'Rafael', 'Valentina', 'Diego', 'Camila', 'Luis'
        ];

        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Garcia',
            'Mendoza', 'Torres', 'Flores', 'Rivera', 'Gomez',
            'Hernandez', 'Lopez', 'Gonzales', 'Aquino', 'Ramos'
        ];

        foreach ($statuses as $i => $status) {
            $firstName = $firstNames[$i];
            $lastName = $lastNames[$i];

            // Create user
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . '@test.com'),
                'password' => Hash::make('password'),
                'role_id' => 2,
                'program_type' => 'DOST',
                'email_verified_at' => now(),
            ]);

            // Create application
            $app = ApplicationForm::create([
                'user_id' => $user->user_id,
                'email_address' => $user->email,
                'status' => 'approved',
                'program' => 'MS',
                'terms_and_conditions_agreed' => true,
            ]);

            // Create scholar with status
            Scholar::create([
                'user_id' => $user->user_id,
                'application_form_id' => $app->application_form_id,
                'status' => $status,
                'start_date' => now(),
            ]);
        }

        $this->command->info('✅ Created 15 scholars with random names!');
    }
}