<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            // ✅ Only MS and PhD fields are active
            $table->string('ms_field')->nullable();
            $table->string('ms_school')->nullable();
            $table->string('ms_scholarship')->nullable();
            $table->string('ms_remarks')->nullable();

            $table->string('phd_field')->nullable();
            $table->string('phd_school')->nullable();
            $table->string('phd_scholarship')->nullable();
            $table->string('phd_remarks')->nullable();

            $table->string('strand_category')->nullable();
            $table->string('strand_type')->nullable();
            $table->string('scholarship_type')->nullable();

            $table->string('new_university')->nullable();
            $table->string('new_course')->nullable();
            $table->string('lateral_university')->nullable();
            $table->string('lateral_course')->nullable();
            $table->integer('units_earned')->nullable();
            $table->integer('units_remaining')->nullable();
            $table->boolean('research_approved')->nullable();
            $table->string('research_title')->nullable();
            $table->string('last_thesis_date')->nullable();
        });
    }

    public function down(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            // ✅ Removed bs_* fields that are not part of the up() method
            $table->dropColumn([
                'ms_field', 'ms_school', 'ms_scholarship', 'ms_remarks',
                'phd_field', 'phd_school', 'phd_scholarship', 'phd_remarks',
                'strand_category', 'strand_type', 'scholarship_type',
                'new_university', 'new_course', 'lateral_university', 'lateral_course',
                'units_earned', 'units_remaining', 'research_approved',
                'research_title', 'last_thesis_date'
            ]);
        });
    }
};
