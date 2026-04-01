<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        // Backfill slugs for existing services from title
        $services = DB::table('services')->get();
        foreach ($services as $s) {
            $decoded = is_string($s->title) ? $s->title : json_decode($s->title, true);
            $title = is_array($decoded) ? ($decoded['en'] ?? $decoded['ka'] ?? reset($decoded) ?? 'service') : ($decoded ?: 'service');
            $slug = Str::slug($title ?: 'service-' . $s->id);
            $base = $slug;
            $i = 1;
            while (DB::table('services')->where('slug', $slug)->where('id', '!=', $s->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('services')->where('id', $s->id)->update(['slug' => $slug]);
        }

        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
