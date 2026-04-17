<?php

namespace App\Filament\Pages;

use App\Models\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
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
    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Contact page';

    protected static ?string $navigationLabel = 'Contact page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?int $navigationSort = 11;

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

        foreach (array_keys($defaults) as $locale) {
            $row = is_array($merged[$locale] ?? null) ? $merged[$locale] : [];
            $merged[$locale] = [
                'intro' => isset($row['intro']) ? (string) $row['intro'] : '',
                'email' => isset($row['email']) ? (string) $row['email'] : '',
                'phone' => isset($row['phone']) ? (string) $row['phone'] : '',
                'address' => isset($row['address']) ? (string) $row['address'] : '',
            ];
        }

        return $merged;
    }

    public function form(Schema $schema): Schema
    {
        return $this->defaultForm($schema)
            ->components([
                Section::make('Contact page copy')
                    ->description('Intro and contact details (per language). Shown on the public contact page.')
                    ->schema($this->localeTabsForContactBlock())
                    ->columns(1),
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
            Textarea::make('intro')
                ->label('Intro')
                ->rows(5)
                ->columnSpanFull(),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->maxLength(255),
            TextInput::make('phone')
                ->label('Phone')
                ->maxLength(255),
            TextInput::make('address')
                ->label('Address')
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
