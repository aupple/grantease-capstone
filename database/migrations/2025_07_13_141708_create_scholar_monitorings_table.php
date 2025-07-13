<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scholar_monitorings', function (Blueprint $table) {
            $table->id();
            $table->year('year_awarded')->nullable();
            $table->string('degree_type');
            $table->string('status_code');
            $table->integer('total')->default(0);
            // Optional: $table->timestamps(); — if you want to track created_at/updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholar_monitorings');
    }
};
