<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'name',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }

    /**
     * @return list<string>
     */
    public function imagePaths(): array
    {
        $images = $this->images ?? [];
        if (! is_array($images)) {
            return [];
        }

        $out = [];
        foreach ($images as $path) {
            if (is_string($path) && $path !== '') {
                $out[] = $path;
            }
        }

        return $out;
    }
}
