<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('remarks', function (Blueprint $table) {
            $table->id('remark_id');
            $table->unsignedBigInteger('evaluation_id');
            $table->text('content');
            $table->timestamps();

            $table->foreign('evaluation_id')->references('evaluation_id')->on('evaluations')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('remarks');
    }
};
