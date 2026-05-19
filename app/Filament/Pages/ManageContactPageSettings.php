<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\ProvidesPageSeoFormComponents;
use App\Models\Gallery;
use App\Models\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

class ManageContactPageSettings extends FilamentPage
{
    use ProvidesPageSeoFormComponents;

    protected static string|UnitEnum|null $navigationGroup = 'Pages';

    protected static ?string $title = 'Contact';

    protected static ?string $navigationLabel = 'Contact';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?int $navigationSort = 60;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $key = Page::KEY_CONTACT_PAGE;

        Page::query()->firstOrCreate(
            ['key' => $key],
            ['payload' => Page::defaultContactPayload()]
        );

        $row = Page::query()->where('key', $key)->first();
        $this->form->fill([
            $key => array_replace_recursive(
                Page::defaultContactPayload(),
                is_array($row?->payload) ? $row->payload : []
            ),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $key = Page::KEY_CONTACT_PAGE;
        $payload = $this->normalizeContactPayload(is_array($state[$key] ?? null) ? $state[$key] : []);

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
    protected function normalizeContactPayload(array $payload): array
    {
        $defaults = Page::defaultContactPayload();
        $merged = array_replace_recursive($defaults, $payload);

        $locales = config('cms.supported_locales', ['en', 'ka']);

        foreach ($locales as $locale) {
            $row = is_array($merged[$locale] ?? null) ? $merged[$locale] : [];
            $merged[$locale] = [
                'menu_title' => isset($row['menu_title']) ? (string) $row['menu_title'] : '',
                'title' => isset($row['title']) ? (string) $row['title'] : '',
                'intro' => isset($row['intro']) ? (string) $row['intro'] : '',
                'email' => isset($row['email']) ? (string) $row['email'] : '',
                'address' => isset($row['address']) ? (string) $row['address'] : '',
            ];
        }

        $galleryId = $merged['gallery_id'] ?? null;
        $merged['gallery_id'] = ($galleryId !== null && $galleryId !== '' && (int) $galleryId > 0) ? (int) $galleryId : null;

        $social = is_array($merged['social'] ?? null) ? $merged['social'] : [];
        $instagram = is_array($social['instagram'] ?? null) ? $social['instagram'] : [];
        $merged['social'] = [
            'instagram' => [
                'url' => isset($instagram['url']) ? (string) $instagram['url'] : '',
                'name' => isset($instagram['name']) ? (string) $instagram['name'] : '',
            ],
            'facebook_url' => isset($social['facebook_url']) ? (string) $social['facebook_url'] : '',
            'linkedin_url' => isset($social['linkedin_url']) ? (string) $social['linkedin_url'] : '',
            'youtube_url' => isset($social['youtube_url']) ? (string) $social['youtube_url'] : '',
        ];

        $merged['google_map_embed'] = isset($merged['google_map_embed']) ? (string) $merged['google_map_embed'] : '';

        return Page::normalizeSeoInPagePayload($merged);
    }

    public function form(Schema $schema): Schema
    {
        return $this->defaultForm($schema)
            ->components([
                Section::make('Contact page copy')
                    ->description('Intro, email, and address (per language). Shown on the mobile menu and site sidebar.')
                    ->schema($this->localeTabsForContactBlock())
                    ->columns(1),
                Section::make('Media & social links')
                    ->description('Gallery for the aside drawer. Instagram name/link appear in the aside drawer, mobile menu, and footer.')
                    ->schema($this->contactMediaAndSocialFields())
                    ->columns(2),
                $this->pageSeoSectionForStandaloneStatePath(Page::KEY_CONTACT_PAGE),
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
    protected function localeTabsForContactBlock(): array
    {
        $key = Page::KEY_CONTACT_PAGE;
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return [
            Group::make([
                Tabs::make($key.'_locales')
                    ->tabs(
                        collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                            ->statePath($locale)
                            ->schema($this->contactFields())
                        )->all()
                    ),
            ])
                ->statePath($key),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function contactFields(): array
    {
        return [
            TextInput::make('menu_title')
                ->label('Menu title')
                ->maxLength(255)
                ->columnSpanFull(),
            TextInput::make('title')
                ->label('Page title')
                ->maxLength(255)
                ->columnSpanFull(),
            Textarea::make('intro')
                ->label('Intro')
                ->rows(5)
                ->columnSpanFull(),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->maxLength(255),
            TextInput::make('address')
                ->label('Address')
                ->maxLength(65535),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function contactMediaAndSocialFields(): array
    {
        $key = Page::KEY_CONTACT_PAGE;

        return [
            Group::make([
                Select::make('gallery_id')
                    ->label('Gallery')
                    ->options(fn (): array => Gallery::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->placeholder('None')
                    ->native(false)
                    ->columnSpanFull(),

                TextInput::make('social.instagram.name')
                    ->label('Instagram name')
                    ->helperText('Shown next to the icon in the aside drawer (e.g. @yourhandle).')
                    ->maxLength(255),
                TextInput::make('social.instagram.url')
                    ->label('Instagram link')
                    ->helperText('Full profile URL; makes the name clickable in the aside.')
                    ->url()
                    ->maxLength(2048),

                TextInput::make('social.facebook_url')
                    ->label('Facebook link')
                    ->url()
                    ->maxLength(2048),
                TextInput::make('social.linkedin_url')
                    ->label('LinkedIn link')
                    ->url()
                    ->maxLength(2048),
                TextInput::make('social.youtube_url')
                    ->label('YouTube link')
                    ->url()
                    ->maxLength(2048)
                    ->placeholder('https://www.youtube.com/@yourchannel'),

                Textarea::make('google_map_embed')
                    ->label('Google Map embed code')
                    ->helperText('Paste the iframe embed code from Google Maps.')
                    ->rows(5)
                    ->columnSpanFull(),
            ])->statePath($key),
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
