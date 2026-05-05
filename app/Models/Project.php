<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasResourceTranslations;

    protected $fillable = [
        'slug',
        'title',
        'client',
        'area',
        'location',
        'status_text',
        'text_content',
        'status_id',
        'cover_photo',
        'gallery',
        'video_poster',
        'video_url',
        'is_featured',
        'featured_order',
        'resourceTranslations',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
    ];

    public array $translatable = [
        'title',
        'client',
        'area',
        'location',
        'status_text',
        'text_content',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    protected static function booted(): void
    {
        static::saving(function (Project $model): void {
            unset($model->attributes['resourceTranslations']);
            if (empty($model->slug) && $model->title) {
                $title = is_string($model->title) ? $model->title : ($model->getTranslation('title', 'en') ?: $model->getTranslation('title', 'ka') ?: 'project');
                $model->slug = Str::slug($title);
                $i = 1;
                while (static::where('slug', $model->slug)->where('id', '!=', $model->id)->exists()) {
                    $model->slug = Str::slug($title).'-'.$i++;
                }
            }
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public static function youtubeWatchUrl(?string $videoUrl): ?string
    {
        if ($videoUrl === null || trim($videoUrl) === '') {
            return null;
        }

        $videoUrl = trim($videoUrl);

        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $videoUrl, $m)) {
            return 'https://www.youtube.com/watch?v='.$m[1];
        }

        if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $videoUrl, $m)) {
            return 'https://www.youtube.com/watch?v='.$m[1];
        }

        if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/', $videoUrl, $m)) {
            return 'https://www.youtube.com/watch?v='.$m[1];
        }

        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/', $videoUrl, $m)) {
            return 'https://www.youtube.com/watch?v='.$m[1];
        }

        return null;
    }

    public static function youtubeVideoId(?string $videoUrl): ?string
    {
        $watch = self::youtubeWatchUrl($videoUrl);
        if ($watch === null) {
            return null;
        }

        return preg_match('/v=([a-zA-Z0-9_-]{11})/', $watch, $m) ? $m[1] : null;
    }
}
