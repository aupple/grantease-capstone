<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->string('permanent_address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('current_address')->nullable();
            $table->string('civil_status')->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('age')->nullable();
            $table->string('sex')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('phone_number')->nullable();
        });
    }

    public function down(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->dropColumn([
                'permanent_address', 'zip_code', 'region', 'district', 'passport_no',
                'current_address', 'civil_status', 'birthdate', 'age', 'sex',
                'father_name', 'mother_name', 'phone_number'
            ]);
        });
    }
};
