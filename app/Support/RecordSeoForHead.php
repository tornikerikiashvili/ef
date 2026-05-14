<?php

namespace App\Support;

use App\Models\News;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Builds public HTML head SEO arrays from News / Project / Service records, with fallbacks
 * when optional SEO fields are empty (title + ~160 char plain-text excerpt).
 */
final class RecordSeoForHead
{
    /** Google-style snippet length for auto-generated descriptions. */
    public const DESCRIPTION_FALLBACK_LIMIT = 160;

    /**
     * @return array{
     *   meta_title: string,
     *   meta_description: string,
     *   og_title: string,
     *   og_description: string,
     *   og_image: string|null
     * }
     */
    public static function forNews(News $news): array
    {
        $locale = app()->getLocale();
        $title = self::translationChain($news, 'title', $locale);
        $metaTitle = self::translationChain($news, 'meta_title', $locale) ?: $title;

        $metaDescCustom = self::translationChain($news, 'meta_description', $locale);
        $metaDesc = $metaDescCustom !== ''
            ? self::plainExcerpt($metaDescCustom, 320)
            : self::plainExcerpt(
                self::translationChain($news, 'teaser', $locale)
                    ?: self::translationChain($news, 'text_content', $locale),
                self::DESCRIPTION_FALLBACK_LIMIT
            );

        $ogTitle = self::translationChain($news, 'og_title', $locale) ?: $metaTitle;
        $ogDesc = self::translationChain($news, 'og_description', $locale) ?: $metaDesc;

        $ogImage = self::ogImagePath($news->og_image ?? null, $news->cover_photo ?? null);

        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'og_title' => $ogTitle,
            'og_description' => $ogDesc,
            'og_image' => $ogImage,
        ];
    }

    /**
     * @return array{
     *   meta_title: string,
     *   meta_description: string,
     *   og_title: string,
     *   og_description: string,
     *   og_image: string|null
     * }
     */
    public static function forProject(Project $project): array
    {
        $locale = app()->getLocale();
        $title = self::translationChain($project, 'title', $locale);
        $metaTitle = self::translationChain($project, 'meta_title', $locale) ?: $title;

        $metaDescCustom = self::translationChain($project, 'meta_description', $locale);
        $metaDesc = $metaDescCustom !== ''
            ? self::plainExcerpt($metaDescCustom, 320)
            : self::plainExcerpt(
                self::translationChain($project, 'text_content', $locale),
                self::DESCRIPTION_FALLBACK_LIMIT
            );

        $ogTitle = self::translationChain($project, 'og_title', $locale) ?: $metaTitle;
        $ogDesc = self::translationChain($project, 'og_description', $locale) ?: $metaDesc;

        $ogImage = self::ogImagePath($project->og_image ?? null, $project->cover_photo ?? null);

        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'og_title' => $ogTitle,
            'og_description' => $ogDesc,
            'og_image' => $ogImage,
        ];
    }

    /**
     * @return array{
     *   meta_title: string,
     *   meta_description: string,
     *   og_title: string,
     *   og_description: string,
     *   og_image: string|null
     * }
     */
    public static function forService(Service $service): array
    {
        $locale = app()->getLocale();
        $title = self::translationChain($service, 'title', $locale);
        $metaTitle = self::translationChain($service, 'meta_title', $locale) ?: $title;

        $metaDescCustom = self::translationChain($service, 'meta_description', $locale);
        $metaDesc = $metaDescCustom !== ''
            ? self::plainExcerpt($metaDescCustom, 320)
            : self::plainExcerpt(
                self::translationChain($service, 'short_teaser', $locale)
                    ?: self::translationChain($service, 'text_content', $locale),
                self::DESCRIPTION_FALLBACK_LIMIT
            );

        $ogTitle = self::translationChain($service, 'og_title', $locale) ?: $metaTitle;
        $ogDesc = self::translationChain($service, 'og_description', $locale) ?: $metaDesc;

        $ogImage = self::ogImagePath($service->og_image ?? null, $service->cover_photo ?? null);

        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'og_title' => $ogTitle,
            'og_description' => $ogDesc,
            'og_image' => $ogImage,
        ];
    }

    private static function translationChain(Model $model, string $attribute, string $locale): string
    {
        if (! method_exists($model, 'getTranslation')) {
            return '';
        }

        $locales = array_values(array_unique(array_filter([$locale, 'en', 'ka'], 'is_string')));

        foreach ($locales as $loc) {
            $raw = $model->getTranslation($attribute, $loc, false);
            if ($raw === null || $raw === '') {
                continue;
            }

            return is_string($raw) ? trim($raw) : trim((string) $raw);
        }

        return '';
    }

    private static function plainExcerpt(string $htmlOrText, int $limit): string
    {
        $plain = preg_replace('/\s+/u', ' ', trim(html_entity_decode(strip_tags($htmlOrText), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

        return Str::limit($plain !== '' ? $plain : '', $limit, '…');
    }

    private static function ogImagePath(?string $ogImage, ?string $coverPhoto): ?string
    {
        if (filled($ogImage)) {
            return (string) $ogImage;
        }
        if (filled($coverPhoto)) {
            return (string) $coverPhoto;
        }

        return null;
    }
}
