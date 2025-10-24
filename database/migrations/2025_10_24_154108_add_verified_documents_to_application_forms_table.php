<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->json('verified_documents')->nullable()->after('remarks');
        });
    }

    public function down()
    {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->dropColumn('verified_documents');
        });
    }
};
