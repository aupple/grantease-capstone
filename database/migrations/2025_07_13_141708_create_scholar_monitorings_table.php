<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scholar_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained('scholars')->onDelete('cascade');
            $table->string('course')->nullable();
            $table->string('school')->nullable();
            $table->string('enrollment_type')->nullable();
            $table->string('scholarship_duration')->nullable();
            $table->string('date_started')->nullable();
            $table->string('expected_completion')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholar_monitorings');
    }
};
