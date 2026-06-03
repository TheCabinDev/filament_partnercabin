<?php

namespace App\Filament\Resources\Partners\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RewardRedemptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'rewardRedemptions';

    public function canCreate(): bool
    {
        return false;
    }

    public function canEdit($record): bool
    {
        return false;
    }

    public function canDelete($record): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('raw_amount_to_redeem')
                    ->label('Raw Amount')
                    ->sortable(),
                TextColumn::make('cash_amount')
                    ->label('Cash Amount')
                    ->sortable(),
                TextColumn::make('redemption_status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
