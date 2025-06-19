<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_sheets', function (Blueprint $table) {
            $table->id('score_sheet_id');
            $table->unsignedBigInteger('evaluation_id');
            $table->string('criteria_name');
            $table->integer('score');
            $table->date('date_of_interview')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        
            $table->foreign('evaluation_id')->references('evaluation_id')->on('evaluations')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_sheets');
    }
};
