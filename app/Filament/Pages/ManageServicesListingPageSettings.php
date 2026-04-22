<?php

namespace App\Filament\Pages;

use App\Models\Page;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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

class ManageServicesListingPageSettings extends FilamentPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Services listing';

    protected static ?string $navigationLabel = 'Services listing';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 13;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $key = Page::KEY_SERVICES_LISTING_PAGE;

        Page::ensureServicesListingPageBlock();

        $row = Page::query()->where('key', $key)->first();

        $this->form->fill([
            $key => array_replace_recursive(
                Page::defaultServicesListingPagePayload(),
                is_array($row?->payload) ? $row->payload : []
            ),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $key = Page::KEY_SERVICES_LISTING_PAGE;
        $payload = $this->normalizePayload(is_array($state[$key] ?? null) ? $state[$key] : []);

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
    protected function normalizePayload(array $payload): array
    {
        $defaults = Page::defaultServicesListingPagePayload();
        $merged = array_replace_recursive($defaults, $payload);

        $cover = $merged['cover_image'] ?? null;
        if (is_array($cover)) {
            $cover = $cover[0] ?? null;
        }
        $merged['cover_image'] = filled($cover) ? (string) $cover : null;

        $videoBg = $merged['video_background_image'] ?? null;
        if (is_array($videoBg)) {
            $videoBg = $videoBg[0] ?? null;
        }
        $merged['video_background_image'] = filled($videoBg) ? (string) $videoBg : null;

        $services = $merged['services'] ?? [];
        $out = [];
        foreach (is_array($services) ? $services : [] as $id) {
            if ($id === null || $id === '') {
                continue;
            }
            $out[] = (int) $id;
        }
        $merged['services'] = array_values(array_unique($out));

        $merged['locales'] = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        $merged['video'] = is_array($merged['video'] ?? null) ? $merged['video'] : [];
        foreach (array_keys($defaults['locales'] ?? []) as $locale) {
            if (! is_string($locale)) {
                continue;
            }
            $row = is_array($merged['locales'][$locale] ?? null) ? $merged['locales'][$locale] : [];
            $merged['locales'][$locale] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
                'services_title' => isset($row['services_title']) ? (string) $row['services_title'] : '',
            ];

            $videoRow = is_array($merged['video'][$locale] ?? null) ? $merged['video'][$locale] : [];
            $url = isset($videoRow['url']) ? (string) $videoRow['url'] : '';
            if (strlen($url) > 2048) {
                $url = substr($url, 0, 2048);
            }
            $merged['video'][$locale] = [
                'url' => $url,
            ];
        }

        return $merged;
    }

    public function form(Schema $schema): Schema
    {
        $key = Page::KEY_SERVICES_LISTING_PAGE;
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return $this->defaultForm($schema)
            ->components([
                Group::make([
                    Section::make('Header')
                        ->description('Page title (per language) and shared cover image.')
                        ->schema([
                            FileUpload::make('cover_image')
                                ->label('Cover image (shared)')
                                ->disk('public')
                                ->directory('services/listing/cover')
                                ->visibility('public')
                                ->image()
                                ->columnSpanFull(),
                            Tabs::make($key.'_header_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('locales.'.$locale)
                                        ->schema([
                                            TextInput::make('title')
                                                ->label('Title')
                                                ->maxLength(65535),
                                        ])
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes(['style' => 'background-color: #fff7ef']),
                        ])
                        ->columns(1),
                    Section::make('Services list')
                        ->description('Section title (per language) and curated services order.')
                        ->schema([
                            Tabs::make($key.'_services_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('locales.'.$locale)
                                        ->schema([
                                            TextInput::make('services_title')
                                                ->label('Services list title')
                                                ->maxLength(65535),
                                        ])
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes(['style' => 'background-color: #fff7ef']),
                            Select::make('services')
                                ->label('Services')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->options(fn (): array => Service::query()
                                    ->orderByDesc('id')
                                    ->get()
                                    ->mapWithKeys(fn (Service $service): array => [
                                        $service->id => $service->getTranslation('title', 'en')
                                            ?: $service->getTranslation('title', 'ka')
                                            ?: ('Service #'.$service->id),
                                    ])
                                    ->all()),
                        ])
                        ->columns(1),
                    Section::make('Video')
                        ->description('Shared background image and per-language YouTube URL.')
                        ->schema([
                            FileUpload::make('video_background_image')
                                ->label('Video background image (shared)')
                                ->disk('public')
                                ->directory('services/listing/video')
                                ->visibility('public')
                                ->image()
                                ->columnSpanFull(),
                            Tabs::make($key.'_video_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('video.'.$locale)
                                        ->schema([
                                            TextInput::make('url')
                                                ->label('YouTube URL')
                                                ->maxLength(2048)
                                                ->placeholder('https://www.youtube.com/watch?v=...'),
                                        ])
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes(['style' => 'background-color: #fff7ef']),
                        ])
                        ->columns(1),
                ])
                    ->statePath($key),
            ]);
    }

    protected function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
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

