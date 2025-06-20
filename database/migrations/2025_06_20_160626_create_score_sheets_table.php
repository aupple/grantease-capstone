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
        Schema::create('score_sheets', function (Blueprint $table) {
    $table->id('score_sheet_id');
    $table->unsignedBigInteger('evaluation_id');
    $table->string('criteria_name');
    $table->decimal('score', 5, 2);
    $table->date('date_of_interview')->nullable();
    $table->string('status');

    $table->foreign('evaluation_id')->references('evaluation_id')->on('evaluations')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_sheets');
    }
};
