<?php

namespace App\Filament\Widgets;

use App\Models\ClaimCodeRecord;
use App\Models\PartnersCode;
use App\Models\PoinActivity;
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

    protected static ?string $heading = 'Rekap Kode Unik';

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
            ->selectSub(
                PoinActivity::query()
                    ->selectRaw('COALESCE(SUM(amount), 0)')
                    ->where('type_activity', 'EARN')
                    ->whereColumn('id_unique_code', 'partners_codes.id')
                    ->when($startDate, fn (Builder $subQuery) => $subQuery->where('date_transaction', '>=', $startDate))
                    ->when($endDate, fn (Builder $subQuery) => $subQuery->where('date_transaction', '<=', $endDate)),
                'amount_total'
            )
            ->selectSub(
                ClaimCodeRecord::query()
                    ->selectRaw('COALESCE(SUM(reservation_total_price), 0)')
                    ->where('reservation_status', 'SUCCESS')
                    ->whereColumn('id_code', 'partners_codes.id')
                    ->when($startDate, fn (Builder $subQuery) => $subQuery->where('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $subQuery) => $subQuery->where('created_at', '<=', $endDate)),
                'nominal_total'
            );

        return $table
            ->query($query)
            ->defaultSort('unique_code')
            ->filters([
                Filter::make('date_range')
                    ->label('Periode')
                    ->schema([
                        DatePicker::make('date_in')
                            ->label('Date In')
                            ->placeholder('Pilih tanggal awal')
                            ->native(false),
                        DatePicker::make('date_out')
                            ->label('Date Out')
                            ->placeholder('Pilih tanggal akhir')
                            ->native(false),
                    ])
                    ->columns(2),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(2)
            ->columns([
                TextColumn::make('unique_code')
                    ->label('Kode Unik')
                    ->searchable(),
                TextColumn::make('amount_total')
                    ->label('Amount')
                    ->alignEnd()
                    ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.'),
                TextColumn::make('nominal_total')
                    ->label('Nominal')
                    ->alignEnd()
                    ->money('IDR', divideBy: 1, locale: 'id_ID'),
            ])
            ->paginated([10, 25, 50]);
    }
}
