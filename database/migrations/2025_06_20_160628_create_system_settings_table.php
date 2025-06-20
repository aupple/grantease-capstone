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
        Schema::create('system_settings', function (Blueprint $table) {
    $table->id('settings_id');
    $table->unsignedBigInteger('updated_by_user_id');
    $table->string('settings_name');
    $table->string('setting_value');
    $table->timestamp('updated_at')->useCurrent();

    $table->foreign('updated_by_user_id')->references('user_id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
