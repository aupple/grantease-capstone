<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->year('year_awarded')->nullable()->after('user_id');
            $table->string('degree_type')->nullable()->after('year_awarded'); // e.g., 'masters' or 'doctoral'
            $table->string('status_code')->nullable()->after('degree_type');  // e.g., '4a', '5b', etc.
        });
    }

    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropColumn(['year_awarded', 'degree_type', 'status_code']);
        });
    }
};
