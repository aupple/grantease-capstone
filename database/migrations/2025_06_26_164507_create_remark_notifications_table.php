<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('remark_notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->unsignedBigInteger('remark_id');
            $table->unsignedBigInteger('user_id');
            $table->string('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Correct foreign keys
            $table->foreign('remark_id')->references('remark_id')->on('remarks')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('remark_notifications');
    }
};

