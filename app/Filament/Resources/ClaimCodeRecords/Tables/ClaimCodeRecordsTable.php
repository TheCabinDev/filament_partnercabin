<?php

namespace App\Filament\Resources\ClaimCodeRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClaimCodeRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('partner.name')
                    ->label('Nama Partner'),
                TextColumn::make('partnercode.unique_code')
                    ->searchable()
                    ->label('Kode unik'),
                TextColumn::make('reservation_id')
                    ->searchable(),
                TextColumn::make('reservation_total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_poin_earned')
                    ->label('Dana didapat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reservation_status')
                    ->badge()
                    ->label('Status')
                    ->colors([
                        'success' => 'SUCCESS',
                        'danger' => 'EXPIRED',
                    ])
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
                SelectFilter::make('reservation_status')
                    ->options([
                        'SUCCESS' => 'SUCCESS',
                        'EXPIRED' => 'EXPIRED',
                    ]),
                SelectFilter::make('id_partner')
                    ->label('Partner')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('id_unique_code')
                    ->label('Kode Unik')
                    ->relationship('partnercode', 'unique_code')
                    ->searchable()
                    ->preload(),



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
