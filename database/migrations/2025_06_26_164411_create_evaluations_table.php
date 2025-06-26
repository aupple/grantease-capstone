<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id('evaluation_id');
            $table->unsignedBigInteger('application_form_id');
            $table->unsignedBigInteger('evaluator_id');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('application_form_id')->references('application_form_id')->on('application_forms')->onDelete('cascade');
            $table->foreign('evaluator_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('evaluations');
    }
};
