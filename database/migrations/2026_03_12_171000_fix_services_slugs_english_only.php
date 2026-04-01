<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $services = DB::table('services')->get();

        foreach ($services as $s) {
            $decoded = is_string($s->title) ? $s->title : json_decode($s->title, true);
            $title = is_array($decoded) ? ($decoded['en'] ?? 'service') : ($decoded ?: 'service');
            $slug = Str::slug($title ?: 'service-' . $s->id);
            $base = $slug;
            $i = 1;
            while (DB::table('services')->where('slug', $slug)->where('id', '!=', $s->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('services')->where('id', $s->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // No-op: cannot reliably restore previous slugs
    }
};
