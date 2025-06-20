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
        Schema::create('application_forms', function (Blueprint $table) {
    $table->id('application_id');
    $table->unsignedBigInteger('user_id');
    $table->string('scholarship_type');
    $table->string('status');
    $table->date('date_submitted')->nullable();
    $table->date('updated_at')->nullable();

    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_forms');
    }
};
