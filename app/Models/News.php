<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasResourceTranslations;

    protected $fillable = [
        'title',
        'teaser',
        'text_content',
        'news_category_id',
        'cover_photo',
        'gallery',
        'resourceTranslations',
    ];

    protected $casts = [
        'gallery' => 'array',
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

    protected static function booted(): void
    {
        static::saving(function (News $model): void {
            unset($model->attributes['resourceTranslations']);
        });
    }
}
