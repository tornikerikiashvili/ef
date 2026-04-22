<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'instagram_link')) {
                $table->dropColumn('instagram_link');
            }
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (! Schema::hasColumn('galleries', 'instagram_link')) {
                $table->string('instagram_link', 2048)->nullable()->after('name');
            }
        });
    }
};
