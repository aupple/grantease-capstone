<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('score_sheets', function (Blueprint $table) {
            $table->bigIncrements('score_sheet_id');
            $table->unsignedBigInteger('evaluation_id');
            $table->string('criteria_name');
            $table->integer('score');
            $table->date('date_of_interview');
            $table->string('status');

            $table->foreign('evaluation_id')->references('evaluation_id')->on('evaluations')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('score_sheets');
    }
};
