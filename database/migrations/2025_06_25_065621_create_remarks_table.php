<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('remarks', function (Blueprint $table) {
            $table->bigIncrements('remark_id');
            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('user_id');
            $table->text('remark_text');
            $table->text('remark_note')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('created_by')->nullable();

            $table->foreign('evaluation_id')->references('evaluation_id')->on('evaluations')->onDelete('cascade');
            $table->foreign('application_id')->references('application_id')->on('application_forms')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('remarks');
    }
};
