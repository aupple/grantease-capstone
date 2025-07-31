<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->string('passport_picture')->nullable()->after('remarks');
            $table->string('form137')->nullable()->after('passport_picture');
            $table->string('cert_employment')->nullable()->after('form137');
            $table->string('cert_purpose')->nullable()->after('cert_employment');
        });
    }

    public function down(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->dropColumn([
                'passport_picture',
                'form137',
                'cert_employment',
                'cert_purpose',
            ]);
        });
    }
};
