<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignTemplateResource\Pages;
use App\Models\CampaignTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Enums\StepType;
use App\Enums\AdType;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use App\Models\Country;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model; // Import Model for default closure

class CampaignTemplateResource extends Resource
{
    protected static ?string $model = CampaignTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Reklam Yönetimi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Şablon Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Şablon Adı')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Reklam şablonunuz için açıklayıcı bir isim girin.'),

                        TextInput::make('slug')
                            ->label('Şablon Kısa Adı')
                            ->unique()
                            ->required()
                            ->helperText('URL ve kodlarda kullanılacak benzersiz tanımlayıcı'),

                        Select::make('category')
                            ->label('Şablon Kategorisi')
                            ->options([
                                'quick_start' => 'Hızlı Başlat',
                                'brand_awareness' => 'Marka Bilinirliği',
                                'lead_generation' => 'Potansiyel Müşteri Kazanımı',
                                'traffic_drive' => 'Trafik Artırma',
                                'custom' => 'Özel Şablon',
                            ])
                            ->required()
                            ->live()
                            ->helperText('Şablonun hangi kategoriye ait olduğunu seçin.'),

                        Textarea::make('description')
                            ->label('Şablon Açıklaması')
                            ->rows(3)
                            ->maxLength(1000)
                            ->helperText('Şablonun ne işe yaradığını açıklayan kısa bir metin.'),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Şablonun kullanıcılar ve kampanyalar tarafından kullanılabilir olup olmadığını belirler.'),

                        TextInput::make('sort_order')
                            ->label('Sıralama Düzeni')
                            ->numeric()
                            ->default(0)
                            ->helperText('Şablonların listelenme sırasını belirler (küçük sayılar önce gösterilir).'),
                    ])
                    ->columns(2),

                Section::make('🌍 Hedef Kitle')
                    ->schema([
                        Select::make('targeting_countries')
                            ->label('🎯 Hedef Ülkeler')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                $countries = Country::whereNotNull('name')->orderBy('name')->pluck('name', 'iso_code');
                                return $countries->prepend('Tüm Ülkeler', 'ALL');
                            })
                            ->preload()
                            ->helperText('Hangi ülkelerdeki kullanıcıları hedefleyeceğinizi seçin. "Tüm Ülkeler" seçeneği ile global hedefleme yapabilirsiniz.'),

                        CheckboxList::make('targeting_devices')
                            ->label('📱 Hedef Cihazlar')
                            ->options([
                                'Desktop' => '💻 Masaüstü',
                                'Mobile' => '📱 Mobil',
                                'Tablet' => '📟 Tablet',
                            ])
                            ->columns(3)
                            ->helperText('Reklamlarınızın hangi cihaz türlerinde gösterileceğini seçin.'),

                        CheckboxList::make('targeting_os')
                            ->label('⚙️ Hedef İşletim Sistemleri')
                            ->options([
                                'iOS' => '🍎 iOS',
                                'Android' => '🤖 Android',
                                'Windows' => '🪟 Windows',
                                'macOS' => '💻 macOS',
                                'Linux' => '🐧 Linux',
                                'Other' => 'Diğer',
                            ])
                            ->columns(3)
                            ->helperText('Reklamlarınızın hangi işletim sistemlerinde gösterileceğini seçin.'),

                        CheckboxList::make('targeting_ages')
                            ->label('👥 Hedef Yaş Grupları')
                            ->options([
                                '18-24' => '18-24 yaş',
                                '25-34' => '25-34 yaş',
                                '35-44' => '35-44 yaş',
                                '45-54' => '45-54 yaş',
                                '55+' => '55+ yaş',
                            ])
                            ->columns(3)
                            ->helperText('Reklamlarınızın hangi yaş gruplarına gösterileceğini seçin.'),
                    ])
                    ->columns(2),

                Section::make('⏰ Zamanlama ve Limitler')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Başlangıç Tarihi')
                            ->nullable()
                            ->helperText('Şablonu kullanan kampanyaların ne zaman başlayacağını belirleyin.'),
                        DatePicker::make('end_date')
                            ->label('Bitiş Tarihi')
                            ->nullable()
                            ->helperText('Şablonu kullanan kampanyaların ne zaman sona ereceğini belirleyin. Boş bırakılırsa bakiye bitene kadar devam eder.'),
                        TextInput::make('daily_click_limit')
                            ->label('Günlük Tıklama Limiti')
                            ->numeric()
                            ->nullable()
                            ->helperText('Şablonu kullanan kampanyaların bir günde alabileceği maksimum tıklama sayısını belirleyin.'),
                        Forms\Components\Group::make()
                            ->schema([
                                TextInput::make('frequency_cap')
                                    ->label('Sıklık Sınırı')
                                    ->numeric()
                                    ->nullable()
                                    ->helperText('Bir kullanıcının reklamı ne sıklıkla görebileceğini sınırlayın.'),
                                Select::make('frequency_cap_unit')
                                    ->label('Birim')
                                    ->options([
                                        'hour' => 'Saat',
                                        'day' => 'Gün',
                                        'week' => 'Hafta',
                                        'month' => 'Ay',
                                    ])
                                    ->nullable()
                                    ->helperText('Sıklık sınırı için zaman birimini seçin.'),
                            ])
                            ->columns(2),
                        
                        // Campaign Schedule (Görseldeki gibi)
                        Forms\Components\Fieldset::make('Kampanya Takvimi')
                            ->schema([
                                Forms\Components\Placeholder::make('schedule_info')
                                    ->content('Reklamlarınızın haftanın hangi günleri ve günün hangi saatlerinde gösterileceğini seçin. UTC+3 zaman dilimine göre gösterilir.'),
                                Repeater::make('campaign_schedule')
                                    ->label('')
                                    ->schema([
                                        Hidden::make('day_of_week'), // Gün adını tutmak için
                                        CheckboxList::make('hours')
                                            ->label(fn (array $state): string => match ($state['day_of_week'] ?? null) { // Safely access day_of_week
                                                'Mon' => 'Pazartesi',
                                                'Tue' => 'Salı',
                                                'Wed' => 'Çarşamba',
                                                'Thu' => 'Perşembe',
                                                'Fri' => 'Cuma',
                                                'Sat' => 'Cumartesi',
                                                'Sun' => 'Pazar',
                                                default => 'Gün Seçin'
                                            })
                                            ->options(function () {
                                                return collect(range(0, 23))->mapWithKeys(fn ($hour) => [sprintf('%02d', $hour) => sprintf('%02d', $hour)]);
                                            })
                                            ->columns(8)
                                            ->gridDirection('row')
                                            ->afterStateHydrated(function (Forms\Components\CheckboxList $component, Get $get, Set $set) {
                                                // Eğer schedule boşsa varsayılan olarak tüm saatleri seç
                                                if (empty($get('hours'))) {
                                                    $set('hours', collect(range(0, 23))->map(fn ($hour) => sprintf('%02d', $hour))->toArray());
                                                }
                                            }),
                                    ])
                                    ->defaultItems(7) // Haftanın 7 günü için varsayılan
                                    ->minItems(7)
                                    ->maxItems(7)
                                    ->disableItemCreation()
                                    ->disableItemDeletion()
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        // İlk oluşturmada gün adlarını otomatik doldur
                                        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                        static $dayIndex = 0;
                                        $data['day_of_week'] = $days[$dayIndex % 7];
                                        $dayIndex++;
                                        return $data;
                                    })
                                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                                        // Mevcut veriyi doldururken gün adını kullan
                                        if (!isset($data['day_of_week'])) {
                                            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                            static $fillIndex = 0;
                                            $data['day_of_week'] = $days[$fillIndex % 7];
                                            $fillIndex++;
                                        }
                                        return $data;
                                    })
                                    ->itemLabel(fn (array $state): ?string => match ($state['day_of_week']) {
                                        'Mon' => 'Pazartesi',
                                        'Tue' => 'Salı',
                                        'Wed' => 'Çarşamba',
                                        'Thu' => 'Perşembe',
                                        'Fri' => 'Cuma',
                                        'Sat' => 'Cumartesi',
                                        'Sun' => 'Pazar',
                                        default => 'Gün'
                                    }),
                            ])
                            ->columns(1),
                    ])
                    ->columns(2),

                Section::make('Reklam Adımları ve İçerikleri')
                    ->schema([
                        Repeater::make('campaignTemplateSteps')
                            ->relationship('campaignTemplateSteps')
                            ->label('Adımlar')
                            ->schema([
                                TextInput::make('step_number')
                                    ->label('Adım Sırası')
                                    ->numeric()
                                    ->required()
                                    ->default(function (Forms\Get $get, ?Model $record) {
                                        // For new items, count existing related steps
                                        if ($record && $record->campaignTemplateSteps) {
                                            return $record->campaignTemplateSteps->count() + 1;
                                        }
                                        // Fallback for initial creation or if no record
                                        return 1;
                                    })
                                    ->live() // Make it live to trigger re-render on update
                                    ->disabled(),
                                
                                Select::make('step_type')
                                    ->label('Adım Türü')
                                    ->options(StepType::class)
                                    ->required()
                                    ->live()
                                    ->helperText('Reklam adımının türünü seçin (örn: Geçiş Reklamı, Banner Sayfası).'),

                                TextInput::make('wait_time')
                                    ->label('Bekleme Süresi (Saniye)')
                                    ->numeric()
                                    ->default(5)
                                    ->suffix('saniye')
                                    ->helperText('Reklam gösterilmeden önce beklenecek süreyi belirleyin.'),

                                Toggle::make('show_popup')
                                    ->label('Bu Adımda Pop-up Gösterilsin mi?')
                                    ->default(false)
                                    ->helperText('Bu adımda ek bir pop-up reklam gösterilip gösterilmeyeceğini belirler.'),

                                Toggle::make('show_linked_popup')
                                    ->label('Bağlı Pop-up Kampanyası Gösterilsin mi?')
                                    ->default(false)
                                    ->helperText('Bu adımda mevcut bir pop-up reklam kampanyasının rastgele seçilip gösterilip gösterilmeyeceğini belirler.'),

                                // popup_ad_campaign_id alanı kaldırıldı, çünkü seçim LinkController'da rastgele yapılacak.

                                Repeater::make('campaignTemplateAds')
                                    ->relationship('campaignTemplateAds')
                                    ->label('Reklam İçerikleri')
                                    ->schema([
                                        Select::make('ad_type')
                                            ->label('Reklam Türü')
                                            ->options(AdType::class)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set) {
                                                $set('ad_data', []); // Reklam türü değiştiğinde ad_data'yı sıfırla
                                            })
                                            ->helperText('Bu adımda gösterilecek reklamın türünü seçin. Seçiminize göre aşağıdaki "Reklam Detayları" alanı değişecektir.'),

                                        Fieldset::make('Reklam Detayları')
                                            ->schema(fn (Get $get): array => match ($get('ad_type')) {
                                                AdType::Banner->value => [
                                                    Select::make('ad_data.size')
                                                        ->label('Banner Boyutu')
                                                        ->options([
                                                            '728x90' => '728×90 (Leaderboard)',
                                                            '300x250' => '300×250 (Medium Rectangle)',
                                                            '320x50' => '320×50 (Mobile Banner)',
                                                            '160x600' => '160×600 (Wide Skyscraper)',
                                                        ])
                                                        ->required()
                                                        ->helperText('Banner reklamının boyutunu seçin.'),
                                                    Forms\Components\FileUpload::make('ad_data.image')
                                                        ->label('Banner Görseli')
                                                        ->image()
                                                        ->directory('campaign-banners')
                                                        ->maxSize(2048) // 2MB
                                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'])
                                                        ->nullable()
                                                        ->helperText('Banner görselini yükleyin. Desteklenen formatlar: JPG, PNG, GIF, SVG, WEBP (Max: 2MB).'),
                                                    TextInput::make('ad_data.url')
                                                        ->label('Hedef URL')
                                                        ->url()
                                                        ->nullable()
                                                        ->required(fn (Get $get): bool => !filled($get('ad_data.custom_js')) && !filled($get('ad_data.image')))
                                                        ->helperText('Banner tıklandığında gidilecek URL.'),
                                                    Textarea::make('ad_data.custom_js')
                                                        ->label('Özel JavaScript Kodu')
                                                        ->rows(6)
                                                        ->nullable()
                                                        ->extraAttributes(['class' => 'font-mono text-sm'])
                                                        ->required(fn (Get $get): bool => !filled($get('ad_data.url')) && !filled($get('ad_data.image')))
                                                        ->helperText('Banner için özel JavaScript kodu ekleyebilirsiniz. URL veya görsel yerine bu kod çalıştırılır.'),
                                                ],
                                                AdType::Popup->value => [
                                                    TextInput::make('ad_data.title')
                                                        ->label('Başlık')
                                                        ->required()
                                                        ->helperText('Pop-up penceresinin başlığı.'),
                                                    TextInput::make('ad_data.url')
                                                        ->label('Hedef URL')
                                                        ->url()
                                                        ->required()
                                                        ->helperText('Pop-up tıklandığında gidilecek URL.'),
                                                    Textarea::make('ad_data.content')
                                                        ->label('İçerik')
                                                        ->rows(3)
                                                        ->required()
                                                        ->helperText('Pop-up penceresinde gösterilecek metin içeriği.'),
                                                    Textarea::make('ad_data.custom_js') // Custom JS for pop-ups
                                                        ->label('Özel JavaScript Kodu')
                                                        ->rows(6)
                                                        ->nullable()
                                                        ->extraAttributes(['class' => 'font-mono text-sm'])
                                                        ->helperText('Pop-up için özel JavaScript kodu ekleyebilirsiniz.'),
                                                ],
                                                AdType::Html->value => [
                                                    Textarea::make('ad_data.content')
                                                        ->label('HTML Kodu')
                                                        ->rows(6)
                                                        ->required()
                                                        ->extraAttributes(['class' => 'font-mono text-sm'])
                                                        ->helperText('Gösterilecek özel HTML reklam kodunu girin.'),
                                                ],
                                                AdType::ThirdParty->value => [
                                                    Textarea::make('ad_data.code')
                                                        ->label('Üçüncü Parti Reklam Kodu (HTML/JS)')
                                                        ->rows(6)
                                                        ->required()
                                                        ->extraAttributes(['class' => 'font-mono text-sm'])
                                                        ->helperText('Google Ads, Facebook Pixel gibi üçüncü parti reklam kodlarını buraya yapıştırın.'),
                                                ],
                                                default => [
                                                    Placeholder::make('no_ad_type_selected')
                                                        ->content(new HtmlString('<p class="text-sm text-gray-500">Lütfen bir reklam türü seçin.</p>')),
                                                ],
                                            })
                                            ->columns(2)
                                            ->visible(fn (Get $get) => filled($get('ad_type'))),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(1)
                                    ->minItems(1)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => AdType::tryFrom($state['ad_type'])?->getLabel() ?? null),
                            ])
                            ->columns(1)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 'Adım ' . ($state['step_number'] ?? '')) // Re-introduced, relying on live step_number
                            ->orderColumn('step_number')
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get, ?Model $record): array {
                                // Explicitly set step_number on creation to ensure correct sequencing
                                if ($record && $record->campaignTemplateSteps) {
                                    $data['step_number'] = $record->campaignTemplateSteps->count() + 1;
                                } else {
                                    $data['step_number'] = 1; // Fallback for initial creation
                                }
                                return $data;
                            }),
                    ]),

                Section::make('📊 Trafik Bilgileri')
                    ->schema([
                        TextInput::make('estimated_traffic')
                            ->label('Tahmini Trafik')
                            ->numeric()
                            ->disabled()
                            ->default(0)
                            ->helperText('Seçilen hedeflemeye göre kampanyanızın alabileceği tahmini toplam trafik.'),
                        TextInput::make('available_traffic')
                            ->label('Mevcut Trafik')
                            ->numeric()
                            ->disabled()
                            ->default(0)
                            ->helperText('Seçilen hedefleme kriterlerine uygun mevcut toplam trafik.'),
                    ])
                    ->columns(2),

                Section::make('Bütçe ve Performans')
                    ->schema([
                        TextInput::make('default_budget')
                            ->label('Varsayılan Günlük Bütçe')
                            ->numeric()
                            ->prefix('$')
                            ->default(100.00)
                            ->helperText('Şablonu kullanan kampanyalar için varsayılan günlük bütçe.'),

                        TextInput::make('estimated_ctr')
                            ->label('Tahmini Tıklama Oranı (CTR)')
                            ->numeric()
                            ->suffix('%')
                            ->default(2.0)
                            ->helperText('Şablonu kullanan kampanyalar için tahmini tıklama oranı.'),

                        TextInput::make('estimated_cpc')
                            ->label('Tahmini Tıklama Başına Maliyet (CPC)')
                            ->numeric()
                            ->prefix('$')
                            ->default(1.00)
                            ->helperText('Şablonu kullanan kampanyalar için tahmini tıklama başına maliyet.'),

                        TextInput::make('estimated_reach')
                            ->label('Tahmini Erişim')
                            ->numeric()
                            ->default(100000)
                            ->helperText('Şablonu kullanan kampanyalar için tahmini erişim sayısı.'),

                        TextInput::make('estimated_conversions')
                            ->label('Tahmini Dönüşümler')
                            ->numeric()
                            ->default(2000)
                            ->helperText('Şablonu kullanan kampanyalar için tahmini dönüşüm sayısı.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Şablon Adı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string =>
                        match($state) {
                            'quick_start' => 'Hızlı Başlat',
                            'brand_awareness' => 'Marka Bilinirliği',
                            'lead_generation' => 'Potansiyel Müşteri',
                            'traffic_drive' => 'Trafik Artırma',
                            'custom' => 'Özel',
                            default => $state
                        }
                    )
                    ->color(fn (string $state): string =>
                        match($state) {
                            'quick_start' => 'success',
                            'brand_awareness' => 'info',
                            'lead_generation' => 'warning',
                            'traffic_drive' => 'danger',
                            'custom' => 'gray',
                            default => 'gray'
                        }
                    ),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('campaignTemplateSteps.count')
                    ->label('Adım Sayısı')
                    ->counts('campaignTemplateSteps')
                    ->badge()
                    ->color('success'),

                TextColumn::make('default_budget')
                    ->label('Varsayılan Bütçe')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'quick_start' => 'Hızlı Başlat',
                        'brand_awareness' => 'Marka Bilinirliği',
                        'lead_generation' => 'Potansiyel Müşteri Kazanımı',
                        'traffic_drive' => 'Trafik Artırma',
                        'custom' => 'Özel Şablon',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif Şablonlar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->label('Şablonu Kopyala'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\CampaignTemplateStepsRelationManager::class, // Will create this later
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaignTemplates::route('/'),
            'create' => Pages\CreateCampaignTemplate::route('/create'),
            'edit' => Pages\EditCampaignTemplate::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // Tablo henüz oluşturulmadıysa badge gösterme
        if (!\Schema::hasTable('campaign_templates')) {
            return null;
        }

        try {
            return static::getModel()::where('is_active', true)->count();
        } catch (\Exception $e) {
            return null;
        }
    }
}
