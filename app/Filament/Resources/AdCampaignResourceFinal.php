<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdCampaignResource\Pages;
use App\Models\AdCampaign;
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
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Enums\CampaignType;
use App\Models\User;

class AdCampaignResourceFinal extends Resource
{
    protected static ?string $model = AdCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';

    protected static ?string $navigationGroup = 'Reklam Yönetimi';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('📋 Kampanya Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Kampanya Adı')
                                    ->required()
                                    ->placeholder('Örn: Yaz İndirimi Kampanyası')
                                    ->maxLength(255)
                                    ->columnSpan(2),

                                Select::make('campaign_type')
                                    ->label('Kampanya Türü')
                                    ->options([
                                        'user' => '👤 Kullanıcı Kampanyası',
                                    ])
                                    ->required()
                                    ->default('user'),

                                TextInput::make('daily_budget')
                                    ->label('Günlük Bütçe')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->default(25)
                                    ->helperText('Günlük harcanacak maksimum tutar'),

                                Select::make('bidding_strategy')
                                    ->label('Teklif Stratejisi')
                                    ->options([
                                        'cpc' => '💰 Tıklama Başına Maliyet (CPC)',
                                        'cpm' => '📊 Bin Gösterim Başına Maliyet (CPM)',
                                        'auto' => '🤖 Otomatik Teklif',
                                    ])
                                    ->required()
                                    ->default('cpc'),
                            ]),

                        Toggle::make('is_active')
                            ->label('✅ Kampanyayı hemen aktif et')
                            ->default(true),
                    ])
                    ->columns(1),

                Section::make('🌍 Hedef Kitle Seçimi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('targeting_countries')
                                    ->label('🎯 Hedef Ülkeler')
                                    ->multiple()
                                    ->searchable()
                                    ->options(\App\Models\Country::whereNotNull('name')->orderBy('name')->pluck('name', 'iso_code'))
                                    ->preload()
                                    ->helperText('Hangi ülkelerdeki kullanıcıları hedefleyeceksiniz?')
                                    ->columnSpan(2),

                                CheckboxList::make('targeting_devices')
                                    ->label('📱 Hedef Cihazlar')
                                    ->options([
                                        'Desktop' => '💻 Masaüstü Bilgisayar',
                                        'Mobile' => '📱 Mobil Telefon',
                                        'Tablet' => '📟 Tablet',
                                    ])
                                    ->columns(3),

                                CheckboxList::make('targeting_ages')
                                    ->label('👥 Hedef Yaş Grupları')
                                    ->options([
                                        '18-24' => '18-24 yaş',
                                        '25-34' => '25-34 yaş',
                                        '35-44' => '35-44 yaş',
                                        '45-54' => '45-54 yaş',
                                        '55+' => '55+ yaş',
                                    ])
                                    ->columns(3),
                            ]),
                    ])
                    ->columns(1),

                Section::make('🎨 Reklam Yapılandırması')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Radio::make('step_type')
                                    ->label('📋 Adım Türü')
                                    ->options([
                                        'interstitial' => '🔄 Geçiş Reklamı (Sayfa arasında tam ekran)',
                                        'banner_page' => '📊 Banner Sayfası (Banner reklamları içeren sayfa)',
                                    ])
                                    ->required()
                                    ->default('interstitial')
                                    ->columnSpan(2),

                                TextInput::make('wait_time')
                                    ->label('⏱️ Bekleme Süresi')
                                    ->numeric()
                                    ->default(5)
                                    ->suffix('saniye')
                                    ->helperText('Kullanıcı reklamı kaç saniye görsün?'),

                                Toggle::make('show_popup')
                                    ->label('🔥 Pop-up reklam da gösterilsin mi?'),
                            ]),

                        Section::make('📊 Banner Reklamı (Banner sayfası için)')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('banner_url')
                                            ->label('🔗 Hedef URL')
                                            ->url()
                                            ->required()
                                            ->placeholder('https://example.com'),

                                        Select::make('banner_size')
                                            ->label('📏 Banner Boyutu')
                                            ->options([
                                                '728x90' => '📏 728×90 (Leaderboard)',
                                                '300x250' => '🖼️ 300×250 (Medium Rectangle)',
                                                '320x50' => '📱 320×50 (Mobile Banner)',
                                                '160x600' => '🏢 160×600 (Wide Skyscraper)',
                                            ])
                                            ->required()
                                            ->default('728x90'),
                                    ]),

                                Toggle::make('banner_responsive')
                                    ->label('📱 Responsive tasarım olsun mu?')
                                    ->default(true),
                            ])
                            ->columns(1)
                            ->visible(fn (callable $get) => $get('step_type') === 'banner_page'),

                        Section::make('🔥 Pop-up Reklamı')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('popup_title')
                                            ->label('📝 Pop-up Başlığı')
                                            ->required()
                                            ->placeholder('Özel Teklif'),

                                        TextInput::make('popup_url')
                                            ->label('🔗 Hedef URL')
                                            ->url()
                                            ->required()
                                            ->placeholder('https://example.com'),
                                    ]),

                                Textarea::make('popup_content')
                                    ->label('📄 Pop-up İçeriği')
                                    ->rows(3)
                                    ->required()
                                    ->placeholder('Bu özel teklif hakkında daha fazla bilgi alın...'),

                                Select::make('popup_size')
                                    ->label('📐 Pop-up Boyutu')
                                    ->options([
                                        'small' => 'Küçük (400x300)',
                                        'medium' => 'Orta (600x400)',
                                        'large' => 'Büyük (800x600)',
                                    ])
                                    ->default('medium'),
                            ])
                            ->columns(1)
                            ->visible(fn (callable $get) => $get('show_popup')),
                    ])
                    ->columns(1),

                Section::make('📈 Performans Tahminleri')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Placeholder::make('estimated_reach')
                                    ->label('🎯 Tahmini Erişim')
                                    ->content('25,000 - 50,000 kişi'),

                                Placeholder::make('estimated_ctr')
                                    ->label('📊 Tahmini Tıklama Oranı')
                                    ->content('2.1% - 3.5%'),

                                Placeholder::make('estimated_conversions')
                                    ->label('✅ Tahmini Dönüşüm')
                                    ->content('500 - 1,000 dönüşüm'),

                                Placeholder::make('estimated_cost')
                                    ->label('💰 Tahmini Maliyet')
                                    ->content(fn (callable $get) => '$' . ($get('daily_budget') ?? 0) . '/gün'),
                            ]),
                    ])
                    ->columns(1)
                    ->visible(fn (callable $get) => $get('daily_budget') > 0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kampanya Adı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('campaign_type')
                    ->label('Tür')
                    ->badge()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('total_impressions')
                    ->label('Gösterim')
                    ->sortable(),

                TextColumn::make('total_clicks')
                    ->label('Tıklanma')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->placeholder('Admin'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('campaign_type')
                    ->options(CampaignType::class),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdCampaigns::route('/'),
            'create' => Pages\CreateAdCampaign::route('/create'),
            'edit' => Pages\EditAdCampaign::route('/{record}/edit'),
        ];
    }
}