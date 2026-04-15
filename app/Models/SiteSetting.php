<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    // Keep value flexible (string/number/object/array) depending on setting type.
    protected $casts = [
        'value' => 'json',
    ];

    private static function normalizeScalarOrNull(mixed $value): mixed
    {
        if (is_array($value)) {
            // Common Filament file upload formats:
            // - ['path/to/file.jpg']
            // - ['uuid' => 'path/to/file.jpg' or []]
            $flat = [];
            array_walk_recursive($value, function ($v) use (&$flat) {
                if (is_string($v) && $v !== '') {
                    $flat[] = $v;
                }
            });

            return $flat[0] ?? null;
        }

        return $value;
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $row = static::query()->where('key', $key)->first();
        $value = $row?->value ?? $default;

        return static::normalizeScalarOrNull($value) ?? $default;
    }

    public static function setValue(string $key, mixed $value): self
    {
        $value = static::normalizeScalarOrNull($value);

        return static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }
}
