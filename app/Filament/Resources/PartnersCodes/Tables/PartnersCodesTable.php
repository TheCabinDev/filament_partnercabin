<?php

namespace App\Filament\Resources\PartnersCodes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class PartnersCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('partner.name')
                    ->label('Partner')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('unique_code')
                    ->label('Unique Code')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Code copied!')
                    ->copyMessageDuration(1500),

                TextColumn::make('fee_percentage')
                    ->label('Fee (%)')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : '-')
                    ->default('-'),

                TextColumn::make('amount_reduction')
                    ->label('Reduction')
                    ->sortable()
                    ->money('IDR')
                    ->default('-'),

                TextColumn::make('claim_quota')
                    ->label('Claim Quota')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ?? 'Unlimited'),

                TextColumn::make('max_claim_per_account')
                    ->label('Max Claim/Account')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ?? 'Unlimited'),

                TextColumn::make('use_started_at')
                    ->label('Start Date')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->default('-'),

                TextColumn::make('use_expired_at')
                    ->label('Expired Date')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->default('-'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'ACTIVE',
                        'danger' => 'INACTIVE',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                    ]),

                SelectFilter::make('id_partner')
                    ->label('Partner')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('id_creator')
                    ->label('Creator')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->where('use_expired_at', '<', now()))
                    ->label('Expired Codes'),

                Filter::make('active_period')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('use_started_at', '<=', now())
                        ->where('use_expired_at', '>=', now()))
                    ->label('Active Period'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
