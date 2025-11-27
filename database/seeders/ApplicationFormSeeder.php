<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ApplicationFormSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users first
        $userIds = [];
        
        for ($i = 1; $i <= 15; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => "Test User {$i}",
                'email' => "testuser{$i}@example.com",
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $userIds[] = $userId;
        }

        // Scholar statuses to distribute
        $scholarStatuses = [
            'Qualifiers' => 3,
            'Not Availing' => 2,
            'Deferred' => 1,
            'Graduated on Time' => 2,
            'Graduated with Extension' => 1,
            'On Ext - Complete FA' => 1,
            'On Ext - With FA' => 1,
            'On Ext - For Monitoring' => 1,
            'GS - On Track' => 1,
            'Leave of Absence' => 1,
            'Suspended' => 1,
        ];

        $index = 0;
        
        foreach ($scholarStatuses as $status => $count) {
            for ($j = 0; $j < $count; $j++) {
                if ($index >= count($userIds)) break;
                
                DB::table('application_forms')->insert([
                    'user_id' => $userIds[$index],
                    
                    // Step 0: Program info
                    'program' => $this->getRandomProgram(),
                    
                    // Step 1: Basic Info
                    'academic_year' => '2024-2025',
                    'school_term' => '1st Semester',
                    'application_no' => 'APP-2024-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                    'passport_picture' => 'passport_' . ($index + 1) . '.jpg',
                    
                    // Step 2: Personal Information
                    'last_name' => 'Dela Cruz',
                    'first_name' => 'Juan',
                    'middle_name' => 'Santos',
                    'suffix' => $index % 3 == 0 ? 'Jr.' : null,
                    'address_no' => rand(1, 999),
                    'address_street' => 'Main Street',
                    'barangay' => 'Barangay ' . rand(1, 50),
                    'city' => 'Cagayan de Oro',
                    'province' => 'Misamis Oriental',
                    'zip_code' => '9000',
                    'region' => 'Region X',
                    'district' => '1st District',
                    'email_address' => "testuser" . ($index + 1) . "@example.com",
                    'civil_status' => $index % 2 == 0 ? 'Single' : 'Married',
                    'date_of_birth' => Carbon::now()->subYears(rand(25, 40))->format('Y-m-d'),
                    'age' => rand(25, 40),
                    'sex' => $index % 2 == 0 ? 'Male' : 'Female',
                    'father_name' => 'Pedro Dela Cruz',
                    'mother_name' => 'Maria Santos',
                    
                    // Step 3: Educational Background
                    'bs_degree' => 'Bachelor of Science',
                    'bs_period' => '2010-2014',
                    'bs_field' => $this->getRandomField(),
                    'bs_university' => $this->getRandomUniversity(),
                    'bs_scholarship_type' => json_encode(['Merit-based']),
                    
                    'ms_degree' => 'Master of Science',
                    'ms_period' => '2015-2017',
                    'ms_field' => $this->getRandomField(),
                    'ms_university' => $this->getRandomUniversity(),
                    'ms_scholarship_type' => json_encode(['DOST Scholarship']),
                    
                    // Step 4: Graduate Scholarship Intentions
                    'applicant_status' => $this->getRandomApplicantStatus(),
                    'strand_category' => $this->getRandomStrand(),
                    'applicant_type' => 'New Applicant',
                    'scholarship_type' => json_encode(['MS Scholarship']),
                    'new_applicant_university' => $this->getRandomUniversity(),
                    'new_applicant_course' => $this->getRandomField(),
                    'research_topic_approved' => true,
                    'research_title' => 'Research on ' . $this->getRandomField(),
                    'intended_degree' => 'Master of Science',
                    'units_required' => 36,
                    'duration' => '2 years',
                    
                    // Step 5: Employment Information
                    'employment_status' => $index % 2 == 0 ? 'Employed' : 'Self-Employed',
                    'employed_position' => 'Research Assistant',
                    'employed_length_of_service' => rand(1, 5) . ' years',
                    'employed_company_name' => 'Tech Company Inc.',
                    'employed_company_address' => 'Business District, Cagayan de Oro',
                    
                    // Step 6: Research, Career, Publications & Awards
                    'research_plans' => 'Plan to conduct research in the field of ' . $this->getRandomField(),
                    'career_plans' => 'Aspire to become a researcher and professor',
                    'rnd_involvement' => json_encode([
                        ['title' => 'Research Project 1', 'year' => '2023']
                    ]),
                    'publications' => json_encode([
                        ['title' => 'Publication 1', 'journal' => 'Journal of Science', 'year' => '2023']
                    ]),
                    
                    // Step 7: Upload Documents
                    'birth_certificate_pdf' => 'birth_cert_' . ($index + 1) . '.pdf',
                    'transcript_of_record_pdf' => 'tor_' . ($index + 1) . '.pdf',
                    
                    // Step 8: Declaration
                    'terms_and_conditions_agreed' => true,
                    'applicant_signature' => 'Juan Dela Cruz',
                    'declaration_date' => now()->format('Y-m-d'),
                    
                    // Status & tracking - Map to application status
                    'status' => $this->mapScholarStatusToApplicationStatus($status),
                    'submitted_at' => Carbon::now()->subDays(rand(1, 90)),
                    'remarks' => "Scholar Status: {$status}",
                    
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $index++;
            }
        }
    }

    private function mapScholarStatusToApplicationStatus($scholarStatus): string
    {
        // Map scholar statuses to application form statuses
        $mapping = [
            'Qualifiers' => 'for_interview',
            'Not Availing' => 'rejected',
            'Deferred' => 'pending',
            'Graduated on Time' => 'approved',
            'Graduated with Extension' => 'approved',
            'On Ext - Complete FA' => 'approved',
            'On Ext - With FA' => 'approved',
            'On Ext - For Monitoring' => 'approved',
            'GS - On Track' => 'approved',
            'Leave of Absence' => 'approved',
            'Suspended' => 'document_verification',
        ];

        return $mapping[$scholarStatus] ?? 'submitted';
    }

    private function getRandomProgram(): string
    {
        $programs = ['MS Program', 'PhD Program', 'Engineering Program', 'Science Program'];
        return $programs[array_rand($programs)];
    }

    private function getRandomField(): string
    {
        $fields = [
            'Computer Science',
            'Engineering',
            'Biology',
            'Chemistry',
            'Physics',
            'Mathematics',
            'Environmental Science'
        ];
        return $fields[array_rand($fields)];
    }

    private function getRandomUniversity(): string
    {
        $universities = [
            'University of the Philippines',
            'Ateneo de Manila University',
            'De La Salle University',
            'University of Santo Tomas',
            'Mindanao State University'
        ];
        return $universities[array_rand($universities)];
    }

    private function getRandomApplicantStatus(): string
    {
        $statuses = ['Active Student', 'Graduate', 'Working Professional'];
        return $statuses[array_rand($statuses)];
    }

    private function getRandomStrand(): string
    {
        $strands = ['Science', 'Technology', 'Engineering', 'Mathematics'];
        return $strands[array_rand($strands)];
    }
}