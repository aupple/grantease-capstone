<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->string('employment_status')->nullable();
            $table->string('position')->nullable();
            $table->string('length_of_service')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_fax')->nullable();

            $table->string('business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_type')->nullable();
            $table->string('years_operation')->nullable();

            $table->longText('research_plans')->nullable();
            $table->longText('career_plans')->nullable();
            $table->longText('rnd_involvement')->nullable();
            $table->longText('publications')->nullable();
            $table->longText('awards')->nullable();
        });
    }

    public function down(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->dropColumn([
                'employment_status', 'position', 'length_of_service',
                'company_name', 'company_address', 'company_email',
                'company_website', 'company_phone', 'company_fax',
                'business_name', 'business_address', 'business_email',
                'business_type', 'years_operation',
                'research_plans', 'career_plans',
                'rnd_involvement', 'publications', 'awards'
            ]);
        });
    }
};
