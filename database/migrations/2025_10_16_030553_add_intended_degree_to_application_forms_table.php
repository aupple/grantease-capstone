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
        $table->string('intended_degree', 150)->nullable()->after('duration');
    });
}

public function down(): void
{
    Schema::table('application_forms', function (Blueprint $table) {
        $table->dropColumn('intended_degree');
    });
}

};
