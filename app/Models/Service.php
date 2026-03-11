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
        'resourceTranslations',
    ];

    protected $casts = [
        'gallery' => 'array',
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
}
