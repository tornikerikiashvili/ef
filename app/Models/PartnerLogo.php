<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;

class PartnerLogo extends Model
{
    use HasResourceTranslations;

    protected $table = 'partner_logos';

    protected $fillable = [
        'title',
        'link',
        'logo_white',
        'logo_colorful',
        'resourceTranslations',
    ];

    public array $translatable = [
        'title',
        'link',
        'logo_white',
        'logo_colorful',
    ];

    protected static function booted(): void
    {
        static::saving(function (PartnerLogo $model): void {
            unset($model->attributes['resourceTranslations']);
        });
    }
}
