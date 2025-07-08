<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('scholars', function (Blueprint $table) {
            $table->id();

            // FK to users table using user_id
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            // FK to application_forms table using application_form_id
            $table->unsignedBigInteger('application_form_id')->nullable();
            $table->foreign('application_form_id')->references('application_form_id')->on('application_forms')->onDelete('set null');

            // Scholar status (for pie chart)
            $table->string('status');

            // Duration tracking
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('scholars');
    }
};
