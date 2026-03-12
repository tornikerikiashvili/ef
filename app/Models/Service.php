<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasResourceTranslations;

    protected $fillable = [
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
        });
    }

    public function scopeFeaturedInHero($query)
    {
        return $query->where('is_featured_in_hero', true);
    }
}
