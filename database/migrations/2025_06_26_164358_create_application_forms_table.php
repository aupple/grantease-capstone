<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('application_forms', function (Blueprint $table) {
            $table->id('application_form_id');
            $table->unsignedBigInteger('user_id');
            $table->string('program');
            $table->string('school');
            $table->string('year_level');
            $table->text('reason');

            // ✅ Add this line for the status tracking
            $table->enum('status', [
                'submitted',
                'under_review',
                'document_verification',
                'for_interview',
                'approved',
                'rejected'
            ])->default('submitted');

            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('application_forms');
    }
};
