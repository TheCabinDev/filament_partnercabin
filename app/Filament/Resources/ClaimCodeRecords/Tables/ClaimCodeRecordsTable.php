<?php

namespace App\Filament\Resources\ClaimCodeRecords\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                Filter::make('trx_indate_range')
                    ->label('Periode Check-in')
                    ->schema([
                        DatePicker::make('from') // Nama field sebaiknya berbeda dengan nama kolom agar tidak membingungkan
                            ->label('CI Dari Tanggal')
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
                                fn(Builder $query, $date): Builder => $query->whereDate('check_in_time', '>=', $date),
                            )
                            // Filter jika tanggal "Sampai" diisi
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('check_in_time', '<=', $date),
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
                Filter::make('trx_date_range')
                    ->label('Periode Trx dibuat')
                    ->schema([
                        DatePicker::make('trx_from') // Nama field sebaiknya berbeda dengan nama kolom agar tidak membingungkan
                            ->label('Trx Dari Tanggal')
                            ->native(false),
                        DatePicker::make('trx_until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            // Filter jika tanggal "Dari" diisi
                            ->when(
                                $data['trx_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            // Filter jika tanggal "Sampai" diisi
                            ->when(
                                $data['trx_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    // Menampilkan indikator filter aktif di atas tabel (Opsional tapi sangat disarankan)
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = 'Dibuat dari: ' . Carbon::parse($data['trx_from'])->format('d M Y');
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = 'Dibuat sampai: ' . Carbon::parse($data['trx_until'])->format('d M Y');
                        }
                        return $indicators;
                    })


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
