<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixProjectSlugs extends Command
{
    protected $signature = 'projects:fix-slugs';

    protected $description = 'Regenerate URL-safe slugs for all projects';

    public function handle(): int
    {
        $projects = Project::all();
        $fixed = 0;

        foreach ($projects as $project) {
            $title = is_string($project->title)
                ? $project->title
                : ($project->getTranslation('title', 'en', false) ?: $project->getTranslation('title', 'ka', false));
            $title = $title ?: 'project-' . $project->id;
            $newSlug = Str::slug($title);
            $base = $newSlug;
            $i = 1;
            while (Project::where('slug', $newSlug)->where('id', '!=', $project->id)->exists()) {
                $newSlug = $base . '-' . $i++;
            }
            if ($project->slug !== $newSlug) {
                $project->update(['slug' => $newSlug]);
                $fixed++;
                $this->line("Project {$project->id}: {$project->slug} → {$newSlug}");
            }
        }

        $this->info("Fixed {$fixed} project slug(s).");
        return self::SUCCESS;
    }
}
