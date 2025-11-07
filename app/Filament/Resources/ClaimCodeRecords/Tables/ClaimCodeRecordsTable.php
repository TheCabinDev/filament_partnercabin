<?php

namespace App\Filament\Resources\ClaimCodeRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClaimCodeRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('claim_id'),
                TextColumn::make('id_partner')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id_code')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reservation_id')
                    ->searchable(),
                TextColumn::make('reservation_total_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_coin_earned')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reservation_status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
