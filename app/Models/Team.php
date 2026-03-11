<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasResourceTranslations;

    protected $fillable = [
        'title',
        'text_content',
        'image',
        'resourceTranslations',
    ];

    public array $translatable = [
        'title',
        'text_content',
    ];

    protected static function booted(): void
    {
        static::saving(function (Team $model): void {
            unset($model->attributes['resourceTranslations']);
        });
    }
}
