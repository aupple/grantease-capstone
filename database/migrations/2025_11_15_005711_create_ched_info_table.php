<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void 
    {
        Schema::create('ched_info_table', function (Blueprint $table) {
            $table->id();

            // FIXED foreign key
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');

            // ✅ ADD STATUS COLUMN HERE
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Step 1 fields
            $table->string('academic_year');
            $table->string('school_term');
            $table->string('school')->nullable();
            $table->string('year_level')->nullable();
            $table->string('application_no')->unique();
            $table->string('passport_photo');

            // Personal Information
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix', 10)->nullable();

            // Address
            $table->string('province');
            $table->string('city');
            $table->string('barangay');
            $table->string('street');
            $table->string('house_no')->nullable();
            $table->string('zip_code');
            $table->string('region');
            $table->string('district')->nullable();
            $table->string('passport_no')->nullable();

            // Contact Information
            $table->string('email');
            $table->string('mailing_address')->nullable();
            $table->string('contact_no', 15);

            // Personal Details
            $table->string('civil_status');
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->string('sex');

            // Parents
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();

            $table->timestamps();   
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ched_info_table');
    }
};