<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ched_grade_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ched_info_id');
            $table->foreign('ched_info_id')->references('id')->on('ched_info_table')->onDelete('cascade');
            
            $table->string('degree_program')->nullable();
            $table->integer('enrolled_subjects')->nullable();
            $table->integer('subjects_passed')->nullable();
            $table->integer('incomplete_grades')->nullable();
            $table->integer('subjects_failed')->nullable();
            $table->integer('no_grades')->nullable();
            $table->integer('not_credited_subjects')->nullable();
            $table->string('status')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->text('remarks')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ched_grade_reports');
    }
};