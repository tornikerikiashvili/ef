<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'category_id',
        'status_id',
        'cover_photo',
        'gallery',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
                $model->slug = \Illuminate\Support\Str::slug($title);
                $i = 1;
                while (static::where('slug', $model->slug)->where('id', '!=', $model->id)->exists()) {
                    $model->slug = \Illuminate\Support\Str::slug($title) . '-' . $i++;
                }
            }
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
