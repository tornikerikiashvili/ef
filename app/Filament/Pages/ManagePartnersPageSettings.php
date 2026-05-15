<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\ProvidesPageSeoFormComponents;
use App\Models\Page;
use App\Models\PartnerLogo;
use BackedEnum;
use Filament\Actions\Action;
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

class ManagePartnersPageSettings extends FilamentPage
{
    use ProvidesPageSeoFormComponents;

    protected static string|UnitEnum|null $navigationGroup = 'Pages';

    protected static ?string $title = 'Partners';

    protected static ?string $navigationLabel = 'Partners';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?int $navigationSort = 55;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $key = Page::KEY_PARTNERS_PAGE;

        Page::ensurePartnersPageBlock();

        $row = Page::query()->where('key', $key)->first();

        $this->form->fill([
            $key => array_replace_recursive(
                Page::defaultPartnersPagePayload(),
                is_array($row?->payload) ? $row->payload : []
            ),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $key = Page::KEY_PARTNERS_PAGE;
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
        $defaults = Page::defaultPartnersPagePayload();
        $merged = array_replace_recursive($defaults, $payload);

        $cover = $merged['cover_image'] ?? null;
        if (is_array($cover)) {
            $cover = $cover[0] ?? null;
        }
        $merged['cover_image'] = filled($cover) ? (string) $cover : null;

        $merged['locales'] = is_array($merged['locales'] ?? null) ? $merged['locales'] : [];
        foreach (array_keys($defaults['locales'] ?? []) as $locale) {
            if (! is_string($locale)) {
                continue;
            }
            $row = is_array($merged['locales'][$locale] ?? null) ? $merged['locales'][$locale] : [];
            $merged['locales'][$locale] = [
                'menu_title' => isset($row['menu_title']) ? (string) $row['menu_title'] : '',
                'title' => isset($row['title']) ? (string) $row['title'] : '',
            ];
        }

        $partnerLogoIds = $merged['partner_logo_ids'] ?? [];
        $outPartnerIds = [];
        foreach (is_array($partnerLogoIds) ? $partnerLogoIds : [] as $id) {
            if ($id === null || $id === '') {
                continue;
            }
            $outPartnerIds[] = (int) $id;
        }
        $merged['partner_logo_ids'] = array_values(array_unique($outPartnerIds));

        return Page::normalizeSeoInPagePayload($merged);
    }

    public function form(Schema $schema): Schema
    {
        $key = Page::KEY_PARTNERS_PAGE;
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return $this->defaultForm($schema)
            ->components([
                Group::make([
                    Section::make('Header')
                        ->description('Menu title + page title (per language) and shared cover image.')
                        ->schema([
                            Tabs::make($key.'_header_locales')
                                ->tabs(
                                    collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                                        ->statePath('locales.'.$locale)
                                        ->schema([
                                            TextInput::make('menu_title')
                                                ->label('Menu title')
                                                ->maxLength(255),
                                            TextInput::make('title')
                                                ->label('Page title')
                                                ->maxLength(65535),
                                        ])
                                    )->all()
                                )
                                ->columns(1)
                                ->extraAttributes(['style' => 'background-color: #fff7ef']),
                            FileUpload::make('cover_image')
                                ->label('Cover image (shared)')
                                ->disk('public')
                                ->directory('partners/cover')
                                ->visibility('public')
                                ->image()
                                ->columnSpanFull(),
                        ])
                        ->columns(1),
                    Section::make('Partner logos')
                        ->description('Choose which logos appear on the page. Display order follows the order you select them.')
                        ->schema([
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
                    $this->pageSeoSectionBlock($key),
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
