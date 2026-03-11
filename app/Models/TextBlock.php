<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;

class TextBlock extends Model
{
    use HasResourceTranslations;

    protected $table = 'text_blocks';

    protected $fillable = [
        'title',
        'short_teaser',
        'image',
        'resourceTranslations',
    ];

    public array $translatable = [
        'title',
        'short_teaser',
    ];

    protected static function booted(): void
    {
        static::saving(function (TextBlock $model): void {
            unset($model->attributes['resourceTranslations']);
        });
    }
}
