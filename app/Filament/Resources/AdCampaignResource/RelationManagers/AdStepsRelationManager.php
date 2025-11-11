<?php

namespace App\Filament\Resources\AdCampaignResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Enums\StepType;
use App\Enums\AdType;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class AdStepsRelationManager extends RelationManager
{
    protected static string $relationship = 'adSteps';

    protected static ?string $recordTitleAttribute = 'Adım';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Adım Yapılandırması')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('step_type')
                                    ->label('Adım Türü')
                                    ->options(StepType::class)
                                    ->required()
                                    ->live()
                                    ->columnSpan(2),

                                TextInput::make('wait_time')
                                    ->label('Bekleme Süresi (sn)')
                                    ->numeric()
                                    ->default(5)
                                    ->suffix('saniye')
                                    ->columnSpan(1),

                                Toggle::make('show_popup')
                                    ->label('Bu Adımda Pop-up Göster')
                                    ->columnSpan(3),
                            ]),

                        // Dinamik Reklam Seçici
                        Builder::make('ads')
                            ->label('Bu Adımda Gösterilecek Reklamlar')
                            ->blocks([
                                Builder\Block::make('banner_ad')
                                    ->label('Banner Reklamı')
                                    ->schema([
                                        FileUpload::make('banner_image')
                                            ->label('Banner Görseli')
                                            ->image()
                                            ->directory('campaign-banners')
                                            ->required(),

                                        TextInput::make('banner_url')
                                            ->label('Hedef URL')
                                            ->url()
                                            ->required(),

                                        Select::make('banner_size')
                                            ->label('Banner Boyutu')
                                            ->options([
                                                '728x90' => '728×90 (Leaderboard)',
                                                '300x250' => '300×250 (Medium Rectangle)',
                                                '320x50' => '320×50 (Mobile Banner)',
                                                '160x600' => '160×600 (Wide Skyscraper)',
                                                '300x600' => '300×600 (Half Page)',
                                            ])
                                            ->required(),

                                        TextInput::make('banner_alt_text')
                                            ->label('Alt Metin')
                                            ->placeholder('Reklam açıklaması'),

                                        Toggle::make('banner_responsive')
                                            ->label('Responsive Tasarım')
                                            ->default(true),
                                    ])
                                    ->icon('heroicon-m-rectangle-stack'),

                                Builder\Block::make('popup_ad')
                                    ->label('Pop-up Reklamı')
                                    ->schema([
                                        TextInput::make('popup_title')
                                            ->label('Pop-up Başlığı')
                                            ->required()
                                            ->maxLength(100),

                                        Textarea::make('popup_content')
                                            ->label('Pop-up İçeriği')
                                            ->maxLength(500)
                                            ->rows(3),

                                        TextInput::make('popup_url')
                                            ->label('Hedef URL')
                                            ->url()
                                            ->required(),

                                        FileUpload::make('popup_image')
                                            ->label('Pop-up Görseli')
                                            ->image()
                                            ->directory('campaign-popups'),

                                        Select::make('popup_size')
                                            ->label('Pop-up Boyutu')
                                            ->options([
                                                'small' => 'Küçük (400x300)',
                                                'medium' => 'Orta (600x400)',
                                                'large' => 'Büyük (800x600)',
                                                'fullscreen' => 'Tam Ekran',
                                            ])
                                            ->default('medium'),

                                        Toggle::make('popup_close_button')
                                            ->label('Kapatma Butonu Göster')
                                            ->default(true),

                                        TextInput::make('popup_delay')
                                            ->label('Görüntülenme Gecikmesi (sn)')
                                            ->numeric()
                                            ->default(0)
                                            ->suffix('saniye'),
                                    ])
                                    ->icon('heroicon-m-window'),

                                Builder\Block::make('third_party_code')
                                    ->label('Üçüncü Parti Kodu')
                                    ->schema([
                                        Textarea::make('third_party_code')
                                            ->label('Reklam Kodu')
                                            ->helperText('Google Ads, Facebook Pixel vb. kodları buraya yapıştırın')
                                            ->rows(8)
                                            ->required(),

                                        TextInput::make('code_name')
                                            ->label('Kod Adı')
                                            ->placeholder('Google Ads Banner, Facebook Pixel vb.')
                                            ->required(),

                                        Toggle::make('code_async')
                                            ->label('Asenkron Yükleme')
                                            ->default(true)
                                            ->helperText('Sayfa yükleme hızını artırmak için'),

                                        Select::make('code_position')
                                            ->label('Kod Konumu')
                                            ->options([
                                                'head' => 'Sayfa Başında (<head>)',
                                                'body_start' => 'Gövde Başında',
                                                'body_end' => 'Gövde Sonunda',
                                            ])
                                            ->default('body_start'),
                                    ])
                                    ->icon('heroicon-m-code-bracket'),

                                Builder\Block::make('html_ad')
                                    ->label('Özel HTML Reklamı')
                                    ->schema([
                                        Textarea::make('html_content')
                                            ->label('HTML İçeriği')
                                            ->rows(6)
                                            ->required(),

                                        TextInput::make('css_styles')
                                            ->label('CSS Stilleri')
                                            ->placeholder('.custom-ad { width: 100%; }')
                                            ->rows(3),

                                        TextInput::make('custom_id')
                                            ->label('Özel ID')
                                            ->placeholder('benzersiz-reklam-id'),

                                        Toggle::make('enable_javascript')
                                            ->label('JavaScript Aktif'),

                                        Textarea::make('javascript_code')
                                            ->label('JavaScript Kodu')
                                            ->rows(4)
                                            ->visible(fn (callable $get) => $get('enable_javascript')),
                                    ])
                                    ->icon('heroicon-m-square-3-stack-3d'),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('step_type')
                    ->label('Adım Türü')
                    ->badge()
                    ->sortable(),

                TextColumn::make('wait_time')
                    ->label('Bekleme Süresi')
                    ->suffix(' sn')
                    ->sortable(),

                IconColumn::make('show_popup')
                    ->label('Pop-up')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('ads_count')
                    ->label('Reklam Sayısı')
                    ->getStateUsing(fn ($record) => $record->stepAds()->count())
                    ->badge()
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('step_type')
                    ->options(StepType::class)
                    ->label('Adım Türü'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Adım numarasını otomatik ata
                        $nextStepNumber = $this->getOwnerRecord()->adSteps()->max('step_number') + 1;
                        $data['step_number'] = $nextStepNumber;

                        // Dinamik reklamları işle
                        if (isset($data['ads']) && is_array($data['ads'])) {
                            $data['processed_ads'] = $this->processAdsData($data['ads']);
                            unset($data['ads']);
                        }

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('step_number')
            ->reorderable('step_number');
    }

    protected function processAdsData(array $adsData): array
    {
        $processedAds = [];

        if (isset($adsData['blocks']) && is_array($adsData['blocks'])) {
            foreach ($adsData['blocks'] as $blockType => $blocks) {
                if (is_array($blocks)) {
                    foreach ($blocks as $blockData) {
                        $processedAds[] = [
                            'type' => $blockType,
                            'data' => $blockData,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        return $processedAds;
    }

    protected function getTemplateDescription(string $template): string
    {
        return match($template) {
            'quick_start' => 'Yeni başlayanlar için optimize edilmiş temel kampanya şablonu',
            'brand_awareness' => 'Marka bilinirliğini artırmaya odaklanan kampanya şablonu',
            'lead_generation' => 'Potansiyel müşteri kazanımına yönelik kampanya şablonu',
            'traffic_drive' => 'Web site trafiğini artırmaya odaklanan kampanya şablonu',
            'custom' => 'Kendi özel ayarlarınızı belirleyin',
            default => 'Şablon açıklaması bulunamadı'
        };
    }
}
