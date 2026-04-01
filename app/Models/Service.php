<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasResourceTranslations;

    protected $fillable = [
        'slug',
        'title',
        'short_teaser',
        'text_content',
        'cover_photo',
        'gallery',
        'is_featured_in_hero',
        'hero_order',
        'resourceTranslations',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured_in_hero' => 'boolean',
    ];

    public array $translatable = [
        'title',
        'short_teaser',
        'text_content',
    ];

    protected static function booted(): void
    {
        static::saving(function (Service $model): void {
            unset($model->attributes['resourceTranslations']);
            if (empty($model->slug) && $model->title) {
                // Use English title only for slug (no fallback to other locales)
                $title = is_string($model->title)
                    ? $model->title
                    : $model->getTranslation('title', 'en', false);
                $title = $title ?: 'service';
                $model->slug = \Illuminate\Support\Str::slug($title);
                $i = 1;
                while (static::where('slug', $model->slug)->where('id', '!=', $model->id)->exists()) {
                    $model->slug = \Illuminate\Support\Str::slug($title) . '-' . $i++;
                }
            }
        });
    }

    public function scopeFeaturedInHero($query)
    {
        return $query->where('is_featured_in_hero', true);
    }
}
