<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('submitted_by'); // admin or user submitting the report
            $table->string('type'); // e.g., 'evaluation', 'summary', etc.
            $table->text('content')->nullable();
            $table->timestamps();

            // Correct foreign key constraint
            $table->foreign('submitted_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('reports');
    }
};
