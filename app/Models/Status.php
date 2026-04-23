<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasResourceTranslations;

    protected $fillable = ['name', 'slug', 'resourceTranslations'];

    protected $casts = [
        'name' => 'array',
    ];

    public array $translatable = [
        'name',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
