<?php

namespace App\Filament\Pages;

use App\Models\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
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

class ManageAboutPageSettings extends FilamentPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'About page';

    protected static ?string $navigationLabel = 'About page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?int $navigationSort = 12;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $key = Page::KEY_ABOUT_PAGE;

        Page::ensureAboutPageBlock();

        $row = Page::query()->where('key', $key)->first();

        $this->form->fill([
            $key => array_replace_recursive(
                Page::defaultAboutPagePayload(),
                is_array($row?->payload) ? $row->payload : []
            ),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $key = Page::KEY_ABOUT_PAGE;
        $payload = $this->normalizeAboutPayload(is_array($state[$key] ?? null) ? $state[$key] : []);

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
    protected function normalizeAboutPayload(array $payload): array
    {
        $defaults = Page::defaultAboutPagePayload();
        $merged = array_replace_recursive($defaults, $payload);

        $merged['cover'] = is_array($merged['cover'] ?? null) ? $merged['cover'] : [];
        $merged['about_images'] = is_array($merged['about_images'] ?? null) ? $merged['about_images'] : [];
        $merged['about'] = is_array($merged['about'] ?? null) ? $merged['about'] : [];
        $merged['funfacts'] = is_array($merged['funfacts'] ?? null) ? $merged['funfacts'] : [];
        $merged['president'] = is_array($merged['president'] ?? null) ? $merged['president'] : [];
        $merged['video'] = is_array($merged['video'] ?? null) ? $merged['video'] : [];
        $merged['video_background_image'] = $merged['video_background_image'] ?? null;
        $merged['body'] = is_array($merged['body'] ?? null) ? $merged['body'] : [];

        $left = $merged['about_images']['image_left'] ?? null;
        if (is_array($left)) {
            $left = $left[0] ?? null;
        }
        $right = $merged['about_images']['image_right'] ?? null;
        if (is_array($right)) {
            $right = $right[0] ?? null;
        }
        $merged['about_images'] = [
            'image_left' => filled($left) ? (string) $left : null,
            'image_right' => filled($right) ? (string) $right : null,
        ];

        // Backward-compat: if older payload stored images per-locale, copy first found into shared fields.
        if (! filled($merged['about_images']['image_left'] ?? null) || ! filled($merged['about_images']['image_right'] ?? null)) {
            foreach (array_keys($defaults['about'] ?? []) as $locale) {
                if (! is_string($locale)) {
                    continue;
                }
                $row = is_array($merged['about'][$locale] ?? null) ? $merged['about'][$locale] : [];
                if (! filled($merged['about_images']['image_left'] ?? null) && filled($row['image_left'] ?? null)) {
                    $merged['about_images']['image_left'] = is_array($row['image_left']) ? ($row['image_left'][0] ?? null) : $row['image_left'];
                }
                if (! filled($merged['about_images']['image_right'] ?? null) && filled($row['image_right'] ?? null)) {
                    $merged['about_images']['image_right'] = is_array($row['image_right']) ? ($row['image_right'][0] ?? null) : $row['image_right'];
                }
            }

            $merged['about_images']['image_left'] = filled($merged['about_images']['image_left'] ?? null)
                ? (string) $merged['about_images']['image_left']
                : null;
            $merged['about_images']['image_right'] = filled($merged['about_images']['image_right'] ?? null)
                ? (string) $merged['about_images']['image_right']
                : null;
        }

        foreach (array_keys($defaults['cover'] ?? []) as $locale) {
            if (! is_string($locale)) {
                continue;
            }

            $cover = is_array($merged['cover'][$locale] ?? null) ? $merged['cover'][$locale] : [];
            $bg = $cover['background_image'] ?? null;
            if (is_array($bg)) {
                $bg = $bg[0] ?? null;
            }

            $merged['cover'][$locale] = [
                'title' => isset($cover['title']) ? (string) $cover['title'] : '',
                'background_image' => filled($bg) ? (string) $bg : null,
            ];

            $about = is_array($merged['about'][$locale] ?? null) ? $merged['about'][$locale] : [];
            $merged['about'][$locale] = [
                'teaser' => isset($about['teaser']) ? (string) $about['teaser'] : '',
                'description' => isset($about['description']) ? (string) $about['description'] : '',
            ];

            $funfacts = is_array($merged['funfacts'][$locale] ?? null) ? $merged['funfacts'][$locale] : [];
            $merged['funfacts'][$locale] = [
                'label_1' => isset($funfacts['label_1']) ? (string) $funfacts['label_1'] : '',
                'value_1' => isset($funfacts['value_1']) ? (int) $funfacts['value_1'] : 0,
                'label_2' => isset($funfacts['label_2']) ? (string) $funfacts['label_2'] : '',
                'value_2' => isset($funfacts['value_2']) ? (int) $funfacts['value_2'] : 0,
            ];

            $presLocales = is_array($merged['president']['locales'] ?? null) ? $merged['president']['locales'] : [];
            $presLocale = is_array($presLocales[$locale] ?? null) ? $presLocales[$locale] : [];
            $presItems = is_array($presLocale['items'] ?? null) ? $presLocale['items'] : [];
            $outItems = [];
            foreach ($presItems as $item) {
                $item = is_array($item) ? $item : [];
                $outItems[] = [
                    'title' => isset($item['title']) ? (string) $item['title'] : '',
                    'content' => isset($item['content']) ? (string) $item['content'] : '',
                ];
            }
            $merged['president']['locales'][$locale] = [
                'title' => isset($presLocale['title']) ? (string) $presLocale['title'] : '',
                'items' => $outItems,
            ];

            $video = is_array($merged['video'][$locale] ?? null) ? $merged['video'][$locale] : [];
            $url = isset($video['url']) ? (string) $video['url'] : '';
            if (strlen($url) > 2048) {
                $url = substr($url, 0, 2048);
            }
            $merged['video'][$locale] = [
                'url' => $url,
            ];

            $body = is_array($merged['body'][$locale] ?? null) ? $merged['body'][$locale] : [];
            $img = $body['image'] ?? null;
            if (is_array($img)) {
                $img = $img[0] ?? null;
            }

            $videoUrl = isset($body['video_url']) ? (string) $body['video_url'] : '';
            if (strlen($videoUrl) > 2048) {
                $videoUrl = substr($videoUrl, 0, 2048);
            }

            $merged['body'][$locale] = [
                'title' => isset($body['title']) ? (string) $body['title'] : '',
                'text' => isset($body['text']) ? (string) $body['text'] : '',
                'image' => filled($img) ? (string) $img : null,
                'video_url' => $videoUrl,
            ];
        }

        $presYears = $merged['president']['years_experience'] ?? 0;
        $merged['president']['years_experience'] = (int) $presYears;
        if ($merged['president']['years_experience'] < 0) {
            $merged['president']['years_experience'] = 0;
        }
        $presImage = $merged['president']['image'] ?? null;
        if (is_array($presImage)) {
            $presImage = $presImage[0] ?? null;
        }
        $merged['president']['image'] = filled($presImage) ? (string) $presImage : null;
        $merged['president']['locales'] = is_array($merged['president']['locales'] ?? null) ? $merged['president']['locales'] : [];

        $videoBg = $merged['video_background_image'] ?? null;
        if (is_array($videoBg)) {
            $videoBg = $videoBg[0] ?? null;
        }
        $merged['video_background_image'] = filled($videoBg) ? (string) $videoBg : null;

        return $merged;
    }

    public function form(Schema $schema): Schema
    {
        $key = Page::KEY_ABOUT_PAGE;
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return $this->defaultForm($schema)
            ->components([
                Group::make([
                    Section::make('Cover')
                        ->description('Page title and optional background image (per language).')
                        ->schema([
                            Tabs::make($key.'_cover_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('cover.'.$locale)
                                        ->schema($this->coverFields())
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
                        ])
                        ->columns(1),
                    Section::make('About section')
                        ->description('Two shared images + teaser + description (per language). Used in the first “about” block on the public About page.')
                        ->schema([
                            Group::make($this->aboutImagesFields())
                                ->columns(2),
                            Tabs::make($key.'_about_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('about.'.$locale)
                                        ->schema($this->aboutSectionFields())
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
                            Tabs::make($key.'_funfacts_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('funfacts.'.$locale)
                                        ->schema($this->funfactsFields())
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
                        ])
                        ->columns(1),
                    Section::make('President section')
                        ->description('Title and accordion content (per language), plus shared experience years and image.')
                        ->schema([
                            Group::make([
                                TextInput::make('president.years_experience')
                                    ->label('Years Experience')
                                    ->numeric()
                                    ->minValue(0),
                                FileUpload::make('president.image')
                                    ->label('President image (shared)')
                                    ->disk('public')
                                    ->directory('about/president')
                                    ->visibility('public')
                                    ->image()
                                    ->columnSpanFull(),
                            ])->columns(2),
                            Tabs::make($key.'_president_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('president.locales.'.$locale)
                                        ->schema($this->presidentLocaleFields())
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
                        ])
                        ->columns(1),
                    Section::make('Video')
                        ->description('Video URL for the About page (per language).')
                        ->schema([
                            FileUpload::make('video_background_image')
                                ->label('Background image (shared)')
                                ->disk('public')
                                ->directory('about/video')
                                ->visibility('public')
                                ->image()
                                ->columnSpanFull(),
                            Tabs::make($key.'_video_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('video.'.$locale)
                                        ->schema($this->videoFields())
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes([
                                    'style' => 'background-color: #fff7ef',
                                ]),
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
    protected function coverFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->maxLength(65535),
            FileUpload::make('background_image')
                ->label('Background image')
                ->disk('public')
                ->directory('about/cover')
                ->visibility('public')
                ->image()
                ->columnSpanFull(),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function bodyFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Section title')
                ->maxLength(65535),
            Textarea::make('text')
                ->label('Text')
                ->rows(10)
                ->columnSpanFull(),
            FileUpload::make('image')
                ->label('Main image')
                ->disk('public')
                ->directory('about/body')
                ->visibility('public')
                ->image()
                ->columnSpanFull(),
            TextInput::make('video_url')
                ->label('Video URL (optional)')
                ->maxLength(2048)
                ->placeholder('https://www.youtube.com/watch?v=...'),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function aboutSectionFields(): array
    {
        return [
            Textarea::make('teaser')
                ->label('Teaser')
                ->rows(4)
                ->columnSpanFull(),
            RichEditor::make('description')
                ->label('Description')
                ->columnSpanFull(),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function aboutImagesFields(): array
    {
        $key = Page::KEY_ABOUT_PAGE;

        return [
            FileUpload::make('about_images.image_left')
                ->label('Left image (shared)')
                ->disk('public')
                ->directory('about/section')
                ->visibility('public')
                ->image()
                ->columnSpanFull(),
            FileUpload::make('about_images.image_right')
                ->label('Right image (shared)')
                ->disk('public')
                ->directory('about/section')
                ->visibility('public')
                ->image()
                ->columnSpanFull(),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function funfactsFields(): array
    {
        return [
            TextInput::make('label_1')
                ->label('Label 1')
                ->maxLength(255),
            TextInput::make('value_1')
                ->label('Value 1')
                ->numeric()
                ->minValue(0),
            TextInput::make('label_2')
                ->label('Label 2')
                ->maxLength(255),
            TextInput::make('value_2')
                ->label('Value 2')
                ->numeric()
                ->minValue(0),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function presidentLocaleFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->maxLength(65535),
            Repeater::make('items')
                ->label('Accordion items')
                ->minItems(1)
                ->defaultItems(3)
                ->schema([
                    TextInput::make('title')
                        ->label('Item title')
                        ->maxLength(65535),
                    Textarea::make('content')
                        ->label('Item content')
                        ->rows(5)
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function videoFields(): array
    {
        return [
            TextInput::make('url')
                ->label('Video URL')
                ->maxLength(2048)
                ->placeholder('https://www.youtube.com/watch?v=...'),
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

