<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ched_continuing_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ched_info_id')->constrained('ched_info_table')->onDelete('cascade');
            
            // Table A: Continuing Scholars
            $table->string('scholarship_type')->nullable();
            $table->string('degree_program')->nullable();
            $table->string('year_of_approval')->nullable();
            $table->string('last_term_enrollment')->nullable();
            $table->boolean('good_academic_standing')->nullable();
            $table->text('standing_explanation')->nullable();
            $table->boolean('finish_on_time')->nullable();
            $table->text('finish_explanation')->nullable();
            $table->string('recommendation')->nullable();
            $table->text('rationale')->nullable();
            
            // Table B: Scholars Who Have Completed Their Degrees
            $table->string('academic_year_graduation')->nullable();
            $table->string('term_of_graduation')->nullable();
            $table->text('remarks')->nullable();
            
            // Category tracking (a or b)
            $table->enum('category', ['a', 'b'])->nullable();
            
            $table->timestamps();
            
            // Ensure one record per scholar
            $table->unique('ched_info_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ched_continuing_reports');
    }
};