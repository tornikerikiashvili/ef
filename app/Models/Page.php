<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Page extends Model
{
    public const KEY_CONTACT_PAGE = 'contact_page';

    /** Single homepage row: featured service ids + localized headline (en/ka). */
    public const KEY_HOME_PAGE = 'home_page';

    /** @var list<string> */
    public const SEED_KEYS = [
        self::KEY_CONTACT_PAGE,
        self::KEY_HOME_PAGE,
    ];

    protected $fillable = [
        'key',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * @return array<string, array{intro: string, email: string, phone: string, address: string}>
     */
    public static function defaultContactPayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);
        $out = [];
        foreach ($locales as $locale) {
            $out[$locale] = [
                'intro' => '',
                'email' => '',
                'phone' => '',
                'address' => '',
            ];
        }

        return $out;
    }

    /**
     * One JSON shape: `ids`, `project_ids`, per-locale headline, per-locale `about`, and per-locale `projects_section` (`title` only).
     *
     * @return array<string, mixed>
     */
    public static function defaultHomePagePayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);
        $highlight = static fn (): array => ['title' => '', 'teaser' => '', 'link' => ''];
        $out = [
            'ids' => [],
            'about' => [],
            'project_ids' => [],
            'projects_section' => [],
            'partner_logo_ids' => [],
            'partners_section' => [],
            'news_ids' => [],
            'news_section' => [],
            'show_contact_form' => false,
            'gallery_id' => null,
        ];
        foreach ($locales as $locale) {
            $out[$locale] = [
                'title' => '',
                'teaser' => '',
                'highlights' => [$highlight(), $highlight(), $highlight()],
            ];
            $out['about'][$locale] = [
                'title' => '',
                'text' => '',
                'image' => null,
                'link' => '',
            ];
            $out['projects_section'][$locale] = [
                'title' => '',
            ];
            $out['partners_section'][$locale] = [
                'title' => '',
            ];
            $out['news_section'][$locale] = [
                'title' => '',
                'teaser' => '',
            ];
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultPayloadForKey(string $key): array
    {
        return match ($key) {
            self::KEY_CONTACT_PAGE => self::defaultContactPayload(),
            self::KEY_HOME_PAGE => self::defaultHomePagePayload(),
        };
    }

    /**
     * Ensure the `home_page` row exists. If legacy `home_featured_services` / `home_headline` rows exist, merge into one payload and remove them.
     */
    public static function ensureHomePageBlock(): void
    {
        if (static::query()->where('key', self::KEY_HOME_PAGE)->exists()) {
            static::query()->whereIn('key', ['home_featured_services', 'home_headline'])->delete();

            return;
        }

        $legacyIds = static::query()->where('key', 'home_featured_services')->first();
        $legacyHeadline = static::query()->where('key', 'home_headline')->first();

        if ($legacyIds || $legacyHeadline) {
            $payload = static::defaultHomePagePayload();
            if ($legacyIds && is_array($legacyIds->payload)) {
                $payload['ids'] = is_array($legacyIds->payload['ids'] ?? null)
                    ? array_values(array_filter(array_map('intval', $legacyIds->payload['ids'])))
                    : [];
            }
            if ($legacyHeadline && is_array($legacyHeadline->payload)) {
                $payload = array_replace_recursive($payload, $legacyHeadline->payload);
            }
            static::query()->create([
                'key' => self::KEY_HOME_PAGE,
                'payload' => $payload,
            ]);
            static::query()->whereIn('key', ['home_featured_services', 'home_headline'])->delete();

            return;
        }

        static::query()->firstOrCreate(
            ['key' => self::KEY_HOME_PAGE],
            ['payload' => static::defaultHomePagePayload()]
        );
    }

    /**
     * Homepage data for the current request locale: service ids, headline, and about block (with EN fallback for non-EN locales).
     *
     * @return array{ids: list<int>, title: string, teaser: string, highlights: list<array{title: string, teaser: string, link: string}>, about: array{title: string, text: string, image: string|null, link: string}, projects_section: array{title: string}, project_ids: list<int>}
     */
    public static function homePageContent(): array
    {
        static::ensureHomePageBlock();

        $defaults = static::defaultHomePagePayload();
        $stored = static::query()->where('key', self::KEY_HOME_PAGE)->value('payload');
        $merged = is_array($stored) ? array_replace_recursive($defaults, $stored) : $defaults;

        $ids = $merged['ids'] ?? [];
        $ids = array_values(array_unique(array_filter(array_map('intval', is_array($ids) ? $ids : []))));

        $locale = app()->getLocale();
        $base = $defaults[$locale] ?? $defaults['en'];
        $en = is_array($merged['en'] ?? null) ? $merged['en'] : [];
        $localized = is_array($merged[$locale] ?? null) ? $merged[$locale] : [];

        $result = array_merge($base, $en, $localized);
        $result['highlights'] = static::normalizeHomeHeadlineHighlights($result['highlights'] ?? []);

        if ($locale !== 'en') {
            foreach (['title', 'teaser'] as $field) {
                if (($result[$field] ?? '') === '' && ($en[$field] ?? '') !== '') {
                    $result[$field] = $en[$field];
                }
            }
            $result['highlights'] = static::mergeHomeHeadlineHighlightFallback(
                $result['highlights'],
                static::normalizeHomeHeadlineHighlights($en['highlights'] ?? [])
            );
        }

        $aboutDefaults = is_array($defaults['about'] ?? null) ? $defaults['about'] : [];
        $aboutStored = is_array($merged['about'] ?? null) ? $merged['about'] : [];
        $aboutBase = $aboutDefaults[$locale] ?? $aboutDefaults['en'] ?? ['title' => '', 'text' => '', 'image' => null, 'link' => ''];
        $aboutEn = is_array($aboutStored['en'] ?? null) ? $aboutStored['en'] : [];
        $aboutLocalized = is_array($aboutStored[$locale] ?? null) ? $aboutStored[$locale] : [];
        $aboutRow = array_merge($aboutBase, $aboutEn, $aboutLocalized);

        if ($locale !== 'en') {
            foreach (['title', 'text', 'link'] as $field) {
                if (($aboutRow[$field] ?? '') === '' && ($aboutEn[$field] ?? '') !== '') {
                    $aboutRow[$field] = $aboutEn[$field];
                }
            }
            if (! filled($aboutRow['image'] ?? null) && filled($aboutEn['image'] ?? null)) {
                $aboutRow['image'] = $aboutEn['image'];
            }
        }

        $image = $aboutRow['image'] ?? null;
        if (is_array($image)) {
            $image = $image[0] ?? null;
        }
        $image = filled($image) ? (string) $image : null;

        $projectsSectionDefaults = is_array($defaults['projects_section'] ?? null) ? $defaults['projects_section'] : [];
        $projectsSectionStored = is_array($merged['projects_section'] ?? null) ? $merged['projects_section'] : [];
        $psBase = $projectsSectionDefaults[$locale] ?? $projectsSectionDefaults['en'] ?? ['title' => ''];
        $psEn = is_array($projectsSectionStored['en'] ?? null) ? $projectsSectionStored['en'] : [];
        $psLocalized = is_array($projectsSectionStored[$locale] ?? null) ? $projectsSectionStored[$locale] : [];
        $projectsSectionRow = array_merge($psBase, $psEn, $psLocalized);
        if ($locale !== 'en' && ($projectsSectionRow['title'] ?? '') === '' && ($psEn['title'] ?? '') !== '') {
            $projectsSectionRow['title'] = $psEn['title'];
        }

        $projectIds = $merged['project_ids'] ?? [];
        $projectIds = array_values(array_unique(array_filter(array_map('intval', is_array($projectIds) ? $projectIds : []))));

        $partnersSectionDefaults = is_array($defaults['partners_section'] ?? null) ? $defaults['partners_section'] : [];
        $partnersSectionStored = is_array($merged['partners_section'] ?? null) ? $merged['partners_section'] : [];
        $parBase = $partnersSectionDefaults[$locale] ?? $partnersSectionDefaults['en'] ?? ['title' => ''];
        $parEn = is_array($partnersSectionStored['en'] ?? null) ? $partnersSectionStored['en'] : [];
        $parLocalized = is_array($partnersSectionStored[$locale] ?? null) ? $partnersSectionStored[$locale] : [];
        $partnersSectionRow = array_merge($parBase, $parEn, $parLocalized);
        if ($locale !== 'en' && ($partnersSectionRow['title'] ?? '') === '' && ($parEn['title'] ?? '') !== '') {
            $partnersSectionRow['title'] = $parEn['title'];
        }

        $partnerLogoIds = $merged['partner_logo_ids'] ?? [];
        $partnerLogoIds = array_values(array_unique(array_filter(array_map('intval', is_array($partnerLogoIds) ? $partnerLogoIds : []))));

        $newsSectionDefaults = is_array($defaults['news_section'] ?? null) ? $defaults['news_section'] : [];
        $newsSectionStored = is_array($merged['news_section'] ?? null) ? $merged['news_section'] : [];
        $newsBase = $newsSectionDefaults[$locale] ?? $newsSectionDefaults['en'] ?? ['title' => '', 'teaser' => ''];
        $newsEn = is_array($newsSectionStored['en'] ?? null) ? $newsSectionStored['en'] : [];
        $newsLocalized = is_array($newsSectionStored[$locale] ?? null) ? $newsSectionStored[$locale] : [];
        $newsSectionRow = array_merge($newsBase, $newsEn, $newsLocalized);
        if ($locale !== 'en') {
            foreach (['title', 'teaser'] as $field) {
                if (($newsSectionRow[$field] ?? '') === '' && ($newsEn[$field] ?? '') !== '') {
                    $newsSectionRow[$field] = $newsEn[$field];
                }
            }
        }

        $newsIds = $merged['news_ids'] ?? [];
        $newsIds = array_values(array_unique(array_filter(array_map('intval', is_array($newsIds) ? $newsIds : []))));

        $showContactForm = filter_var($merged['show_contact_form'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $galleryId = $merged['gallery_id'] ?? null;
        $galleryId = ($galleryId !== null && $galleryId !== '' && (int) $galleryId > 0) ? (int) $galleryId : null;
        $galleryPayload = null;
        if ($galleryId !== null) {
            $gallery = Gallery::query()->find($galleryId);
            if ($gallery instanceof Gallery) {
                $galleryPayload = [
                    'id' => $gallery->id,
                    'name' => (string) ($gallery->name ?? ''),
                    'images' => $gallery->imagePaths(),
                ];
            }
        }

        return [
            'ids' => $ids,
            'title' => (string) ($result['title'] ?? ''),
            'teaser' => (string) ($result['teaser'] ?? ''),
            'highlights' => $result['highlights'],
            'about' => [
                'title' => (string) ($aboutRow['title'] ?? ''),
                'text' => (string) ($aboutRow['text'] ?? ''),
                'image' => $image,
                'link' => (string) ($aboutRow['link'] ?? ''),
            ],
            'projects_section' => [
                'title' => (string) ($projectsSectionRow['title'] ?? ''),
            ],
            'project_ids' => $projectIds,
            'partners_section' => [
                'title' => (string) ($partnersSectionRow['title'] ?? ''),
            ],
            'partner_logo_ids' => $partnerLogoIds,
            'news_section' => [
                'title' => (string) ($newsSectionRow['title'] ?? ''),
                'teaser' => (string) ($newsSectionRow['teaser'] ?? ''),
            ],
            'news_ids' => $newsIds,
            'show_contact_form' => $showContactForm,
            'gallery_id' => $galleryId,
            'gallery' => $galleryPayload,
        ];
    }

    /**
     * @param  list<int|string>  $ids
     * @return Collection<int, Service>
     */
    public static function orderedServices(array $ids): Collection
    {
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if ($ids === []) {
            return collect();
        }

        $order = array_flip($ids);

        return Service::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (Service $s): int => $order[$s->id] ?? 999)
            ->values();
    }

    /**
     * @param  list<int|string>  $ids
     * @return Collection<int, Project>
     */
    public static function orderedProjects(array $ids): Collection
    {
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if ($ids === []) {
            return collect();
        }

        $order = array_flip($ids);

        return Project::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (Project $p): int => $order[$p->id] ?? 999)
            ->values();
    }

    /**
     * @param  list<int|string>  $ids
     * @return Collection<int, PartnerLogo>
     */
    public static function orderedPartnerLogos(array $ids): Collection
    {
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if ($ids === []) {
            return collect();
        }

        $order = array_flip($ids);

        return PartnerLogo::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (PartnerLogo $p): int => $order[$p->id] ?? 999)
            ->values();
    }

    /**
     * @param  list<int|string>  $ids
     * @return Collection<int, News>
     */
    public static function orderedNews(array $ids): Collection
    {
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if ($ids === []) {
            return collect();
        }

        $order = array_flip($ids);

        return News::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (News $n): int => $order[$n->id] ?? 999)
            ->values();
    }

    /**
     * Merged locale-specific fields for the current app locale, with English fallback (contact page).
     *
     * @return array<string, mixed>
     */
    public static function payloadFor(string $key): array
    {
        $defaults = static::defaultPayloadForKey($key);
        $stored = static::query()->where('key', $key)->value('payload');
        $merged = is_array($stored) ? array_replace_recursive($defaults, $stored) : $defaults;

        $locale = app()->getLocale();
        $base = $defaults[$locale] ?? $defaults['en'];
        $en = is_array($merged['en'] ?? null) ? $merged['en'] : [];
        $localized = is_array($merged[$locale] ?? null) ? $merged[$locale] : [];

        $result = array_merge($base, $en, $localized);

        if ($key === self::KEY_CONTACT_PAGE) {
            if ($locale !== 'en') {
                foreach (['intro', 'email', 'phone', 'address'] as $field) {
                    $current = $result[$field] ?? null;
                    if (($current === null || $current === '') && array_key_exists($field, $en)) {
                        $fromEn = $en[$field];
                        if ($fromEn !== null && $fromEn !== '') {
                            $result[$field] = $fromEn;
                        }
                    }
                }
            }

            return $result;
        }

        return $result;
    }

    /**
     * @param  list<array{title?: string, teaser?: string, link?: string}>  $highlights
     * @return list<array{title: string, teaser: string, link: string}>
     */
    protected static function normalizeHomeHeadlineHighlights(array $highlights): array
    {
        $out = [];
        for ($i = 0; $i < 3; $i++) {
            $row = $highlights[$i] ?? [];
            $out[] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
                'teaser' => isset($row['teaser']) ? (string) $row['teaser'] : '',
                'link' => isset($row['link']) ? (string) $row['link'] : '',
            ];
        }

        return $out;
    }

    /**
     * @param  list<array{title: string, teaser: string, link: string}>  $primary
     * @param  list<array{title: string, teaser: string, link: string}>  $en
     * @return list<array{title: string, teaser: string, link: string}>
     */
    protected static function mergeHomeHeadlineHighlightFallback(array $primary, array $en): array
    {
        $merged = [];
        for ($i = 0; $i < 3; $i++) {
            $p = $primary[$i] ?? ['title' => '', 'teaser' => '', 'link' => ''];
            $e = $en[$i] ?? ['title' => '', 'teaser' => '', 'link' => ''];
            $merged[] = [
                'title' => filled($p['title']) ? $p['title'] : $e['title'],
                'teaser' => filled($p['teaser']) ? $p['teaser'] : $e['teaser'],
                'link' => filled($p['link']) ? $p['link'] : $e['link'],
            ];
        }

        return $merged;
    }
}
