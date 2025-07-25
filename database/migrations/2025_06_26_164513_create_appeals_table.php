<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('appeals', function (Blueprint $table) {
            $table->id('appeal_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_form_id');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Correct foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('application_form_id')->references('application_form_id')->on('application_forms')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('appeals');
    }
};

