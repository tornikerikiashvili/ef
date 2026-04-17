<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('setting_blocks') && ! Schema::hasTable('pages')) {
            Schema::rename('setting_blocks', 'pages');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pages') && ! Schema::hasTable('setting_blocks')) {
            Schema::rename('pages', 'setting_blocks');
        }
    }
};
