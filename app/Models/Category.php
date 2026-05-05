<?php

namespace App\Models;

use App\Models\Concerns\HasResourceTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasResourceTranslations;

    protected $fillable = ['name', 'slug', 'resourceTranslations'];

    protected $casts = [
        'name' => 'array',
    ];

    public array $translatable = [
        'name',
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }
}
