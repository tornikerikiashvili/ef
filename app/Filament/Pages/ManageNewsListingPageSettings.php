<?php

namespace App\Filament\Pages;

use App\Models\News;
use App\Models\Page;
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

class ManageNewsListingPageSettings extends FilamentPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'News page';

    protected static ?string $navigationLabel = 'News page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 15;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $key = Page::KEY_NEWS_LISTING_PAGE;

        Page::ensureNewsListingPageBlock();

        $row = Page::query()->where('key', $key)->first();

        $this->form->fill([
            $key => array_replace_recursive(
                Page::defaultNewsListingPagePayload(),
                is_array($row?->payload) ? $row->payload : []
            ),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $key = Page::KEY_NEWS_LISTING_PAGE;
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
        $defaults = Page::defaultNewsListingPagePayload();
        $merged = array_replace_recursive($defaults, $payload);

        $cover = $merged['cover_image'] ?? null;
        if (is_array($cover)) {
            $cover = $cover[0] ?? null;
        }
        $merged['cover_image'] = filled($cover) ? (string) $cover : null;

        $news = $merged['news'] ?? [];
        $out = [];
        foreach (is_array($news) ? $news : [] as $id) {
            if ($id === null || $id === '') {
                continue;
            }
            $out[] = (int) $id;
        }
        $merged['news'] = array_values(array_unique($out));

        $merged['locales'] = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        foreach (array_keys($defaults['locales'] ?? []) as $locale) {
            if (! is_string($locale)) {
                continue;
            }
            $row = is_array($merged['locales'][$locale] ?? null) ? $merged['locales'][$locale] : [];
            $merged['locales'][$locale] = [
                'title' => isset($row['title']) ? (string) $row['title'] : '',
            ];
        }

        return $merged;
    }

    public function form(Schema $schema): Schema
    {
        $key = Page::KEY_NEWS_LISTING_PAGE;
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
                                ->directory('news/listing/cover')
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
                    Section::make('News list')
                        ->description('Optional curated list. If empty, the public page can show all news by date.')
                        ->schema([
                            Select::make('news')
                                ->label('News posts')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->options(fn (): array => News::query()
                                    ->orderByDesc('id')
                                    ->get()
                                    ->mapWithKeys(fn (News $news): array => [
                                        $news->id => $news->getTranslation('title', 'en')
                                            ?: $news->getTranslation('title', 'ka')
                                            ?: ('News #'.$news->id),
                                    ])
                                    ->all()),
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

