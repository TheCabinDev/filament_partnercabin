<?php

namespace App\Filament\Resources\Partners\Widgets;

use App\Models\ClaimCodeRecord;
use App\Models\Partners;
use App\Models\RewardRedemption;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PartnerStatsOverview extends BaseWidget
{
    public ?Partners $record = null;

    protected function getStats(): array
    {
        if (! $this->record) {
            return [
                Stat::make('Status', 'Data tidak tersedia')
                    ->color('danger'),
            ];
        }

        // Total keuntungan IDR dari transaksi SUCCESS
        $totalKeuntungan = ClaimCodeRecord::where('id_partner', $this->record->id)
            ->where('reservation_status', 'SUCCESS')
            ->sum('total_poin_earned');

        // Total penarikan dari reward redemption
        $totalPenarikan = RewardRedemption::where('id_partner', $this->record->id)
            ->where('redemption_status', 'COMPLETED')
            ->sum('cash_amount');

        $sisaSaldo = $totalKeuntungan - $totalPenarikan;

        return [
            Stat::make('Total Keuntungan', 'Rp ' . number_format($totalKeuntungan, 0, ',', '.'))
                ->description('Akumulasi IDR dari transaksi SUCCESS')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Penarikan', 'Rp ' . number_format($totalPenarikan, 0, ',', '.'))
                ->description('Dana yang sudah ditarik')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning'),

            // Stat::make('Sisa Saldo', 'Rp ' . number_format($sisaSaldo, 0, ',', '.'))
            //     ->description($sisaSaldo < 0 ? '⚠️ Saldo minus' : 'Saldo tersedia untuk ditarik')
            //     ->icon('heroicon-o-banknotes')
            //     ->color($sisaSaldo < 0 ? 'danger' : 'info'),
        ];
    }
}
