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
        Schema::create('remarks', function (Blueprint $table) {
            $table->id('remark_id');
            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('user_id'); // staff/admin giving the remark
            $table->text('remark_text');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('created_by');
        
            $table->foreign('evaluation_id')->references('evaluation_id')->on('evaluations')->onDelete('cascade');
            $table->foreign('application_id')->references('application_id')->on('application_forms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remarks');
    }
};
