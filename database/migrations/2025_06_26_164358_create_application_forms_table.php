<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('application_forms', function (Blueprint $table) {
            $table->id('application_form_id');
            $table->unsignedBigInteger('user_id');

            // Basic info
            $table->string('program', 100)->nullable();
            $table->string('school', 150)->nullable();
            $table->string('year_level', 50)->nullable();
            $table->text('reason')->nullable();

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

            // Personal fields
            $table->string('last_name', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('contact', 50)->nullable();
            $table->string('dob', 50)->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('citizenship', 100)->nullable();
            $table->string('birthplace', 150)->nullable();
            $table->text('home_address')->nullable();
            $table->text('current_address')->nullable();
            $table->string('civil_status', 50)->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('age')->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('father_name', 150)->nullable();
            $table->string('father_occupation', 150)->nullable();
            $table->string('mother_name', 150)->nullable();
            $table->string('mother_occupation', 150)->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('passport_no', 50)->nullable();
            $table->string('phone_number', 30)->nullable();

            // Academic fields
            $table->string('academic_year', 20)->nullable();
            $table->string('school_term', 20)->nullable();
            $table->string('application_no', 50)->nullable();

            // BS
            $table->string('bs_field', 100)->nullable();
            $table->string('bs_university', 150)->nullable();
            $table->string('bs_scholarship_type', 100)->nullable();
            $table->string('bs_scholarship_others', 100)->nullable();
            $table->string('bs_remarks', 150)->nullable();
            $table->string('bs_school', 150)->nullable();
            $table->string('bs_course', 150)->nullable();
            $table->string('bs_grad_year', 10)->nullable();
            $table->string('bs_honors', 150)->nullable();

            // MS
            $table->string('ms_field', 100)->nullable();
            $table->string('ms_school', 150)->nullable();
            $table->string('ms_course', 150)->nullable();
            $table->string('ms_grad_year', 10)->nullable();
            $table->string('ms_honors', 150)->nullable();
            $table->string('ms_scholarship', 100)->nullable();
            $table->string('ms_scholarship_type', 100)->nullable();
            $table->string('ms_remarks', 150)->nullable();

            // PhD
            $table->string('phd_field', 100)->nullable();
            $table->string('phd_school', 150)->nullable();
            $table->string('phd_course', 150)->nullable();
            $table->string('phd_grad_year', 10)->nullable();
            $table->string('phd_honors', 150)->nullable();
            $table->string('phd_scholarship', 100)->nullable();
            $table->string('phd_scholarship_type', 100)->nullable();
            $table->string('phd_remarks', 150)->nullable();

            $table->string('graduate_strand', 100)->nullable();
            $table->string('strand_category', 100)->nullable();
            $table->string('strand_type', 100)->nullable();

            $table->string('application_type', 100)->nullable();
            $table->string('scholarship_type', 100)->nullable();
            $table->string('entry_status', 100)->nullable();

            $table->string('new_university', 150)->nullable();
            $table->string('new_course', 150)->nullable();
            $table->string('lateral_university', 150)->nullable();
            $table->string('lateral_course', 150)->nullable();

            $table->integer('units_earned')->nullable();
            $table->integer('units_remaining')->nullable();
            $table->boolean('research_approved')->nullable();
            $table->string('research_title', 200)->nullable();
            $table->string('last_thesis_date', 50)->nullable();

            // Employment
            $table->string('employed', 50)->nullable();
            $table->string('employer', 150)->nullable();
            $table->string('position', 150)->nullable();
            $table->string('employment_status', 100)->nullable();
            $table->string('length_of_service', 50)->nullable();

            $table->string('company_name', 150)->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_email', 150)->nullable();
            $table->string('company_website', 150)->nullable();
            $table->string('company_phone', 50)->nullable();
            $table->string('company_fax', 50)->nullable();

            $table->string('business_name', 150)->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_email', 150)->nullable();
            $table->string('business_type', 100)->nullable();
            $table->string('years_operation', 50)->nullable();

            $table->string('studying', 50)->nullable();
            $table->string('school_name', 150)->nullable();
            $table->string('school_course', 150)->nullable();
            $table->string('school_type', 100)->nullable();

            $table->string('government_service', 50)->nullable();
            $table->string('gs_details', 200)->nullable();

            // Research, career, involvement
            $table->longText('research_plans')->nullable();
            $table->longText('career_plans')->nullable();
            $table->string('research_scope', 150)->nullable();
            $table->string('research_timeline', 150)->nullable();
            $table->longText('rnd_involvement')->nullable();
            $table->longText('publications')->nullable();
            $table->longText('awards')->nullable();

            // File uploads
            $table->string('passport_picture', 255)->nullable();
            $table->string('form137', 255)->nullable();
            $table->string('cert_employment', 255)->nullable();
            $table->string('cert_purpose', 255)->nullable();
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

            // Declaration
            $table->boolean('terms_and_conditions_agreed')->default(false);
            $table->string('applicant_signature', 150)->nullable();
            $table->date('declaration_date')->nullable();

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