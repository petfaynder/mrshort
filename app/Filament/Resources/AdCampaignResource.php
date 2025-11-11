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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Enums\CampaignType;
use App\Models\User;
use App\Models\CampaignTemplate;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use App\Models\Country;
use Filament\Forms\Components\KeyValue; // Add this import
use Filament\Forms\Components\ToggleButtons; // Add this import
use Filament\Forms\Components\TimePicker; // Add this import
use Filament\Forms\Components\Repeater; // Add this import
use Filament\Forms\Components\Grid; // Add this import
use Filament\Forms\Components\Hidden; // Add this import
use Filament\Forms\Get; // Add this import
use Filament\Forms\Set; // Add this import
use Illuminate\Support\Collection; // Add this import

class AdCampaignResource extends Resource
{
    protected static ?string $model = AdCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-m-cursor-arrow-rays';

    protected static ?string $navigationGroup = 'Reklam Yönetimi';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('📋 Temel Bilgiler')
                    ->schema([
                        TextInput::make('name')
                            ->label('Kampanya Adı')
                            ->required()
                            ->placeholder('Örn: Yaz İndirimi Kampanyası')
                            ->maxLength(255)
                            ->helperText('Reklam kampanyanız için açıklayıcı bir isim girin.'),

                        Select::make('campaign_type')
                            ->label('Kampanya Türü')
                            ->options(CampaignType::class)
                            ->required()
                            ->default('user')
                            ->helperText('Kampanyanın türünü seçin (örn: Kullanıcı veya Yönetici).'),

                        TextInput::make('budget')
                            ->label('Toplam Bütçe')
                            ->numeric()
                            ->prefix('$')
                            ->nullable()
                            ->default(0)
                            ->helperText('Kampanyanız için toplam harcama limitini belirleyin. "Bakiye Bitene Kadar Devam Et" seçeneği aktifse bu bütçe kullanılır.'),
                        
                        TextInput::make('daily_budget')
                            ->label('Günlük Bütçe')
                            ->numeric()
                            ->prefix('$')
                            ->nullable()
                            ->default(0)
                            ->helperText('Kampanyanız için günlük harcama limitini belirleyin. 0 bırakılırsa günlük limit uygulanmaz.'),

                        Select::make('bidding_strategy')
                            ->label('Teklif Stratejisi')
                            ->options([
                                'cpc' => 'Tıklama Başına Maliyet (CPC)',
                                'cpm' => 'Bin Gösterim Başına Maliyet (CPM)',
                                'auto' => 'Otomatik Teklif',
                            ])
                            ->required()
                            ->default('cpc')
                            ->helperText('Reklamlarınızın nasıl ücretlendirileceğini seçin.'),

                        Toggle::make('is_active')
                            ->label('Kampanyayı hemen aktif et')
                            ->default(true)
                            ->helperText('Kampanyanın oluşturulduktan sonra hemen yayına girip girmeyeceğini belirler.'),
                    ])
                    ->columns(2),

                Section::make('📋 Kampanya Şablonu')
                    ->schema([
                        Select::make('campaign_template_id')
                            ->label('Şablon Seçin')
                            ->options(CampaignTemplate::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Bu kampanya için bir şablon seçin. Şablon seçimi, reklam adımlarını ve içeriklerini otomatik olarak dolduracaktır.'),
                    ]),

                Section::make('🌍 Hedef Kitle')
                    ->schema([
                        Select::make('targeting_countries')
                            ->label('🎯 Hedef Ülkeler')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                $countries = Country::whereNotNull('name')->orderBy('name')->pluck('name', 'iso_code');
                                return $countries->prepend('Tüm Ülkeler', 'ALL'); // "Tüm Ülkeler" seçeneğini ekle
                            })
                            ->preload()
                            ->helperText('Hangi ülkelerdeki kullanıcıları hedefleyeceksiniz? "Tüm Ülkeler" seçeneği ile global hedefleme yapabilirsiniz.'),
                        
                        CheckboxList::make('targeting_devices')
                            ->label('📱 Hedef Cihazlar')
                            ->options([
                                'desktop' => '💻 Masaüstü',
                                'mobile' => '📱 Mobil',
                                'tablet' => '📟 Tablet',
                            ])
                            ->columns(3)
                            ->helperText('Reklamlarınızın hangi cihaz türlerinde (masaüstü bilgisayarlar, mobil telefonlar veya tabletler) gösterileceğini seçin. Örneğin, sadece mobil kullanıcıları hedefleyebilirsiniz.'),

                        CheckboxList::make('targeting_os')
                            ->label('⚙️ Hedef İşletim Sistemleri')
                            ->options([
                                'ios' => '🍎 iOS',
                                'android' => '🤖 Android',
                                'windows' => '🪟 Windows',
                                'macos' => '💻 macOS',
                                'linux' => '🐧 Linux',
                                'other' => 'Diğer',
                            ])
                            ->columns(3)
                            ->helperText('Reklamlarınızın hangi işletim sistemlerinde (örn: iOS, Android, Windows) gösterileceğini seçin. Belirli bir işletim sistemine sahip kullanıcıları hedeflemek için kullanışlıdır.'),

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
                        Toggle::make('run_until_budget_depleted')
                            ->label('Bakiye Bitene Kadar Devam Et')
                            ->default(false)
                            ->live()
                            ->helperText('Bu seçenek aktifse, kampanya belirlenen bütçe (Toplam Bütçe) bitene kadar devam eder ve bitiş tarihi dikkate alınmaz.'),

                        DatePicker::make('start_date')
                            ->label('Başlangıç Tarihi')
                            ->nullable()
                            ->helperText('Kampanyanın ne zaman başlayacağını belirleyin.'),
                        
                        DatePicker::make('end_date')
                            ->label('Bitiş Tarihi')
                            ->nullable()
                            ->hidden(fn (Get $get): bool => $get('run_until_budget_depleted'))
                            ->helperText('Kampanyanın ne zaman sona ereceğini belirleyin. "Bakiye Bitene Kadar Devam Et" seçeneği aktifse bu alan gizlenir.'),
                        
                        TextInput::make('daily_click_limit')
                            ->label('Günlük Tıklama Limiti')
                            ->numeric()
                            ->nullable()
                            ->helperText('Kampanyanızın bir günde alabileceği maksimum tıklama sayısını belirleyin. 0 bırakılırsa günlük limit uygulanmaz.'),
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
                    ])
                    ->columns(2),

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('campaignTemplate.name')
                    ->label('Şablon')
                    ->placeholder('Yok')
                    ->sortable(),

                TextColumn::make('campaign_type')
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
                Tables\Filters\SelectFilter::make('campaign_template_id')
                    ->relationship('campaignTemplate', 'name')
                    ->label('Şablon'),
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
