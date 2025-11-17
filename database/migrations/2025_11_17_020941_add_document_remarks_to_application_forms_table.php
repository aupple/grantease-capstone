<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            // Add a JSON column to store remarks for each document
            $table->json('document_remarks')->nullable()->after('status');
            
            // Optionally, add a column to track which documents need revision
            $table->json('documents_needing_revision')->nullable()->after('document_remarks');
        });
    }

    public function down(): void {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->dropColumn(['document_remarks', 'documents_needing_revision']);
        });
    }
};