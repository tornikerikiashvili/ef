<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['project_id', 'category_id']);
        });

        foreach (DB::table('projects')->whereNotNull('category_id')->cursor() as $row) {
            DB::table('category_project')->insert([
                'project_id' => $row->id,
                'category_id' => $row->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('category_id');
            $table->string('video_poster')->nullable()->after('gallery');
            $table->string('video_url', 2048)->nullable()->after('video_poster');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['video_poster', 'video_url']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('text_content')->constrained()->nullOnDelete();
        });

        foreach (DB::table('category_project')->orderBy('id')->cursor() as $row) {
            DB::table('projects')->where('id', $row->project_id)->whereNull('category_id')->update([
                'category_id' => $row->category_id,
            ]);
        }

        Schema::dropIfExists('category_project');
    }
};
