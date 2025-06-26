<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('remark_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('remark_id');
            $table->unsignedBigInteger('attachment_id');

            $table->primary(['remark_id', 'attachment_id']);

            $table->foreign('remark_id')->references('remark_id')->on('remarks')->onDelete('cascade');
            $table->foreign('attachment_id')->references('attachment_id')->on('attachments')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('remark_attachments');
    }
};
