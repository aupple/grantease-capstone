<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ched_enrollment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ched_info_id')->constrained('ched_info_table')->onDelete('cascade');
            
            // Common fields
            $table->string('degree_program')->nullable();
            
            // Table A: Enrolled Scholars, With No Issues
            $table->string('enrollment_status')->nullable(); // Units/Residency/Others
            $table->integer('units_enrolled')->nullable();
            $table->string('retaken_subjects')->nullable(); // Yes/No
            $table->text('remarks')->nullable();
            
            // Table B: Enrolled Scholars, But With Issues
            $table->string('issue_status')->nullable(); // Extension/Retaken/Withdrew/Others
            
            // Table C: Expected to Enroll, But Did Not Enroll
            $table->string('non_enrollment_status')->nullable(); // LOA/AWOL/Did Not Enroll/Others
            
            // Table D: Scholars No Longer Expected to Enroll
            $table->string('termination_status')->nullable(); // Ineligible/Graduated/Withdrawn/Others
            
            // Common fields for B, C, D
            $table->string('others_status')->nullable();
            $table->text('status_description')->nullable();
            
            // Category tracking (a, b, c, or d)
            $table->enum('category', ['a', 'b', 'c', 'd'])->nullable();
            
            $table->timestamps();
            
            // Ensure one record per scholar
            $table->unique('ched_info_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ched_enrollment_reports');
    }
};