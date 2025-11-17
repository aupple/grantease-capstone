<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('remarks', function (Blueprint $table) {
            $table->id('remark_id');
            $table->unsignedBigInteger('application_form_id');
            $table->string('document_name'); // e.g., "Birth Certificate", "NBI Clearance"
            $table->text('remark_text');
            $table->timestamps();

            $table->foreign('application_form_id')
                  ->references('application_form_id')
                  ->on('application_forms')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('remarks');
    }
};