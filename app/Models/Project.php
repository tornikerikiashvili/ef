<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasResourceTranslations;

    protected $fillable = [
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
        'resourceTranslations',
    ];

    protected $casts = [
        'gallery' => 'array',
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
        });
    }
}
