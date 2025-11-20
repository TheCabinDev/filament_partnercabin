<?php

namespace App\Filament\Resources\PoinActivities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PoinActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('reservation_id')
                    ->label('Reservasi'),

                TextColumn::make('type_activity')
                    ->label('Tipe Aktivitas')
                    ->badge()
                    ->colors([
                        'success' => 'EARN',
                        'danger' => 'USE',
                        'info' => 'EXPIRE',
                    ])
                    ->sortable(),

                TextColumn::make('partner.name')
                    ->label('Partner')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('partnercode.unique_code')
                    ->label('kode unik')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('amount')
                    ->label('Jumlah poin')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('date_transaction')
                    ->dateTime(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type_activity')
                    ->label('Tipe Aktivitas')
                    ->options([
                        'EARN' => 'EARN',
                        'USE' => 'USE',
                        'EXPIRE' => 'EXPIRE',
                    ]),
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
