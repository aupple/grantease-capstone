<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->bigIncrements('settings_id');
            $table->string('settings_name');
            $table->text('setting_value');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('updated_by')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('system_settings');
    }
};
