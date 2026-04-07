<?php

namespace App\Filament\Resources\RewardRedemptions\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                TextColumn::make('raw_amount_to_redeem')
                    ->label('Nominal mentah (Pencarian Dana + Admin Fee)')
                    ->tooltip('Nominal total ini adalah nominal mentah yang mencakup jumlah pencarian dana yang akan diterima partner ditambah dengan biaya admin fee yang dikenakan.')
                    ->money('IDR')
                    ->default('-'),
                TextColumn::make('cash_amount')
                    ->label('Nominal Pencarian Dana')
                    ->tooltip('Nominal bersih yang akan diterima partner setelah dikurangi admin fee.')
                    ->money('IDR')
                    ->default('-'),
                TextColumn::make('admin_fee_amount')
                    ->label('Nominal Fee Admin')
                    ->tooltip('Nominal keseluruhan Admin Fee (admin fee, bank tf fee dll).')
                    ->money('IDR')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('partner.destination_bank')
                    ->label('Bank Tujuan')
                    ->description(fn($record) => $record->partner->account_number),

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
                SelectFilter::make('redemption_status')
                    ->options([
                        'PENDING' => 'PENDING',
                        'PROCESSING' => 'PROCESSING',
                        'COMPLETED' => 'COMPLETED',
                        'REJECTED' => 'REJECTED',
                    ]),
                SelectFilter::make('id_partner')
                    ->label('Partner')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('trx_indate_range')
                    ->label('Periode Settlement')
                    ->schema([
                        DatePicker::make('from') // Nama field sebaiknya berbeda dengan nama kolom agar tidak membingungkan
                            ->label('Dari Tanggal')
                            ->native(false),
                        DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            // Filter jika tanggal "Dari" diisi
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('settlement_date', '>=', $date),
                            )
                            // Filter jika tanggal "Sampai" diisi
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('settlement_date', '<=', $date),
                            );
                    })
                    // Menampilkan indikator filter aktif di atas tabel (Opsional tapi sangat disarankan)
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = 'Check-in dari: ' . Carbon::parse($data['from'])->format('d M Y');
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = 'Check-in sampai: ' . Carbon::parse($data['until'])->format('d M Y');
                        }
                        return $indicators;
                    }),
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
