<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['news', 'projects', 'services'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->json('meta_title')->nullable()->after('text_content');
                $table->json('meta_description')->nullable()->after('meta_title');
                $table->json('og_title')->nullable()->after('meta_description');
                $table->json('og_description')->nullable()->after('og_title');
                $table->string('og_image')->nullable()->after('og_description');
            });
        }
    }

    public function down(): void
    {
        foreach (['news', 'projects', 'services'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn([
                    'meta_title',
                    'meta_description',
                    'og_title',
                    'og_description',
                    'og_image',
                ]);
            });
        }
    }
};
