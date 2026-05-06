<?php

namespace App\Filament\Pages;

use App\Models\Gallery;
use App\Models\Page;
use App\Models\PartnerLogo;
use App\Models\Project;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page as FilamentPage;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use UnitEnum;

class ManageHomePageSettings extends FilamentPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Pages';

    protected static ?string $title = 'Home';

    protected static ?string $navigationLabel = 'Home';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 10;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        Page::ensureHomePageBlock();

        $key = Page::KEY_HOME_PAGE;
        $row = Page::query()->where('key', $key)->first();

        $this->form->fill([
            $key => array_replace_recursive(
                Page::defaultHomePagePayload(),
                is_array($row?->payload) ? $row->payload : []
            ),
        ]);
    }

    /**
     * When “Headline & highlights” is hidden in the form, Filament may omit locale keys from state.
     * Merge stored headline blocks so saving other sections does not wipe title/teaser/highlights.
     *
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    protected function mergePreservedHomeHeadlineLocalesFromDatabase(array $incoming): array
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);
        $existing = Page::query()->where('key', Page::KEY_HOME_PAGE)->value('payload');
        if (! is_array($existing)) {
            return $incoming;
        }

        foreach ($locales as $locale) {
            if (! is_string($locale)) {
                continue;
            }
            if (isset($incoming[$locale])) {
                continue;
            }
            if (isset($existing[$locale]) && is_array($existing[$locale])) {
                $incoming[$locale] = $existing[$locale];
            }
        }

        return $incoming;
    }

    /**
     * Home form no longer includes News fields; keep stored `news_ids` / `news_section` on save.
     *
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    protected function mergePreservedHomeNewsFromDatabase(array $incoming): array
    {
        $existing = Page::query()->where('key', Page::KEY_HOME_PAGE)->value('payload');
        if (! is_array($existing)) {
            return $incoming;
        }

        foreach (['news_ids', 'news_section'] as $field) {
            if (! array_key_exists($field, $incoming) && array_key_exists($field, $existing)) {
                $incoming[$field] = $existing[$field];
            }
        }

        return $incoming;
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $key = Page::KEY_HOME_PAGE;
        $incoming = is_array($state[$key] ?? null) ? $state[$key] : [];
        $incoming = $this->mergePreservedHomeHeadlineLocalesFromDatabase($incoming);
        $incoming = $this->mergePreservedHomeNewsFromDatabase($incoming);
        $payload = $this->normalizeHomePagePayload($incoming);

        Page::query()->updateOrCreate(
            ['key' => $key],
            ['payload' => $payload]
        );

        Notification::make()
            ->title('Saved')
            ->success()
            ->send();
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function normalizeHomePagePayload(array $payload): array
    {
        $defaults = Page::defaultHomePagePayload();
        $merged = array_replace_recursive($defaults, $payload);

        $ids = $merged['ids'] ?? [];
        $outIds = [];
        foreach (is_array($ids) ? $ids : [] as $id) {
            if ($id === null || $id === '') {
                continue;
            }
            $outIds[] = (int) $id;
        }
        $merged['ids'] = array_values(array_unique($outIds));

        $projectIds = $merged['project_ids'] ?? [];
        $outProjectIds = [];
        foreach (is_array($projectIds) ? $projectIds : [] as $pid) {
            if ($pid === null || $pid === '') {
                continue;
            }
            $outProjectIds[] = (int) $pid;
        }
        $merged['project_ids'] = array_values(array_unique($outProjectIds));

        $partnerLogoIds = $merged['partner_logo_ids'] ?? [];
        $outPartnerIds = [];
        foreach (is_array($partnerLogoIds) ? $partnerLogoIds : [] as $pid) {
            if ($pid === null || $pid === '') {
                continue;
            }
            $outPartnerIds[] = (int) $pid;
        }
        $merged['partner_logo_ids'] = array_values(array_unique($outPartnerIds));

        $newsIdsRaw = $merged['news_ids'] ?? [];
        $outNewsIds = [];
        foreach (is_array($newsIdsRaw) ? $newsIdsRaw : [] as $nid) {
            if ($nid === null || $nid === '') {
                continue;
            }
            $outNewsIds[] = (int) $nid;
        }
        $merged['news_ids'] = array_values(array_unique($outNewsIds));

        $rootMetaKeys = [
            'ids',
            'about',
            'project_ids',
            'projects_section',
            'partner_logo_ids',
            'partners_section',
            'news_ids',
            'news_section',
            'show_contact_form',
            'gallery_id',
            'gallery_instagram_link',
        ];

        foreach (array_keys($defaults) as $locale) {
            if (in_array($locale, $rootMetaKeys, true)) {
                continue;
            }
            if (! is_string($locale) || ! isset($defaults[$locale]['title'])) {
                continue;
            }
            $row = is_array($merged[$locale] ?? null) ? $merged[$locale] : [];
            $highlights = $defaults[$locale]['highlights'];
            $storedHighlights = is_array($row['highlights'] ?? null) ? $row['highlights'] : [];
            for ($i = 0; $i < 3; $i++) {
                $h = $storedHighlights[$i] ?? [];
                $highlights[$i] = [
                    'title' => isset($h['title']) ? (string) $h['title'] : '',
                    'teaser' => isset($h['teaser']) ? (string) $h['teaser'] : '',
                    'link' => isset($h['link']) ? (string) $h['link'] : '',
                ];
            }
            $merged[$locale] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
                'teaser' => isset($row['teaser']) ? (string) $row['teaser'] : '',
                'highlights' => $highlights,
            ];
        }

        $merged['about'] = is_array($merged['about'] ?? null) ? $merged['about'] : [];
        foreach (array_keys($defaults['about'] ?? []) as $aboutLocale) {
            if (! is_string($aboutLocale)) {
                continue;
            }
            $row = is_array($merged['about'][$aboutLocale] ?? null) ? $merged['about'][$aboutLocale] : [];
            $image = $row['image'] ?? null;
            if (is_array($image)) {
                $image = $image[0] ?? null;
            }
            $merged['about'][$aboutLocale] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
                'text' => isset($row['text']) ? (string) $row['text'] : '',
                'image' => filled($image) ? (string) $image : null,
                'link' => isset($row['link']) ? (string) $row['link'] : '',
            ];
        }

        $merged['projects_section'] = is_array($merged['projects_section'] ?? null) ? $merged['projects_section'] : [];
        foreach (array_keys($defaults['projects_section'] ?? []) as $psLocale) {
            if (! is_string($psLocale)) {
                continue;
            }
            $row = is_array($merged['projects_section'][$psLocale] ?? null) ? $merged['projects_section'][$psLocale] : [];
            $merged['projects_section'][$psLocale] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
            ];
        }

        $merged['partners_section'] = is_array($merged['partners_section'] ?? null) ? $merged['partners_section'] : [];
        foreach (array_keys($defaults['partners_section'] ?? []) as $pLocale) {
            if (! is_string($pLocale)) {
                continue;
            }
            $row = is_array($merged['partners_section'][$pLocale] ?? null) ? $merged['partners_section'][$pLocale] : [];
            $merged['partners_section'][$pLocale] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
            ];
        }

        $merged['news_section'] = is_array($merged['news_section'] ?? null) ? $merged['news_section'] : [];
        foreach (array_keys($defaults['news_section'] ?? []) as $nLocale) {
            if (! is_string($nLocale)) {
                continue;
            }
            $row = is_array($merged['news_section'][$nLocale] ?? null) ? $merged['news_section'][$nLocale] : [];
            $merged['news_section'][$nLocale] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
                'teaser' => isset($row['teaser']) ? (string) $row['teaser'] : '',
            ];
        }

        $merged['show_contact_form'] = filter_var($merged['show_contact_form'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $gid = $merged['gallery_id'] ?? null;
        $merged['gallery_id'] = ($gid !== null && $gid !== '' && (int) $gid > 0) ? (int) $gid : null;

        $merged['gallery_instagram_link'] = isset($merged['gallery_instagram_link'])
            ? (string) $merged['gallery_instagram_link']
            : '';
        if (strlen($merged['gallery_instagram_link']) > 2048) {
            $merged['gallery_instagram_link'] = substr($merged['gallery_instagram_link'], 0, 2048);
        }

        return $merged;
    }

    public function form(Schema $schema): Schema
    {
        $key = Page::KEY_HOME_PAGE;

        return $this->defaultForm($schema)
            ->components([
                Group::make([
                    Section::make('Featured services')
                        ->description('Choose which services appear in the home hero.')
                        ->schema([
                            Select::make('ids')
                                ->label('Hero: featured services')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->options(fn (): array => Service::query()
                                    ->orderBy('id')
                                    ->get()
                                    ->mapWithKeys(fn (Service $service): array => [
                                        $service->id => $service->getTranslation('title', 'en')
                                            ?: $service->getTranslation('title', 'ka')
                                            ?: ('Service #'.$service->id),
                                    ])
                                    ->all()),
                        ])
                        ->columns(1),
                    Section::make('Headline & highlights')
                        ->description('Title, teaser, and three highlight cards per language (EN / KA).')
                        ->schema([
                            Tabs::make($key.'_locales')
                                ->tabs(
                                    collect(config('cms.supported_locales', ['en', 'ka']))->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath($locale)
                                        ->schema($this->headlineTabFields())
                                    )->all()
                                )->columns(1)->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
                        ])
                        ->columns(1)
                        // Hidden from editors per client request; data remains in DB and on save (see mergePreservedHomeHeadlineLocalesFromDatabase).
                        ->hidden(),
                    Section::make('About')
                        ->description('Optional home about band: title, body, image, and link (per language).')
                        ->schema([
                            Tabs::make($key.'_about_locales')
                                ->tabs(
                                    collect(config('cms.supported_locales', ['en', 'ka']))->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('about.'.$locale)
                                        ->schema($this->aboutTabFields())
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
                        ])
                        ->columns(1),
                    Section::make('Projects')
                        ->description('Section title per language; the same projects are shown for all languages.')
                        ->schema([
                            Tabs::make($key.'_projects_section_locales')
                                ->tabs(
                                    collect(config('cms.supported_locales', ['en', 'ka']))->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('projects_section.'.$locale)
                                        ->schema($this->projectsSectionTabFields())
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
                            Select::make('project_ids')
                                ->label('Projects')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->options(fn (): array => Project::query()
                                    ->orderByDesc('id')
                                    ->get()
                                    ->mapWithKeys(fn (Project $project): array => [
                                        $project->id => $project->getTranslation('title', 'en')
                                            ?: $project->getTranslation('title', 'ka')
                                            ?: ('Project #'.$project->id),
                                    ])
                                    ->all()),
                        ])
                        ->columns(1),
                    Section::make('Partners')
                        ->description('Section title per language; the same partner logos are shown for every language.')
                        ->schema([
                            Tabs::make($key.'_partners_section_locales')
                                ->tabs(
                                    collect(config('cms.supported_locales', ['en', 'ka']))->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('partners_section.'.$locale)
                                        ->schema($this->partnersSectionTabFields())
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
                            Select::make('partner_logo_ids')
                                ->label('Partner logos')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->options(fn (): array => PartnerLogo::query()
                                    ->orderBy('id')
                                    ->get()
                                    ->mapWithKeys(fn (PartnerLogo $partner): array => [
                                        $partner->id => $partner->getTranslation('title', 'en')
                                            ?: $partner->getTranslation('title', 'ka')
                                            ?: ('Partner #'.$partner->id),
                                    ])
                                    ->all()),
                        ])
                        ->columns(1),
                    Section::make('Contact form')
                        ->description('Whether to show the contact form on the public home page.')
                        ->schema([
                            Toggle::make('show_contact_form')
                                ->label('Show contact form on home')
                                ->default(false),
                        ])
                        ->columns(1),
                    Section::make('Gallery')
                        ->description('Pick one gallery (create and edit galleries under Content → Galleries).')
                        ->schema([
                            Select::make('gallery_id')
                                ->label('Gallery')
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->options(fn (): array => Gallery::query()
                                    ->orderByDesc('id')
                                    ->get()
                                    ->mapWithKeys(fn (Gallery $gallery): array => [
                                        $gallery->id => filled($gallery->name)
                                            ? $gallery->name
                                            : ('Gallery #'.$gallery->id),
                                    ])
                                    ->all())
                                ->nullable(),
                            TextInput::make('gallery_instagram_link')
                                ->label('Instagram link')
                                ->maxLength(2048)
                                ->placeholder('https://www.instagram.com/yourprofile/')
                                ->helperText('URL for the “Follow on Instagram” button next to the gallery images on the home page.'),
                        ])
                        ->columns(1),
                ])
                    ->statePath($key),
            ]);
    }

    protected function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data');
    }

    /**
     * @return list<Component>
     */
    protected function headlineTabFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->maxLength(65535),
            Textarea::make('teaser')
                ->label('Teaser')
                ->rows(4)
                ->columnSpanFull(),
            Repeater::make('highlights')
                ->label('Highlight boxes')
                ->minItems(3)
                ->maxItems(3)
                ->grid(['default' => 1, 'md' => 3])
                ->reorderable(false)
                ->addable(false)
                ->deletable(false)
                ->schema([
                    Group::make([
                        TextInput::make('title')->label('Title')->maxLength(65535),
                        Textarea::make('teaser')->label('Teaser')->rows(3)->columnSpanFull(),
                        TextInput::make('link')->label('Link')->maxLength(2048),
                    ])
                        ->columns(1)
                        ->extraAttributes([
                            'class' => 'rounded-lg bg-zinc-100 p-4 ring-1 ring-zinc-950/5 dark:bg-zinc-900 dark:ring-white/10',
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function aboutTabFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->maxLength(65535),
            RichEditor::make('text')
                ->label('Text')
                ->columnSpanFull(),
            FileUpload::make('image')
                ->label('Image')
                ->disk('public')
                ->directory('home/about')
                ->visibility('public')
                ->image()
                ->columnSpanFull(),
            TextInput::make('link')
                ->label('Link')
                ->maxLength(2048),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function projectsSectionTabFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Section title')
                ->maxLength(65535),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function partnersSectionTabFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Section title')
                ->maxLength(65535),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    EmbeddedSchema::make('form'),
                ])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ])
                            ->alignment(Alignment::Start)
                            ->key('form-actions'),
                    ]),
            ]);
    }
}
