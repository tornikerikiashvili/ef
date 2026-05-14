<?php

namespace App\Filament\Pages;

use App\Models\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
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

class ManageLegalPagesSettings extends FilamentPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Site configuration';

    protected static ?string $title = 'Legal pages';

    protected static ?string $navigationLabel = 'Legal pages';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?int $navigationSort = 10;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $key = Page::KEY_LEGAL_PAGES;

        Page::ensureLegalPagesBlock();

        $row = Page::query()->where('key', $key)->first();

        $this->form->fill([
            $key => array_replace_recursive(
                Page::defaultLegalPagesPayload(),
                is_array($row?->payload) ? $row->payload : []
            ),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $key = Page::KEY_LEGAL_PAGES;
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
        $defaults = Page::defaultLegalPagesPayload();
        $merged = array_replace_recursive($defaults, $payload);

        $merged['locales'] = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        foreach (array_keys($defaults['locales'] ?? []) as $locale) {
            if (! is_string($locale)) {
                continue;
            }
            $row = is_array($merged['locales'][$locale] ?? null) ? $merged['locales'][$locale] : [];
            $merged['locales'][$locale] = [
                'terms' => isset($row['terms']) ? (string) $row['terms'] : '',
                'privacy' => isset($row['privacy']) ? (string) $row['privacy'] : '',
                'cookies' => isset($row['cookies']) ? (string) $row['cookies'] : '',
            ];
        }

        return $merged;
    }

    public function form(Schema $schema): Schema
    {
        $key = Page::KEY_LEGAL_PAGES;
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return $this->defaultForm($schema)
            ->components([
                Group::make([
                    Section::make('Policy content')
                        ->description('Terms and conditions, privacy policy, and cookie policy (per language). Shown on the public legal routes linked from the footer.')
                        ->schema([
                            Tabs::make($key.'_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('locales.'.$locale)
                                        ->schema([
                                            RichEditor::make('terms')
                                                ->label('Terms and conditions')
                                                ->columnSpanFull(),
                                            RichEditor::make('privacy')
                                                ->label('Privacy policy')
                                                ->columnSpanFull(),
                                            RichEditor::make('cookies')
                                                ->label('Cookie policy')
                                                ->columnSpanFull(),
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
