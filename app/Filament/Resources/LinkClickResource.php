<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkClickResource\Pages;
use App\Models\LinkClick;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\HtmlString;

class LinkClickResource extends Resource
{
    protected static ?string $model = LinkClick::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    
    protected static ?string $navigationGroup = 'Link Management';
    
    protected static ?string $navigationLabel = 'Statistics Table';
    
    protected static ?string $modelLabel = 'Click';
    
    protected static ?string $pluralModelLabel = 'Statistics Table';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    /**
     * Determine the "reason" badge for a click
     */
    private static function getClickReason(LinkClick $record): array
    {
        if ($record->is_bot) {
            return ['label' => 'Bot', 'color' => 'gray', 'icon' => 'heroicon-o-cpu-chip'];
        }

        if ($record->is_skipped) {
            return ['label' => 'Sampled Out', 'color' => 'warning', 'icon' => 'heroicon-o-funnel'];
        }

        if ($record->cpm_rate > 0) {
            return ['label' => 'Paid', 'color' => 'success', 'icon' => 'heroicon-o-check-circle'];
        }

        return ['label' => 'Unpaid', 'color' => 'danger', 'icon' => 'heroicon-o-x-circle'];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->paginated([10, 25, 50, 100])
            ->striped()
            ->columns([
                // ID
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->width('70px'),

                // Created
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                
                // Reason badge (Paid / Sampled Out / Unpaid / Bot)
                Tables\Columns\TextColumn::make('reason')
                    ->label('Reason')
                    ->state(function (LinkClick $record): string {
                        return self::getClickReason($record)['label'];
                    })
                    ->badge()
                    ->color(function (LinkClick $record): string {
                        return self::getClickReason($record)['color'];
                    })
                    ->icon(function (LinkClick $record): string {
                        return self::getClickReason($record)['icon'];
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('cpm_rate', $direction);
                    }),

                // User
                Tables\Columns\TextColumn::make('link.user.name')
                    ->label('User')
                    ->url(fn (LinkClick $record): ?string => $record->link?->user 
                        ? route('filament.admin.resources.users.edit', $record->link->user) 
                        : null)
                    ->color('primary')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('link.user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->toggleable(),
                
                // Link Code
                Tables\Columns\TextColumn::make('link.code')
                    ->label('Link')
                    ->description(fn (LinkClick $record): string => 'ID: ' . ($record->link_id ?? '—'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->color('primary'),

                // IP Address
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                // Country
                Tables\Columns\TextColumn::make('country')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),
                
                // City
                Tables\Columns\TextColumn::make('city')
                    ->label('City')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                // CPM Rate
                Tables\Columns\TextColumn::make('cpm_rate')
                    ->label('CPM Rate')
                    ->numeric(decimalPlaces: 4)
                    ->sortable()
                    ->color(fn (LinkClick $record): string => $record->cpm_rate > 0 ? 'success' : 'gray'),

                // Publisher Earn (per click)
                Tables\Columns\TextColumn::make('publisher_earn')
                    ->label('Publisher Earn')
                    ->state(function (LinkClick $record): string {
                        if ($record->is_skipped || $record->cpm_rate <= 0) return '$0.0000';
                        return '$' . number_format($record->cpm_rate / 1000, 4);
                    })
                    ->color(fn (LinkClick $record): string => ($record->cpm_rate > 0 && !$record->is_skipped) ? 'success' : 'gray'),

                // Owner Earn (site's cut — if we have advertiser rate)
                Tables\Columns\TextColumn::make('owner_earn')
                    ->label('Owner Earn')
                    ->state(function (LinkClick $record): string {
                        if ($record->is_skipped || $record->cpm_rate <= 0) return '$0.0000';
                        // Try to get advertiser rate from the country's CPM rate
                        $advertiserRate = 0;
                        if ($record->country_id) {
                            $cpmRate = \App\Models\CpmRate::where('country_id', $record->country_id)->first();
                            if ($cpmRate) {
                                $advertiserRate = $cpmRate->advertiser_rate;
                            }
                        }
                        $ownerEarn = max(0, ($advertiserRate - $record->cpm_rate)) / 1000;
                        return '$' . number_format($ownerEarn, 4);
                    })
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Device
                Tables\Columns\TextColumn::make('device_type')
                    ->label('Device')
                    ->sortable()
                    ->toggleable(),

                // OS
                Tables\Columns\TextColumn::make('os')
                    ->label('OS')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Browser
                Tables\Columns\TextColumn::make('browser')
                    ->label('Browser')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Referer Domain (parsed from full URL)
                Tables\Columns\TextColumn::make('referrer')
                    ->label('Referer Domain')
                    ->state(function (LinkClick $record): string {
                        if (!$record->referrer || $record->referrer === 'Direct Access') {
                            return 'Direct';
                        }
                        return parse_url($record->referrer, PHP_URL_HOST) ?? $record->referrer;
                    })
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),

                // Skipped icon
                Tables\Columns\IconColumn::make('is_skipped')
                    ->label('Skipped')
                    ->boolean()
                    ->trueIcon('heroicon-o-funnel')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Bot icon  
                Tables\Columns\IconColumn::make('is_bot')
                    ->label('Bot')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Reason filter (Paid / Sampled Out / Unpaid / Bot)
                SelectFilter::make('reason')
                    ->label('Reason')
                    ->options([
                        'paid'        => '🟢 Paid',
                        'sampled_out' => '🟡 Sampled Out',
                        'unpaid'      => '🔴 Unpaid',
                        'bot'         => '🤖 Bot',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'paid'        => $query->where('cpm_rate', '>', 0)->where('is_skipped', false)->where(function ($q) {
                                $q->where('is_bot', false)->orWhereNull('is_bot');
                            }),
                            'sampled_out' => $query->where('is_skipped', true),
                            'unpaid'      => $query->where('cpm_rate', '<=', 0)->where('is_skipped', false)->where(function ($q) {
                                $q->where('is_bot', false)->orWhereNull('is_bot');
                            }),
                            'bot'         => $query->where('is_bot', true),
                            default       => $query,
                        };
                    }),

                SelectFilter::make('user')
                    ->relationship('link.user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('User'),

                SelectFilter::make('link_id')
                    ->relationship('link', 'code')
                    ->searchable()
                    ->preload()
                    ->label('Link Code'),

                SelectFilter::make('country')
                    ->label('Country')
                    ->options(function () {
                        return LinkClick::whereNotNull('country')
                            ->where('country', '!=', '')
                            ->distinct()
                            ->pluck('country', 'country')
                            ->toArray();
                    })
                    ->searchable(),

                SelectFilter::make('device_type')
                    ->label('Device')
                    ->options(function () {
                        return LinkClick::whereNotNull('device_type')
                            ->distinct()
                            ->pluck('device_type', 'device_type')
                            ->toArray();
                    }),

                SelectFilter::make('os')
                    ->label('OS')
                    ->options(function () {
                        return LinkClick::whereNotNull('os')
                            ->where('os', '!=', '')
                            ->distinct()
                            ->pluck('os', 'os')
                            ->toArray();
                    })
                    ->searchable(),

                TernaryFilter::make('is_bot')
                    ->label('Bot Status')
                    ->placeholder('All Clicks')
                    ->trueLabel('Only Bots')
                    ->falseLabel('Real Users'),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                Filter::make('ip_search')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('ip')
                            ->label('IP Address')
                            ->placeholder('e.g. 192.168.1.1'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['ip'] ?? null,
                            fn (Builder $query, $ip) => $query->where('ip_address', 'like', "%{$ip}%"),
                        );
                    }),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [
            LinkClickResource\Widgets\ClickStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinkClicks::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Click records are log data, cannot be created manually
    }
}
