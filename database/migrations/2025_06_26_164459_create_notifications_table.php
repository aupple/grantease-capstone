<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // Class name of the notification
            $table->morphs('notifiable'); // Polymorphic relation (e.g., user, admin)
            $table->string('title')->nullable(); // Custom field for title
            $table->text('message')->nullable(); // Custom field for message
            $table->json('data'); // Laravel standard: payload in JSON
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('notifications');
    }
};
