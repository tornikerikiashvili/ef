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
        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        $news = DB::table('news')->get();
        foreach ($news as $n) {
            $decoded = is_string($n->title) ? $n->title : json_decode($n->title, true);
            $title = is_array($decoded) ? ($decoded['en'] ?? 'post') : ($decoded ?: 'post');
            $slug = Str::slug($title ?: 'post-' . $n->id);
            $base = $slug;
            $i = 1;
            while (DB::table('news')->where('slug', $slug)->where('id', '!=', $n->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('news')->where('id', $n->id)->update(['slug' => $slug]);
        }

        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
