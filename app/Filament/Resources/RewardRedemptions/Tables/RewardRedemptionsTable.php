<?php

namespace App\Filament\Resources\RewardRedemptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RewardRedemptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('partner.name')
                    ->label('Partner')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('poin_to_redeem')
                    ->label('Poin')
                    ->money('IDR')
                    ->default('-'),
                TextColumn::make('cash_amount')
                    ->label('Uang yang akan diterima partner')
                    ->money('IDR')
                    ->default('-'),

                TextColumn::make('partner.destination_bank')
                    ->label('Bank Tujuan'),

                TextColumn::make('redemption_status')
                    ->badge()
                    ->label('Status Withdraw')
                    ->colors([
                        'info' => 'PENDING',
                        'info' => 'PROCESSING',
                        'success' => 'COMPLETED',
                        'danger' => 'REJECTED',
                    ])
                    ->sortable(),

                TextColumn::make('request_date')
                    ->label('Tanggal Penarikan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('settlement_date')
                    ->label('Tanggal Settlement')
                    ->dateTime('d M Y H:i')
                    ->sortable(),


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
