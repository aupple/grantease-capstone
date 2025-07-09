<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('application_forms', 'academic_score')) {
                $table->float('academic_score')->nullable()->after('reason');
            }
            if (!Schema::hasColumn('application_forms', 'financial_score')) {
                $table->float('financial_score')->nullable()->after('academic_score');
            }
            if (!Schema::hasColumn('application_forms', 'interview_score')) {
                $table->float('interview_score')->nullable()->after('financial_score');
            }
            // Don't add 'remarks' again — it already exists.
        });
    }

    public function down(): void
    {
        Schema::table('application_forms', function (Blueprint $table) {
            // Only drop if they exist
            if (Schema::hasColumn('application_forms', 'academic_score')) {
                $table->dropColumn('academic_score');
            }
            if (Schema::hasColumn('application_forms', 'financial_score')) {
                $table->dropColumn('financial_score');
            }
            if (Schema::hasColumn('application_forms', 'interview_score')) {
                $table->dropColumn('interview_score');
            }
        });
    }
};
