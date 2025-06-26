<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('remark_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('remark_id');
            $table->unsignedBigInteger('notification_id');

            $table->primary(['remark_id', 'notification_id']);

            $table->foreign('remark_id')->references('remark_id')->on('remarks')->onDelete('cascade');
            $table->foreign('notification_id')->references('notification_id')->on('notifications')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('remark_notifications');
    }
};
