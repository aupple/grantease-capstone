<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('program_study')->nullable();
            $table->string('specialization')->nullable();
            $table->string('scholarship_program')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('course_school')->nullable();
            $table->string('admission_program')->nullable();
            $table->string('admission_school')->nullable();
            $table->date('admission_date')->nullable();
            $table->string('academic_gpa')->nullable();
            $table->string('academic_honors')->nullable();
            $table->string('employer_deped')->nullable();
            $table->string('years_service')->nullable();
            $table->string('reg_deped')->nullable();
            $table->string('part_deped')->nullable();
            $table->boolean('performance_rating')->nullable();
            $table->boolean('physically_fit')->nullable();
            $table->text('health_comments')->nullable();
            $table->integer('age')->nullable();
            $table->string('endorsement_1')->nullable();
            $table->string('endorsement_2')->nullable();
            $table->string('grad_units')->nullable();
            $table->string('gpa_lateral')->nullable();
            $table->string('decision')->nullable();
            $table->string('evaluator_name')->nullable();
            $table->date('evaluation_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn([
                'program_study',
                'specialization',
                'scholarship_program',
                'graduation_year',
                'course_school',
                'admission_program',
                'admission_school',
                'admission_date',
                'academic_gpa',
                'academic_honors',
                'employer_deped',
                'years_service',
                'reg_deped',
                'part_deped',
                'performance_rating',
                'physically_fit',
                'health_comments',
                'age',
                'endorsement_1',
                'endorsement_2',
                'grad_units',
                'gpa_lateral',
                'decision',
                'evaluator_name',
                'evaluation_date',
            ]);
        });
    }
};
