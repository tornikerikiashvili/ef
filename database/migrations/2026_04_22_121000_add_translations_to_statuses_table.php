<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            UPDATE `statuses`
            SET `name` = JSON_OBJECT('en', `name`)
            WHERE `name` IS NOT NULL
              AND JSON_VALID(`name`) = 0
        ");

        Schema::table('statuses', function (Blueprint $table) {
            // Spatie Translatable stores translations as JSON in the same column.
            $table->json('name')->change();
        });
    }

    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->string('name')->change();
        });
    }
};

