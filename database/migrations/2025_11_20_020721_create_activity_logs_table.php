<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('action');
        $table->text('details')->nullable();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->timestamp('created_at');
        
        $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        $table->index('user_id');
        $table->index('created_at');
    });
}

public function down()
{
    Schema::dropIfExists('activity_logs');
}
};
