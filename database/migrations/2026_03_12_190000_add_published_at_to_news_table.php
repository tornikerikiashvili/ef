<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dateTime('published_at')->nullable()->after('news_category_id');
        });

        // Backfill: set published_at = created_at for existing records
        DB::table('news')->whereNull('published_at')->update([
            'published_at' => DB::raw('created_at'),
        ]);
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
};
