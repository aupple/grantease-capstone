<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attachments', function (Blueprint $table) {
            $table->bigIncrements('attachment_id');
            $table->unsignedBigInteger('application_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->timestamp('uploaded_at')->nullable();

            $table->foreign('application_id')->references('application_id')->on('application_forms')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('attachments');
    }
};
