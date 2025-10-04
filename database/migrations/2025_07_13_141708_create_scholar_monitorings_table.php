<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('scholar_monitorings', function (Blueprint $table) {
            $table->id();
             $table->foreignId('scholar_id')->constrained('scholars')->onDelete('cascade');
            
            // Scholar identity
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();

            // Academic details
            $table->enum('level', ['MS', 'PhD']);
            $table->string('course');
            $table->string('school');

            // Enrollment details
            $table->enum('new_or_lateral', ['NEW', 'LATERAL'])->nullable();
            $table->enum('enrollment_type', ['FULL TIME', 'PART TIME'])->nullable();
            $table->string('scholarship_duration')->nullable();
            $table->string('date_started')->nullable();          // flexible text field
            $table->string('expected_completion')->nullable();

            // Monitoring summary (from old table)
            $table->year('year_awarded')->nullable();
            $table->string('degree_type')->nullable();  // masters / doctoral
            $table->string('status_code')->nullable();  // G, D, etc.
            $table->integer('total')->default(0);

            // Current status + notes
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('scholar_monitorings');
    }
};
