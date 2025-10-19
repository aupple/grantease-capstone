<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('application_forms', function (Blueprint $table) {
    $table->string('thesis_title', 255)->nullable()->after('research_title');
    $table->integer('units_required')->nullable()->after('thesis_title');
    $table->string('duration', 100)->nullable()->after('units_required');
    });
}

public function down(): void
{
    Schema::table('application_forms', function (Blueprint $table) {
        $table->dropColumn(['thesis_title', 'units_required', 'duration']);
    });
}

};
