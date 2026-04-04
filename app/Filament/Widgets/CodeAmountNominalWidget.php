<?php

namespace App\Filament\Widgets;

use App\Models\ClaimCodeRecord;
use App\Models\PartnersCode;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class CodeAmountNominalWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Leaderboard Performa Kode Partner';

    protected static ?int $sort = 7;

    public function table(Table $table): Table
    {
        $dateFilter = $this->getTableFilterState('date_range') ?? [];

        $dateIn = data_get($dateFilter, 'date_in');
        $dateOut = data_get($dateFilter, 'date_out');

        $startDate = $dateIn ? Carbon::parse($dateIn)->startOfDay() : null;
        $endDate = $dateOut ? Carbon::parse($dateOut)->endOfDay() : null;

        $query = PartnersCode::query()
            ->select(['partners_codes.id', 'partners_codes.unique_code'])
            // 1. Menghitung JUMLAH TRANSAKSI (Leaderboard Count)
            ->selectSub(
                ClaimCodeRecord::query()
                    ->selectRaw('COUNT(*)')
                    ->where('reservation_status', 'SUCCESS')
                    ->whereColumn('id_code', 'partners_codes.id')
                    ->when($startDate, fn(Builder $subQuery) => $subQuery->where('check_in_time', '>=', $startDate))
                    ->when($endDate, fn(Builder $subQuery) => $subQuery->where('check_in_time', '<=', $endDate)),
                'transaksi_count'
            )
            // 2. Menghitung NOMINAL TRANSAKSI (Leaderboard Sum)
            ->selectSub(
                ClaimCodeRecord::query()
                    ->selectRaw('COALESCE(SUM(reservation_total_price), 0)')
                    ->where('reservation_status', 'SUCCESS')
                    ->whereColumn('id_code', 'partners_codes.id')
                    ->when($startDate, fn(Builder $subQuery) => $subQuery->where('check_in_time', '>=', $startDate))
                    ->when($endDate, fn(Builder $subQuery) => $subQuery->where('check_in_time', '<=', $endDate)),
                'nominal_total'
            );

        return $table
            ->query($query)
            // Default diurutkan berdasarkan jumlah transaksi terbanyak
            ->defaultSort('transaksi_count', 'desc')
            ->filters([
                Filter::make('date_range')
                    ->label('Periode Check-in')
                    ->schema([
                        DatePicker::make('date_in')
                            ->label('Dari Tanggal')
                            ->native(false),
                        DatePicker::make('date_out')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->columns(2),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->columns([
                TextColumn::make('unique_code')
                    ->label('Nama Kode Partner')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('transaksi_count')
                    ->label('Banyak Transaksi')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('nominal_total')
                    ->label('Sum Nominal')
                    ->alignEnd()
                    ->sortable()
                    ->money('IDR', locale: 'id_ID'),
            ])
            ->paginated([10, 25, 50]);
    }
}
