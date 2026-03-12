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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        // Backfill slugs for existing projects from title
        $projects = DB::table('projects')->get();
        foreach ($projects as $p) {
            $decoded = is_string($p->title) ? $p->title : json_decode($p->title, true);
            $title = is_array($decoded) ? ($decoded['en'] ?? $decoded['ka'] ?? reset($decoded) ?? 'project') : ($decoded ?: 'project');
            $slug = Str::slug($title ?: 'project-' . $p->id);
            $base = $slug;
            $i = 1;
            while (DB::table('projects')->where('slug', $slug)->where('id', '!=', $p->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('projects')->where('id', $p->id)->update(['slug' => $slug]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
