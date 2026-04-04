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
                TextColumn::make('partnercode.unique_code')
                    ->searchable()
                    ->label('Kode unik')
                    ->description(fn($record) => $record->partner->name),
                TextColumn::make('reservation_id')
                    ->searchable(),
                TextColumn::make('reservation_total_price')
                    ->tooltip('nilai ini digunakan untuk perhitungan komisi. Nilai ini bukan harga yang dibayarkan tamu, tapi harga total reservasi sebelum potongan untuk tamu')
                    ->label('IDR Total Harga')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_poin_earned')
                    ->tooltip('nilai ini dihitung berdasarkan rate profit % dikalikan dengan IDR Total Harga Reservasi')
                    ->label('IDR Dana didapat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rate_profit')
                    ->label('Rate Profit %')
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
                    ->label('Tgl Transaksi')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('check_in_time')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('check_out_time')
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
