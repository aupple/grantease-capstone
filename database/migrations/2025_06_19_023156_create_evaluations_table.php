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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id('evaluation_id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('user_id'); // staff evaluator
            $table->date('evaluation_date')->nullable();
            $table->string('status')->default('pending');
            $table->text('criteria')->nullable();
            $table->text('indicators')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        
            $table->foreign('application_id')->references('application_id')->on('application_forms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};
