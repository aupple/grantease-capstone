<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('remark_attachments', function (Blueprint $table) {
            $table->id('remark_attachment_id');
            $table->unsignedBigInteger('remark_id');
            $table->string('file_path');
            $table->string('file_type');
            $table->timestamps();

            $table->foreign('remark_id')->references('remark_id')->on('remarks')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('remark_attachments');
    }
};