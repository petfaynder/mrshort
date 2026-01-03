<?php

namespace App\Filament\Pages;

use App\Models\AuditLog;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class AuditLogs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Audit Logs';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'filament.pages.audit-logs';

    public function table(Table $table): Table
    {
        return $table
            ->query(AuditLog::query()->latest())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                TextColumn::make('action_label')
                    ->label('Action')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('action', 'like', "%{$search}%");
                    }),

                TextColumn::make('user.name')
                    ->label('User')
                    ->default('System/Guest')
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (AuditLog $record): string {
                        return $record->description ?? '';
                    })
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GET' => 'gray',
                        'POST' => 'success',
                        'PUT', 'PATCH' => 'warning',
                        'DELETE' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->label('Action Type')
                    ->options([
                        'login' => 'Login',
                        'logout' => 'Logout',
                        'login_failed' => 'Failed Login',
                        'withdrawal_request' => 'Withdrawal Request',
                        'withdrawal_approved' => 'Withdrawal Approved',
                        'withdrawal_rejected' => 'Withdrawal Rejected',
                        'user_deactivated' => 'User Deactivated',
                        'user_reactivated' => 'User Reactivated',
                        'admin_impersonate' => 'Admin Impersonation',
                        'settings_changed' => 'Settings Changed',
                    ]),

                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }
}
