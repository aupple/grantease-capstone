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
        Schema::create('remark_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('remark_id');
            $table->unsignedBigInteger('attachment_id');
        
            $table->foreign('remark_id')->references('remark_id')->on('remarks')->onDelete('cascade');
            $table->foreign('attachment_id')->references('attachment_id')->on('attachments')->onDelete('cascade');
        
            $table->primary(['remark_id', 'attachment_id']);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remark_notifications');
    }
};
