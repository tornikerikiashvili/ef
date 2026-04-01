<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasResourceTranslations;

    protected $fillable = [
        'slug',
        'title',
        'teaser',
        'text_content',
        'news_category_id',
        'published_at',
        'is_featured',
        'featured_order',
        'cover_photo',
        'gallery',
        'resourceTranslations',
    ];

    protected $casts = [
        'gallery' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public array $translatable = [
        'title',
        'teaser',
        'text_content',
    ];

    public function newsCategory(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    protected static function booted(): void
    {
        static::saving(function (News $model): void {
            unset($model->attributes['resourceTranslations']);
            if (empty($model->slug) && $model->title) {
                $title = is_string($model->title) ? $model->title : $model->getTranslation('title', 'en', false);
                $title = $title ?: 'post';
                $model->slug = \Illuminate\Support\Str::slug($title);
                $i = 1;
                while (static::where('slug', $model->slug)->where('id', '!=', $model->id)->exists()) {
                    $model->slug = \Illuminate\Support\Str::slug($title) . '-' . $i++;
                }
            }
        });
    }
}
