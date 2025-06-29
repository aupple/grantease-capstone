<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('application_forms', function (Blueprint $table) {
            $table->id('application_form_id');
            $table->unsignedBigInteger('user_id');
            $table->string('program');
            $table->string('school');
            $table->string('year_level');
            $table->text('reason');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('application_forms');
    }
};
