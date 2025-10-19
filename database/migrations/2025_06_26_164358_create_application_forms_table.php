<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('application_forms', function (Blueprint $table) {
            $table->id('application_form_id');
            $table->unsignedBigInteger('user_id');

            // Step 0: Program info
            $table->string('program', 100)->nullable();
            $table->string('reason', 50)->nullable();

            // Step 1: Basic Info
            $table->string('academic_year', 20)->nullable();
            $table->string('school_term', 20)->nullable();
            $table->string('application_no', 50)->nullable();
            $table->string('passport_picture', 255)->nullable();

            // Step 2: Personal Information
            $table->string('last_name', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('suffix', 50)->nullable();

            $table->string('address_no', 50)->nullable();
            $table->string('address_street', 150)->nullable();
            $table->string('barangay', 150)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('province', 150)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('district', 100)->nullable();

            $table->string('passport_no', 50)->nullable();
            $table->string('email_address', 150)->nullable();
            $table->string('current_mailing_address', 255)->nullable();
            $table->string('telephone_nos', 50)->nullable();
            $table->string('civil_status', 50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('father_name', 150)->nullable();
            $table->string('mother_name', 150)->nullable();

            // Step 3: Educational Background (BS / MS / PhD)
            $table->string('bs_degree', 150)->nullable();
            $table->string('bs_period', 50)->nullable();
            $table->string('bs_field', 150)->nullable();
            $table->string('bs_university', 150)->nullable();
            $table->json('bs_scholarship_type')->nullable();
            $table->string('bs_scholarship_others', 150)->nullable();
            $table->string('bs_remarks', 150)->nullable();

            $table->string('ms_degree', 150)->nullable();
            $table->string('ms_period', 50)->nullable();
            $table->string('ms_field', 150)->nullable();
            $table->string('ms_university', 150)->nullable();
            $table->json('ms_scholarship_type')->nullable();
            $table->string('ms_scholarship_others', 150)->nullable();
            $table->string('ms_remarks', 150)->nullable();

            $table->string('phd_degree', 150)->nullable();
            $table->string('phd_period', 50)->nullable();
            $table->string('phd_field', 150)->nullable();
            $table->string('phd_university', 150)->nullable();
            $table->json('phd_scholarship_type')->nullable();
            $table->string('phd_scholarship_others', 150)->nullable();
            $table->string('phd_remarks', 150)->nullable();

            // Step 4: Graduate Scholarship Intentions
            $table->string('applicant_status', 50)->nullable();
            $table->string('strand_category', 50)->nullable();
            $table->string('applicant_type', 50)->nullable();
            $table->json('scholarship_type')->nullable();

            $table->string('new_applicant_university', 150)->nullable();
            $table->string('new_applicant_course', 150)->nullable();
            $table->string('lateral_university_enrolled', 150)->nullable();
            $table->string('lateral_course_degree', 150)->nullable();
            $table->integer('lateral_units_earned')->nullable();
            $table->integer('lateral_remaining_units')->nullable();

            $table->boolean('research_topic_approved')->nullable();
            $table->string('research_title', 200)->nullable();
            $table->date('last_enrollment_date')->nullable();

            // Step 5: Employment Information
            $table->string('employment_status', 50)->nullable();
            $table->string('employed_position', 150)->nullable();
            $table->string('employed_length_of_service', 50)->nullable();
            $table->string('employed_company_name', 150)->nullable();
            $table->text('employed_company_address')->nullable();
            $table->string('employed_email', 150)->nullable();
            $table->string('employed_website', 150)->nullable();
            $table->string('employed_telephone', 50)->nullable();
            $table->string('employed_fax', 50)->nullable();

            $table->string('self_employed_business_name', 150)->nullable();
            $table->text('self_employed_address')->nullable();
            $table->string('self_employed_email_website', 150)->nullable();
            $table->string('self_employed_telephone', 50)->nullable();
            $table->string('self_employed_fax', 50)->nullable();
            $table->string('self_employed_type_of_business', 100)->nullable();
            $table->string('self_employed_years_of_operation', 50)->nullable();

            // Step 6: Research, Career, Publications & Awards
            $table->longText('research_plans')->nullable();
            $table->longText('career_plans')->nullable();
            $table->json('rnd_involvement')->nullable();
            $table->json('publications')->nullable();
            $table->json('awards')->nullable();

            // Step 7: Upload Documents
            $table->string('birth_certificate_pdf', 255)->nullable();
            $table->string('transcript_of_record_pdf', 255)->nullable();
            $table->string('endorsement_1_pdf', 255)->nullable();
            $table->string('endorsement_2_pdf', 255)->nullable();
            $table->string('recommendation_head_agency_pdf', 255)->nullable();
            $table->string('form_2a_pdf', 255)->nullable();
            $table->string('form_2b_pdf', 255)->nullable();
            $table->string('form_a_research_plans_pdf', 255)->nullable();
            $table->string('form_b_career_plans_pdf', 255)->nullable();
            $table->string('form_c_health_status_pdf', 255)->nullable();
            $table->string('nbi_clearance_pdf', 255)->nullable();
            $table->string('letter_of_admission_pdf', 255)->nullable();
            $table->string('approved_program_of_study_pdf', 255)->nullable();
            $table->string('lateral_certification_pdf', 255)->nullable();

            // Step 8: Declaration / Terms
            $table->boolean('terms_and_conditions_agreed')->default(false);
            $table->string('applicant_signature', 150)->nullable();
            $table->date('declaration_date')->nullable();

            // Status & tracking
            $table->enum('status', [
                'submitted',
                'pending',
                'document_verification',
                'for_interview',
                'approved',
                'rejected'
            ])->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('application_forms');
    }
};
