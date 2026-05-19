<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Page extends Model
{
    public const KEY_CONTACT_PAGE = 'contact_page';

    /** Single homepage row: featured service ids + localized headline (en/ka). */
    public const KEY_HOME_PAGE = 'home_page';

    /** Single about page row: localized cover + body content. */
    public const KEY_ABOUT_PAGE = 'about_page';

    /** Services listing page settings. */
    public const KEY_SERVICES_LISTING_PAGE = 'services_listing_page';

    /** Projects listing page settings. */
    public const KEY_PROJECTS_LISTING_PAGE = 'projects_listing_page';

    /** News listing page settings. */
    public const KEY_NEWS_LISTING_PAGE = 'news_listing_page';

    /** Partners listing page settings. */
    public const KEY_PARTNERS_PAGE = 'partners_page';

    /** Legal / policy texts (terms, privacy, cookies), rich HTML per CMS locale. */
    public const KEY_LEGAL_PAGES = 'legal_pages';

    /** @var list<string> */
    public const SEED_KEYS = [
        self::KEY_CONTACT_PAGE,
        self::KEY_HOME_PAGE,
        self::KEY_ABOUT_PAGE,
        self::KEY_SERVICES_LISTING_PAGE,
        self::KEY_PROJECTS_LISTING_PAGE,
        self::KEY_NEWS_LISTING_PAGE,
        self::KEY_PARTNERS_PAGE,
        self::KEY_LEGAL_PAGES,
    ];

    /**
     * Optional SEO stored on each `pages` row payload under `seo.locales` (per supported CMS locale).
     *
     * @return array{locales: array<string, array{
     *   meta_title: string,
     *   meta_description: string,
     *   og_title: string,
     *   og_description: string,
     *   og_image: string|null
     * }>}
     */
    public static function defaultSeoPayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);
        $out = [
            'locales' => [],
        ];
        foreach ($locales as $locale) {
            if (! is_string($locale)) {
                continue;
            }
            $out['locales'][$locale] = [
                'meta_title' => '',
                'meta_description' => '',
                'og_title' => '',
                'og_description' => '',
                'og_image' => null,
            ];
        }

        return $out;
    }

    /**
     * Normalize and merge `seo` into a page payload (call at end of each page settings normalizer).
     *
     * @param  array<string, mixed>  $merged
     * @return array<string, mixed>
     */
    public static function normalizeSeoInPagePayload(array $merged): array
    {
        $defaults = static::defaultSeoPayload();
        $seo = is_array($merged['seo'] ?? null) ? $merged['seo'] : [];

        // Legacy: flat `seo.*` fields (pre localized SEO) → one locale row (prefer English).
        if ($seo !== [] && ! is_array($seo['locales'] ?? null)) {
            $img = $seo['og_image'] ?? null;
            if (is_array($img)) {
                $img = $img[0] ?? null;
            }
            $legacy = [
                'meta_title' => isset($seo['meta_title']) ? (string) $seo['meta_title'] : '',
                'meta_description' => isset($seo['meta_description']) ? (string) $seo['meta_description'] : '',
                'og_title' => isset($seo['og_title']) ? (string) $seo['og_title'] : '',
                'og_description' => isset($seo['og_description']) ? (string) $seo['og_description'] : '',
                'og_image' => filled($img) ? (string) $img : null,
            ];
            $localesDefaults = is_array($defaults['locales'] ?? null) ? $defaults['locales'] : [];
            $targetLocale = array_key_exists('en', $localesDefaults)
                ? 'en'
                : (string) (array_key_first($localesDefaults) ?: 'en');
            $seo = ['locales' => [$targetLocale => $legacy]];
        }

        $localesDefaults = is_array($defaults['locales'] ?? null) ? $defaults['locales'] : [];
        $localesStored = is_array($seo['locales'] ?? null) ? $seo['locales'] : [];
        $mergedLocales = [];

        foreach (array_keys($localesDefaults) as $locale) {
            if (! is_string($locale)) {
                continue;
            }
            $base = $localesDefaults[$locale];
            $stored = is_array($localesStored[$locale] ?? null) ? $localesStored[$locale] : [];
            $row = array_replace($base, $stored);

            $row['meta_title'] = isset($row['meta_title']) ? (string) $row['meta_title'] : '';
            if (strlen($row['meta_title']) > 255) {
                $row['meta_title'] = substr($row['meta_title'], 0, 255);
            }

            $row['meta_description'] = isset($row['meta_description']) ? (string) $row['meta_description'] : '';
            if (strlen($row['meta_description']) > 65535) {
                $row['meta_description'] = substr($row['meta_description'], 0, 65535);
            }

            $row['og_title'] = isset($row['og_title']) ? (string) $row['og_title'] : '';
            if (strlen($row['og_title']) > 255) {
                $row['og_title'] = substr($row['og_title'], 0, 255);
            }

            $row['og_description'] = isset($row['og_description']) ? (string) $row['og_description'] : '';
            if (strlen($row['og_description']) > 65535) {
                $row['og_description'] = substr($row['og_description'], 0, 65535);
            }

            $img = $row['og_image'] ?? null;
            if (is_array($img)) {
                $img = $img[0] ?? null;
            }
            $row['og_image'] = filled($img) ? (string) $img : null;

            $mergedLocales[$locale] = $row;
        }

        $merged['seo'] = [
            'locales' => $mergedLocales,
        ];

        return $merged;
    }

    /**
     * Ensure a `pages` row exists for CMS-backed routes that expose SEO.
     */
    public static function ensurePageBlockForKey(string $pageKey): void
    {
        match ($pageKey) {
            self::KEY_HOME_PAGE => static::ensureHomePageBlock(),
            self::KEY_ABOUT_PAGE => static::ensureAboutPageBlock(),
            self::KEY_SERVICES_LISTING_PAGE => static::ensureServicesListingPageBlock(),
            self::KEY_PROJECTS_LISTING_PAGE => static::ensureProjectsListingPageBlock(),
            self::KEY_NEWS_LISTING_PAGE => static::ensureNewsListingPageBlock(),
            self::KEY_PARTNERS_PAGE => static::ensurePartnersPageBlock(),
            self::KEY_CONTACT_PAGE => static::query()->firstOrCreate(
                ['key' => self::KEY_CONTACT_PAGE],
                ['payload' => static::defaultContactPayload()]
            ),
            self::KEY_LEGAL_PAGES => static::query()->firstOrCreate(
                ['key' => self::KEY_LEGAL_PAGES],
                ['payload' => static::defaultLegalPagesPayload()]
            ),
            default => null,
        };
    }

    /**
     * SEO fields for the current app locale (with English fallback for empty strings), for public HTML head.
     *
     * @return array{
     *   meta_title: string,
     *   meta_description: string,
     *   og_title: string,
     *   og_description: string,
     *   og_image: string|null
     * }
     */
    public static function resolvedPublicSeoForPageKey(string $pageKey): array
    {
        static::ensurePageBlockForKey($pageKey);

        $defaults = static::defaultPayloadForKey($pageKey);
        $stored = static::query()->where('key', $pageKey)->value('payload');
        $merged = array_replace_recursive($defaults, is_array($stored) ? $stored : []);
        $merged = static::normalizeSeoInPagePayload($merged);

        $seo = is_array($merged['seo'] ?? null) ? $merged['seo'] : [];
        $locales = is_array($seo['locales'] ?? null) ? $seo['locales'] : [];
        $defaultLocales = static::defaultSeoPayload()['locales'];

        $locale = app()->getLocale();
        if (! is_string($locale) || ! array_key_exists($locale, $defaultLocales)) {
            $locale = array_key_exists('en', $defaultLocales) ? 'en' : (string) (array_key_first($defaultLocales) ?: 'en');
        }

        $base = $defaultLocales[$locale] ?? [
            'meta_title' => '',
            'meta_description' => '',
            'og_title' => '',
            'og_description' => '',
            'og_image' => null,
        ];
        $localized = is_array($locales[$locale] ?? null) ? $locales[$locale] : [];
        $row = array_merge($base, $localized);

        if ($locale !== 'en' && array_key_exists('en', $defaultLocales)) {
            $enBase = $defaultLocales['en'];
            $enLocalized = is_array($locales['en'] ?? null) ? $locales['en'] : [];
            $enRow = array_merge($enBase, $enLocalized);
            foreach (['meta_title', 'meta_description', 'og_title', 'og_description'] as $field) {
                if (($row[$field] ?? '') === '' && ($enRow[$field] ?? '') !== '') {
                    $row[$field] = $enRow[$field];
                }
            }
            if (! filled($row['og_image'] ?? null) && filled($enRow['og_image'] ?? null)) {
                $row['og_image'] = $enRow['og_image'];
            }
        }

        return [
            'meta_title' => (string) ($row['meta_title'] ?? ''),
            'meta_description' => (string) ($row['meta_description'] ?? ''),
            'og_title' => (string) ($row['og_title'] ?? ''),
            'og_description' => (string) ($row['og_description'] ?? ''),
            'og_image' => filled($row['og_image'] ?? null) ? (string) $row['og_image'] : null,
        ];
    }

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
     * @return array<string, mixed>
     */
    public static function defaultContactPayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);
        $out = [];
        foreach ($locales as $locale) {
            $out[$locale] = [
                'menu_title' => '',
                'title' => '',
                'intro' => '',
                'email' => '',
                'address' => '',
            ];
        }

        $out['seo'] = static::defaultSeoPayload();
        $out['gallery_id'] = null;
        $out['social'] = [
            'instagram' => [
                'url' => '',
                'name' => '',
            ],
            'facebook_url' => '',
            'linkedin_url' => '',
            'youtube_url' => '',
        ];
        $out['google_map_embed'] = '';

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
        $contactCard = static fn (): array => [
            'title' => '',
            'value' => '',
            'button_title' => '',
            'button_link' => '',
        ];
        $out = [
            'seo' => static::defaultSeoPayload(),
            'ids' => [],
            'about' => [],
            'project_ids' => [],
            'projects_section' => [],
            'partner_logo_ids' => [],
            'partners_section' => [],
            'news_ids' => [],
            'news_section' => [],
            'show_contact_form' => false,
            'contact_cards' => [],
            'gallery_id' => null,
            'gallery_instagram_link' => '',
        ];
        foreach ($locales as $locale) {
            $out[$locale] = [
                'menu_title' => '',
                'title' => '',
                'teaser' => '',
                'highlights' => [$highlight(), $highlight(), $highlight()],
            ];
            $out['about'][$locale] = [
                'title' => '',
                'text' => '',
                'image' => null,
                'link' => '',
                'video_url' => '',
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

            $out['contact_cards'][$locale] = [$contactCard(), $contactCard(), $contactCard()];
        }

        return $out;
    }

    /**
     * @return array{
     *   cover: array<string, array{title: string, background_image: string|null}>,
     *   about_images: array{image_left: string|null, image_right: string|null},
     *   about: array<string, array{teaser: string, description: string}>,
     *   funfacts: array<string, array{label_1: string, value_1: int, label_2: string, value_2: int}>,
     *   president: array{
     *     years_experience: int,
     *     image: string|null,
     *     locales: array<string, array{
     *       title: string,
     *       content: string,
     *       image_person_name: string
     *     }>
     *   },
     *   video_background_image: string|null,
     *   video: array<string, array{url: string}>,
     *   body: array<string, array{title: string, text: string, image: string|null, video_url: string}>
     * }
     */
    public static function defaultAboutPagePayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        $out = [
            'seo' => static::defaultSeoPayload(),
            'menu' => [],
            'cover_image' => null,
            'cover' => [],
            'about_images' => [
                'image_left' => null,
                'image_right' => null,
            ],
            'about' => [],
            'funfacts' => [],
            'president' => [
                'years_experience' => 0,
                'image' => null,
                'locales' => [],
            ],
            'video_background_image' => null,
            'video' => [],
            'body' => [],
        ];

        foreach ($locales as $locale) {
            $out['menu'][$locale] = [
                'title' => '',
            ];
            $out['cover'][$locale] = [
                'title' => '',
                'background_image' => null,
            ];

            $out['about'][$locale] = [
                'teaser' => '',
                'description' => '',
            ];

            $out['funfacts'][$locale] = [
                'label_1' => '',
                'value_1' => 0,
                'label_2' => '',
                'value_2' => 0,
            ];

            $out['president']['locales'][$locale] = [
                'title' => '',
                'content' => '',
                'image_person_name' => '',
            ];

            $out['video'][$locale] = [
                'url' => '',
            ];

            $out['body'][$locale] = [
                'title' => '',
                'text' => '',
                'image' => null,
                'video_url' => '',
            ];
        }

        return $out;
    }

    /**
     * @return array{
     *   cover_image: string|null,
     *   video_background_image: string|null,
     *   video_url: string,
     *   services: list<int>,
     *   locales: array<string, array{title: string, services_title: string, typewrite_text: string}>
     * }
     */
    public static function defaultServicesListingPagePayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        $out = [
            'seo' => static::defaultSeoPayload(),
            'cover_image' => null,
            'video_background_image' => null,
            'services' => [],
            'video' => [],
            'locales' => [],
        ];

        foreach ($locales as $locale) {
            $out['locales'][$locale] = [
                'menu_title' => '',
                'title' => '',
                'services_title' => '',
                'typewrite_text' => '',
            ];
            $out['video'][$locale] = [
                'url' => '',
            ];
        }

        return $out;
    }

    /**
     * @return array{
     *   cover_image: string|null,
     *   projects: list<int>,
     *   locales: array<string, array{title: string}>
     * }
     */
    public static function defaultProjectsListingPagePayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        $out = [
            'seo' => static::defaultSeoPayload(),
            'cover_image' => null,
            'projects' => [],
            'locales' => [],
        ];

        foreach ($locales as $locale) {
            $out['locales'][$locale] = [
                'menu_title' => '',
                'title' => '',
            ];
        }

        return $out;
    }

    /**
     * @return array{
     *   cover_image: string|null,
     *   news: list<int>,
     *   locales: array<string, array{title: string}>
     * }
     */
    public static function defaultNewsListingPagePayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        $out = [
            'seo' => static::defaultSeoPayload(),
            'cover_image' => null,
            'news' => [],
            'locales' => [],
        ];

        foreach ($locales as $locale) {
            $out['locales'][$locale] = [
                'menu_title' => '',
                'title' => '',
            ];
        }

        return $out;
    }

    /**
     * @return array{
     *   cover_image: string|null,
     *   partner_logo_ids: list<int>,
     *   locales: array<string, array{menu_title: string, title: string}>
     * }
     */
    public static function defaultPartnersPagePayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        $out = [
            'seo' => static::defaultSeoPayload(),
            'cover_image' => null,
            'partner_logo_ids' => [],
            'locales' => [],
        ];

        foreach ($locales as $locale) {
            $out['locales'][$locale] = [
                'menu_title' => '',
                'title' => '',
            ];
        }

        return $out;
    }

    /**
     * Terms, privacy policy, and cookie policy body HTML (per CMS locale).
     *
     * @return array{
     *   locales: array<string, array{terms: string, privacy: string, cookies: string}>
     * }
     */
    public static function defaultLegalPagesPayload(): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);
        $out = [
            'locales' => [],
        ];
        foreach ($locales as $locale) {
            if (! is_string($locale)) {
                continue;
            }
            $out['locales'][$locale] = [
                'terms' => '',
                'privacy' => '',
                'cookies' => '',
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
            self::KEY_ABOUT_PAGE => self::defaultAboutPagePayload(),
            self::KEY_SERVICES_LISTING_PAGE => self::defaultServicesListingPagePayload(),
            self::KEY_PROJECTS_LISTING_PAGE => self::defaultProjectsListingPagePayload(),
            self::KEY_NEWS_LISTING_PAGE => self::defaultNewsListingPagePayload(),
            self::KEY_PARTNERS_PAGE => self::defaultPartnersPagePayload(),
            self::KEY_LEGAL_PAGES => self::defaultLegalPagesPayload(),
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
     * Ensure the `about_page` row exists.
     */
    public static function ensureAboutPageBlock(): void
    {
        static::query()->firstOrCreate(
            ['key' => self::KEY_ABOUT_PAGE],
            ['payload' => static::defaultAboutPagePayload()]
        );
    }

    /**
     * Ensure the `services_listing_page` row exists.
     */
    public static function ensureServicesListingPageBlock(): void
    {
        static::query()->firstOrCreate(
            ['key' => self::KEY_SERVICES_LISTING_PAGE],
            ['payload' => static::defaultServicesListingPagePayload()]
        );
    }

    /**
     * Ensure the `projects_listing_page` row exists.
     */
    public static function ensureProjectsListingPageBlock(): void
    {
        static::query()->firstOrCreate(
            ['key' => self::KEY_PROJECTS_LISTING_PAGE],
            ['payload' => static::defaultProjectsListingPagePayload()]
        );
    }

    /**
     * Ensure the `news_listing_page` row exists.
     */
    public static function ensureNewsListingPageBlock(): void
    {
        static::query()->firstOrCreate(
            ['key' => self::KEY_NEWS_LISTING_PAGE],
            ['payload' => static::defaultNewsListingPagePayload()]
        );
    }

    /**
     * Ensure the `partners_page` row exists.
     */
    public static function ensurePartnersPageBlock(): void
    {
        static::query()->firstOrCreate(
            ['key' => self::KEY_PARTNERS_PAGE],
            ['payload' => static::defaultPartnersPagePayload()]
        );
    }

    /**
     * Ensure the `legal_pages` row exists (terms / privacy / cookie policy copy).
     */
    public static function ensureLegalPagesBlock(): void
    {
        static::query()->firstOrCreate(
            ['key' => self::KEY_LEGAL_PAGES],
            ['payload' => static::defaultLegalPagesPayload()]
        );
    }

    /**
     * Partners page settings for the current request locale (no cross-locale fallback).
     *
     * @return array{
     *   menu_title: string,
     *   title: string,
     *   cover_image: string|null,
     *   partner_logo_ids: list<int>
     * }
     */
    public static function partnersPageContent(): array
    {
        static::ensurePartnersPageBlock();

        $defaults = static::defaultPartnersPagePayload();
        $stored = static::query()->where('key', self::KEY_PARTNERS_PAGE)->value('payload');
        $merged = is_array($stored) ? array_replace_recursive($defaults, $stored) : $defaults;

        $locale = app()->getLocale();
        $baseLocale = array_key_exists($locale, $defaults['locales'] ?? []) ? $locale : 'en';

        $localesDefaults = is_array($defaults['locales'] ?? null) ? $defaults['locales'] : [];
        $localesStored = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        $base = $localesDefaults[$baseLocale] ?? ['menu_title' => '', 'title' => ''];
        $localized = is_array($localesStored[$baseLocale] ?? null) ? $localesStored[$baseLocale] : [];
        $row = array_merge($base, $localized);

        $cover = $merged['cover_image'] ?? null;
        if (is_array($cover)) {
            $cover = $cover[0] ?? null;
        }
        $cover = filled($cover) ? (string) $cover : null;

        $partnerLogoIds = $merged['partner_logo_ids'] ?? [];
        $partnerLogoIds = array_values(array_unique(array_filter(array_map('intval', is_array($partnerLogoIds) ? $partnerLogoIds : []))));

        return [
            'menu_title' => (string) ($row['menu_title'] ?? ''),
            'title' => (string) ($row['title'] ?? ''),
            'cover_image' => $cover,
            'partner_logo_ids' => $partnerLogoIds,
        ];
    }

    /**
     * Legal page bodies for the current request locale (English fallback for empty HTML).
     *
     * @return array{
     *   terms: string,
     *   privacy: string,
     *   cookies: string
     * }
     */
    public static function legalPagesContent(): array
    {
        static::ensureLegalPagesBlock();

        $defaults = static::defaultLegalPagesPayload();
        $stored = static::query()->where('key', self::KEY_LEGAL_PAGES)->value('payload');
        $merged = is_array($stored) ? array_replace_recursive($defaults, $stored) : $defaults;

        $locale = app()->getLocale();
        $baseLocale = array_key_exists($locale, $defaults['locales'] ?? []) ? $locale : 'en';

        $localesDefaults = is_array($defaults['locales'] ?? null) ? $defaults['locales'] : [];
        $localesStored = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        $base = $localesDefaults[$baseLocale] ?? ['terms' => '', 'privacy' => '', 'cookies' => ''];
        $en = is_array($localesStored['en'] ?? null) ? $localesStored['en'] : [];
        $localized = is_array($localesStored[$baseLocale] ?? null) ? $localesStored[$baseLocale] : [];

        if ($baseLocale !== 'en') {
            $row = array_merge($base, $en, $localized);
            foreach (['terms', 'privacy', 'cookies'] as $field) {
                $current = trim(strip_tags((string) ($row[$field] ?? '')));
                $fromEn = trim(strip_tags((string) ($en[$field] ?? '')));
                if ($current === '' && $fromEn !== '') {
                    $row[$field] = (string) ($en[$field] ?? '');
                }
            }
        } else {
            $row = array_merge($base, $localized);
        }

        return [
            'terms' => (string) ($row['terms'] ?? ''),
            'privacy' => (string) ($row['privacy'] ?? ''),
            'cookies' => (string) ($row['cookies'] ?? ''),
        ];
    }

    /**
     * News listing page settings for the current request locale (no cross-locale fallback).
     *
     * @return array{
     *   title: string,
     *   cover_image: string|null,
     *   news: list<int>
     * }
     */
    public static function newsListingPageContent(): array
    {
        static::ensureNewsListingPageBlock();

        $defaults = static::defaultNewsListingPagePayload();
        $stored = static::query()->where('key', self::KEY_NEWS_LISTING_PAGE)->value('payload');
        $merged = is_array($stored) ? array_replace_recursive($defaults, $stored) : $defaults;

        $locale = app()->getLocale();
        $baseLocale = array_key_exists($locale, $defaults['locales'] ?? []) ? $locale : 'en';

        $localesDefaults = is_array($defaults['locales'] ?? null) ? $defaults['locales'] : [];
        $localesStored = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        $base = $localesDefaults[$baseLocale] ?? ['menu_title' => '', 'title' => ''];
        $localized = is_array($localesStored[$baseLocale] ?? null) ? $localesStored[$baseLocale] : [];
        $row = array_merge($base, $localized);

        $cover = $merged['cover_image'] ?? null;
        if (is_array($cover)) {
            $cover = $cover[0] ?? null;
        }
        $cover = filled($cover) ? (string) $cover : null;

        $news = $merged['news'] ?? [];
        $newsIds = array_values(array_unique(array_filter(array_map('intval', is_array($news) ? $news : []))));

        return [
            'menu_title' => (string) ($row['menu_title'] ?? ''),
            'title' => (string) ($row['title'] ?? ''),
            'cover_image' => $cover,
            'news' => $newsIds,
        ];
    }

    /**
     * Projects listing page settings for the current request locale (no cross-locale fallback).
     *
     * @return array{
     *   title: string,
     *   cover_image: string|null,
     *   projects: list<int>
     * }
     */
    public static function projectsListingPageContent(): array
    {
        static::ensureProjectsListingPageBlock();

        $defaults = static::defaultProjectsListingPagePayload();
        $stored = static::query()->where('key', self::KEY_PROJECTS_LISTING_PAGE)->value('payload');
        $merged = is_array($stored) ? array_replace_recursive($defaults, $stored) : $defaults;

        $locale = app()->getLocale();
        $baseLocale = array_key_exists($locale, $defaults['locales'] ?? []) ? $locale : 'en';

        $localesDefaults = is_array($defaults['locales'] ?? null) ? $defaults['locales'] : [];
        $localesStored = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        $base = $localesDefaults[$baseLocale] ?? ['menu_title' => '', 'title' => ''];
        $localized = is_array($localesStored[$baseLocale] ?? null) ? $localesStored[$baseLocale] : [];
        $row = array_merge($base, $localized);

        $cover = $merged['cover_image'] ?? null;
        if (is_array($cover)) {
            $cover = $cover[0] ?? null;
        }
        $cover = filled($cover) ? (string) $cover : null;

        $projects = $merged['projects'] ?? [];
        $projectIds = array_values(array_unique(array_filter(array_map('intval', is_array($projects) ? $projects : []))));

        return [
            'menu_title' => (string) ($row['menu_title'] ?? ''),
            'title' => (string) ($row['title'] ?? ''),
            'cover_image' => $cover,
            'projects' => $projectIds,
        ];
    }

    /**
     * Services listing page settings for the current request locale (no cross-locale fallback).
     *
     * @return array{
     *   title: string,
     *   services_title: string,
     *   typewrite_text: string,
     *   cover_image: string|null,
     *   video_background_image: string|null,
     *   video_url: string,
     *   services: list<int>
     * }
     */
    public static function servicesListingPageContent(): array
    {
        static::ensureServicesListingPageBlock();

        $defaults = static::defaultServicesListingPagePayload();
        $stored = static::query()->where('key', self::KEY_SERVICES_LISTING_PAGE)->value('payload');
        $merged = is_array($stored) ? array_replace_recursive($defaults, $stored) : $defaults;

        $locale = app()->getLocale();
        $baseLocale = array_key_exists($locale, $defaults['locales'] ?? []) ? $locale : 'en';

        $localesDefaults = is_array($defaults['locales'] ?? null) ? $defaults['locales'] : [];
        $localesStored = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        $base = $localesDefaults[$baseLocale] ?? ['menu_title' => '', 'title' => '', 'services_title' => '', 'typewrite_text' => ''];
        $localized = is_array($localesStored[$baseLocale] ?? null) ? $localesStored[$baseLocale] : [];
        $row = array_merge($base, $localized);

        $videoDefaults = is_array($defaults['video'] ?? null) ? $defaults['video'] : [];
        $videoStored = is_array($merged['video'] ?? null) ? $merged['video'] : [];
        $videoBase = $videoDefaults[$baseLocale] ?? ['url' => ''];
        $videoLocalized = is_array($videoStored[$baseLocale] ?? null) ? $videoStored[$baseLocale] : [];
        $videoRow = array_merge($videoBase, $videoLocalized);

        $cover = $merged['cover_image'] ?? null;
        if (is_array($cover)) {
            $cover = $cover[0] ?? null;
        }
        $cover = filled($cover) ? (string) $cover : null;

        $videoBg = $merged['video_background_image'] ?? null;
        if (is_array($videoBg)) {
            $videoBg = $videoBg[0] ?? null;
        }
        $videoBg = filled($videoBg) ? (string) $videoBg : null;

        $services = $merged['services'] ?? [];
        $serviceIds = array_values(array_unique(array_filter(array_map('intval', is_array($services) ? $services : []))));

        return [
            'menu_title' => (string) ($row['menu_title'] ?? ''),
            'title' => (string) ($row['title'] ?? ''),
            'services_title' => (string) ($row['services_title'] ?? ''),
            'typewrite_text' => (string) ($row['typewrite_text'] ?? ''),
            'cover_image' => $cover,
            'video_background_image' => $videoBg,
            'video_url' => (string) ($videoRow['url'] ?? ''),
            'services' => $serviceIds,
        ];
    }

    /**
     * President section body: saved RichEditor HTML, or legacy repeater rows converted to HTML.
     *
     * @param  array<string, mixed>  $presLocale
     */
    public static function presidentLocaleBodyHtml(array $presLocale): string
    {
        $raw = isset($presLocale['content']) ? trim((string) $presLocale['content']) : '';
        if ($raw !== '') {
            return (string) ($presLocale['content'] ?? '');
        }

        $items = is_array($presLocale['items'] ?? null) ? $presLocale['items'] : [];
        $chunks = [];
        foreach ($items as $item) {
            $item = is_array($item) ? $item : [];
            $title = isset($item['title']) ? trim((string) $item['title']) : '';
            $body = isset($item['content']) ? trim((string) $item['content']) : '';
            if ($title !== '') {
                $chunks[] = '<h6>'.e($title).'</h6>';
            }
            if ($body !== '') {
                $chunks[] = '<p>'.nl2br(e($body)).'</p>';
            }
        }

        return implode('', $chunks);
    }

    /**
     * About page content for the current request locale (no cross-locale fallback).
     *
     * @return array{
     *   cover: array{title: string, background_image: string|null},
     *   about: array{teaser: string, description: string, image_left: string|null, image_right: string|null},
     *   funfacts: array{label_1: string, value_1: int, label_2: string, value_2: int},
     *   president: array{title: string, years_experience: int, image: string|null, content: string, image_person_name: string},
     *   video_background_image: string|null,
     *   video: array{url: string},
     *   body: array{title: string, text: string, image: string|null, video_url: string}
     * }
     */
    public static function aboutPageContent(): array
    {
        static::ensureAboutPageBlock();

        $defaults = static::defaultAboutPagePayload();
        $stored = static::query()->where('key', self::KEY_ABOUT_PAGE)->value('payload');
        $merged = is_array($stored) ? array_replace_recursive($defaults, $stored) : $defaults;

        $locale = app()->getLocale();
        $baseLocale = array_key_exists($locale, $defaults['cover'] ?? []) ? $locale : 'en';

        $menuDefaults = is_array($defaults['menu'] ?? null) ? $defaults['menu'] : [];
        $menuStored = is_array($merged['menu'] ?? null) ? $merged['menu'] : [];
        $menuBase = $menuDefaults[$baseLocale] ?? ['title' => ''];
        $menuLocalized = is_array($menuStored[$baseLocale] ?? null) ? $menuStored[$baseLocale] : [];
        $menuRow = array_merge($menuBase, $menuLocalized);

        $coverDefaults = is_array($defaults['cover'] ?? null) ? $defaults['cover'] : [];
        $coverStored = is_array($merged['cover'] ?? null) ? $merged['cover'] : [];
        $coverBase = $coverDefaults[$baseLocale] ?? ['title' => '', 'background_image' => null];
        $coverLocalized = is_array($coverStored[$baseLocale] ?? null) ? $coverStored[$baseLocale] : [];
        $coverRow = array_merge($coverBase, $coverLocalized);

        $aboutDefaults = is_array($defaults['about'] ?? null) ? $defaults['about'] : [];
        $aboutStored = is_array($merged['about'] ?? null) ? $merged['about'] : [];
        $aboutBase = $aboutDefaults[$baseLocale] ?? ['teaser' => '', 'description' => ''];
        $aboutLocalized = is_array($aboutStored[$baseLocale] ?? null) ? $aboutStored[$baseLocale] : [];
        $aboutRow = array_merge($aboutBase, $aboutLocalized);

        $aboutImagesStored = is_array($merged['about_images'] ?? null) ? $merged['about_images'] : [];
        $aboutImageLeft = $aboutImagesStored['image_left'] ?? null;
        if (is_array($aboutImageLeft)) {
            $aboutImageLeft = $aboutImageLeft[0] ?? null;
        }
        $aboutImageLeft = filled($aboutImageLeft) ? (string) $aboutImageLeft : null;

        $aboutImageRight = $aboutImagesStored['image_right'] ?? null;
        if (is_array($aboutImageRight)) {
            $aboutImageRight = $aboutImageRight[0] ?? null;
        }
        $aboutImageRight = filled($aboutImageRight) ? (string) $aboutImageRight : null;

        $funfactsDefaults = is_array($defaults['funfacts'] ?? null) ? $defaults['funfacts'] : [];
        $funfactsStored = is_array($merged['funfacts'] ?? null) ? $merged['funfacts'] : [];
        $funfactsBase = $funfactsDefaults[$baseLocale] ?? ['label_1' => '', 'value_1' => 0, 'label_2' => '', 'value_2' => 0];
        $funfactsLocalized = is_array($funfactsStored[$baseLocale] ?? null) ? $funfactsStored[$baseLocale] : [];
        $funfactsRow = array_merge($funfactsBase, $funfactsLocalized);

        $presidentStored = is_array($merged['president'] ?? null) ? $merged['president'] : [];
        $presYears = (int) ($presidentStored['years_experience'] ?? 0);
        if ($presYears < 0) {
            $presYears = 0;
        }
        $presImage = $presidentStored['image'] ?? null;
        if (is_array($presImage)) {
            $presImage = $presImage[0] ?? null;
        }
        $presImage = filled($presImage) ? (string) $presImage : null;

        $presLocalesDefaults = is_array(($defaults['president']['locales'] ?? null)) ? $defaults['president']['locales'] : [];
        $presLocalesStored = is_array(($presidentStored['locales'] ?? null)) ? $presidentStored['locales'] : [];
        $presBase = $presLocalesDefaults[$baseLocale] ?? ['title' => '', 'content' => '', 'image_person_name' => ''];
        $presLocalized = is_array($presLocalesStored[$baseLocale] ?? null) ? $presLocalesStored[$baseLocale] : [];
        $presRow = array_merge($presBase, $presLocalized);

        $presidentContentHtml = static::presidentLocaleBodyHtml($presRow);

        $videoDefaults = is_array($defaults['video'] ?? null) ? $defaults['video'] : [];
        $videoStored = is_array($merged['video'] ?? null) ? $merged['video'] : [];
        $videoBase = $videoDefaults[$baseLocale] ?? ['url' => ''];
        $videoLocalized = is_array($videoStored[$baseLocale] ?? null) ? $videoStored[$baseLocale] : [];
        $videoRow = array_merge($videoBase, $videoLocalized);

        $videoBg = $merged['video_background_image'] ?? null;
        if (is_array($videoBg)) {
            $videoBg = $videoBg[0] ?? null;
        }
        $videoBg = filled($videoBg) ? (string) $videoBg : null;

        $bodyDefaults = is_array($defaults['body'] ?? null) ? $defaults['body'] : [];
        $bodyStored = is_array($merged['body'] ?? null) ? $merged['body'] : [];
        $bodyBase = $bodyDefaults[$baseLocale] ?? ['title' => '', 'text' => '', 'image' => null, 'video_url' => ''];
        $bodyLocalized = is_array($bodyStored[$baseLocale] ?? null) ? $bodyStored[$baseLocale] : [];
        $bodyRow = array_merge($bodyBase, $bodyLocalized);

        $bg = $coverRow['background_image'] ?? null;
        if (is_array($bg)) {
            $bg = $bg[0] ?? null;
        }
        $bg = filled($bg) ? (string) $bg : null;

        $coverImage = $merged['cover_image'] ?? null;
        if (is_array($coverImage)) {
            $coverImage = $coverImage[0] ?? null;
        }
        $coverImage = filled($coverImage) ? (string) $coverImage : null;
        $bg = $coverImage ?? $bg;

        $img = $bodyRow['image'] ?? null;
        if (is_array($img)) {
            $img = $img[0] ?? null;
        }
        $img = filled($img) ? (string) $img : null;

        return [
            'menu' => [
                'title' => (string) ($menuRow['title'] ?? ''),
            ],
            'cover' => [
                'title' => (string) ($coverRow['title'] ?? ''),
                'background_image' => $bg,
            ],
            'about' => [
                'teaser' => (string) ($aboutRow['teaser'] ?? ''),
                'description' => (string) ($aboutRow['description'] ?? ''),
                'image_left' => $aboutImageLeft,
                'image_right' => $aboutImageRight,
            ],
            'funfacts' => [
                'label_1' => (string) ($funfactsRow['label_1'] ?? ''),
                'value_1' => (int) ($funfactsRow['value_1'] ?? 0),
                'label_2' => (string) ($funfactsRow['label_2'] ?? ''),
                'value_2' => (int) ($funfactsRow['value_2'] ?? 0),
            ],
            'president' => [
                'title' => (string) ($presRow['title'] ?? ''),
                'years_experience' => $presYears,
                'image' => $presImage,
                'content' => $presidentContentHtml,
                'image_person_name' => (string) ($presRow['image_person_name'] ?? ''),
            ],
            'video' => [
                'url' => (string) ($videoRow['url'] ?? ''),
            ],
            'video_background_image' => $videoBg,
            'body' => [
                'title' => (string) ($bodyRow['title'] ?? ''),
                'text' => (string) ($bodyRow['text'] ?? ''),
                'image' => $img,
                'video_url' => (string) ($bodyRow['video_url'] ?? ''),
            ],
        ];
    }

    /**
     * Homepage data for the current request locale: service ids, headline, and about block (with EN fallback for non-EN locales).
     *
     * @return array{ids: list<int>, menu_title: string, title: string, teaser: string, highlights: list<array{title: string, teaser: string, link: string}>, about: array{title: string, text: string, image: string|null, link: string}, projects_section: array{title: string}, project_ids: list<int>}
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
            foreach (['menu_title', 'title', 'teaser'] as $field) {
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
        $aboutBase = $aboutDefaults[$locale] ?? $aboutDefaults['en'] ?? ['title' => '', 'text' => '', 'image' => null, 'link' => '', 'video_url' => ''];
        $aboutEn = is_array($aboutStored['en'] ?? null) ? $aboutStored['en'] : [];
        $aboutLocalized = is_array($aboutStored[$locale] ?? null) ? $aboutStored[$locale] : [];
        $aboutRow = array_merge($aboutBase, $aboutEn, $aboutLocalized);

        if ($locale !== 'en') {
            foreach (['title', 'text', 'link', 'video_url'] as $field) {
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

        $contactCardsDefaults = is_array($defaults['contact_cards'] ?? null) ? $defaults['contact_cards'] : [];
        $contactCardsStored = is_array($merged['contact_cards'] ?? null) ? $merged['contact_cards'] : [];
        $ccBase = $contactCardsDefaults[$locale] ?? $contactCardsDefaults['en'] ?? [];
        $ccEn = is_array($contactCardsStored['en'] ?? null) ? $contactCardsStored['en'] : [];
        $ccLocalized = is_array($contactCardsStored[$locale] ?? null) ? $contactCardsStored[$locale] : [];
        $contactCards = [];
        for ($i = 0; $i < 3; $i++) {
            $baseRow = is_array($ccBase[$i] ?? null) ? $ccBase[$i] : [];
            $enRow = is_array($ccEn[$i] ?? null) ? $ccEn[$i] : [];
            $locRow = is_array($ccLocalized[$i] ?? null) ? $ccLocalized[$i] : [];
            $row = array_merge($baseRow, $enRow, $locRow);
            if ($locale !== 'en') {
                foreach (['title', 'value', 'button_title', 'button_link'] as $field) {
                    if (($row[$field] ?? '') === '' && ($enRow[$field] ?? '') !== '') {
                        $row[$field] = $enRow[$field];
                    }
                }
            }
            $contactCards[] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
                'value' => isset($row['value']) ? (string) $row['value'] : '',
                'button_title' => isset($row['button_title']) ? (string) $row['button_title'] : '',
                'button_link' => isset($row['button_link']) ? (string) $row['button_link'] : '',
            ];
        }

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
            'menu_title' => (string) ($result['menu_title'] ?? ''),
            'title' => (string) ($result['title'] ?? ''),
            'teaser' => (string) ($result['teaser'] ?? ''),
            'highlights' => $result['highlights'],
            'about' => [
                'title' => (string) ($aboutRow['title'] ?? ''),
                'text' => (string) ($aboutRow['text'] ?? ''),
                'image' => $image,
                'link' => (string) ($aboutRow['link'] ?? ''),
                'video_url' => (string) ($aboutRow['video_url'] ?? ''),
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
            'contact_cards' => $contactCards,
            'gallery_id' => $galleryId,
            'gallery_instagram_link' => (string) ($merged['gallery_instagram_link'] ?? ''),
            'gallery' => $galleryPayload,
        ];
    }

    /**
     * Email, phone, and address from Home → Contact information (3 cards).
     *
     * @return array{
     *   email: string,
     *   email_href: string,
     *   phone: string,
     *   phone_href: string,
     *   address: string,
     *   address_href: string
     * }
     */
    public static function homeContactCardDetails(): array
    {
        $cards = self::homePageContent()['contact_cards'] ?? [];
        $card = static fn (int $index): array => is_array($cards[$index] ?? null) ? $cards[$index] : [];

        $emailCard = $card(0);
        $phoneCard = $card(1);
        $addressCard = $card(2);

        $email = (string) ($emailCard['value'] ?? '');
        $phone = (string) ($phoneCard['value'] ?? '');
        $address = (string) ($addressCard['value'] ?? '');

        return [
            'email' => $email,
            'email_href' => filled($emailCard['button_link'] ?? null)
                ? (string) $emailCard['button_link']
                : (filled($email) ? 'mailto:'.$email : ''),
            'phone' => $phone,
            'phone_href' => filled($phoneCard['button_link'] ?? null)
                ? (string) $phoneCard['button_link']
                : (filled($phone) ? 'tel:'.preg_replace('/[^0-9+]/', '', $phone) : ''),
            'address' => $address,
            'address_href' => filled($addressCard['button_link'] ?? null)
                ? (string) $addressCard['button_link']
                : route('contact'),
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
            ->with(['categories', 'status'])
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
                foreach (['menu_title', 'title', 'intro', 'email', 'address'] as $field) {
                    $current = $result[$field] ?? null;
                    if (($current === null || $current === '') && array_key_exists($field, $en)) {
                        $fromEn = $en[$field];
                        if ($fromEn !== null && $fromEn !== '') {
                            $result[$field] = $fromEn;
                        }
                    }
                }
            }

            $social = is_array($merged['social'] ?? null) ? $merged['social'] : [];
            $instagram = is_array($social['instagram'] ?? null) ? $social['instagram'] : [];

            $galleryId = $merged['gallery_id'] ?? null;
            $galleryId = ($galleryId !== null && $galleryId !== '' && (int) $galleryId > 0) ? (int) $galleryId : null;

            return array_merge($result, [
                'gallery_id' => $galleryId,
                'social' => [
                    'instagram' => [
                        'url' => isset($instagram['url']) ? (string) $instagram['url'] : '',
                        'name' => isset($instagram['name']) ? (string) $instagram['name'] : '',
                    ],
                    'facebook_url' => isset($social['facebook_url']) ? (string) $social['facebook_url'] : '',
                    'linkedin_url' => isset($social['linkedin_url']) ? (string) $social['linkedin_url'] : '',
                    'youtube_url' => isset($social['youtube_url']) ? (string) $social['youtube_url'] : '',
                ],
                'google_map_embed' => isset($merged['google_map_embed']) ? (string) $merged['google_map_embed'] : '',
            ]);
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
