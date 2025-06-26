<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->bigIncrements('evaluation_id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('user_id');
            $table->date('evaluation_date');
            $table->text('comments')->nullable();
            $table->string('status');
            $table->text('criteria')->nullable();
            $table->text('indicators')->nullable();

            $table->foreign('application_id')->references('application_id')->on('application_forms')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('evaluations');
    }
};

