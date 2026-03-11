<?php

namespace App\Models\Concerns;

use Spatie\Translatable\HasTranslations;

/**
 * Trait for models that use Spatie Translatable and expose
 * a single "resourceTranslations" attribute for Filament forms
 * (locale-keyed array of translatable attributes).
 */
trait HasResourceTranslations
{
    use HasTranslations;

    public function initializeHasResourceTranslations(): void
    {
        if (! $this->isFillable('resourceTranslations')) {
            $this->mergeFillable(['resourceTranslations']);
        }
        if (! in_array('resourceTranslations', $this->appends ?? [])) {
            $this->append(array_merge($this->appends ?? [], ['resourceTranslations']));
        }
    }

    public function setResourceTranslationsAttribute($value): void
    {
        if (is_array($value)) {
            foreach ($value as $locale => $translations) {
                if (! is_array($translations)) {
                    continue;
                }
                foreach ($translations as $attribute => $translation) {
                    if (in_array($attribute, $this->getTranslatableAttributes(), true)) {
                        $this->setTranslation($attribute, $locale, $translation);
                    }
                }
            }
        }
    }

    public function getResourceTranslationsAttribute(): array
    {
        $translations = [];
        foreach ($this->getTranslatableAttributes() as $attribute) {
            foreach (array_keys($this->getTranslations($attribute) ?? []) as $locale) {
                $translations[$locale][$attribute] = $this->getTranslation($attribute, $locale);
            }
        }
        return $translations;
    }
}
