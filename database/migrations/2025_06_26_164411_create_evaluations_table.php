<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
       Schema::create('evaluations', function (Blueprint $table) {
    $table->id('evaluation_id');
    $table->unsignedBigInteger('application_form_id');
    $table->unsignedBigInteger('evaluator_id');

    // Documentary Evaluation Fields
    $table->string('scholarship_program')->nullable();
    $table->string('graduation_year')->nullable();
    $table->string('course_school')->nullable();

    $table->string('program_study')->nullable();
    $table->string('school')->nullable();
    $table->date('date_admission')->nullable();

    $table->string('gwa')->nullable();
    $table->string('honors')->nullable();

    $table->string('current_employer')->nullable();
    $table->string('years_service')->nullable();
    $table->string('dep_ed_status')->nullable();
    $table->boolean('dep_ed_vsat_rating')->nullable();

    $table->string('non_dep_ed_employer')->nullable();
    $table->string('non_dep_ed_years')->nullable();

    $table->string('other_employer')->nullable();
    $table->string('other_years')->nullable();

    $table->boolean('physically_fit')->nullable();
    $table->text('health_comments')->nullable();

    $table->string('age')->nullable();

    $table->string('endorsement_1')->nullable();
    $table->string('endorsement_2')->nullable();

    $table->string('graduate_units')->nullable();
    $table->string('ms_phd_gwa')->nullable();

    $table->text('remarks')->nullable();
    $table->string('decision')->nullable();
    $table->string('evaluator_name')->nullable();
    $table->date('evaluation_date')->nullable();

    $table->timestamps();

    $table->foreign('application_form_id')->references('application_form_id')->on('application_forms')->onDelete('cascade');
    $table->foreign('evaluator_id')->references('user_id')->on('users')->onDelete('cascade');
});

    }

    public function down(): void {
        Schema::dropIfExists('evaluations');
    }
};
