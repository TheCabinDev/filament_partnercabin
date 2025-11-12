<?php
// filepath: c:\Users\ThinkPad X280\BE-Partnership\app\Filament\Widgets\TotalCodesWidget.php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PartnersCode;
use Filament\Support\Icons\Heroicon;

class TotalCodesWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalCodes = PartnersCode::count();
        // $activeCodes = PartnersCode::where('status', 'ACTIVE')->count();
        $inactiveCodes = PartnersCode::where('status', 'INACTIVE')->count();

        $validCodes = PartnersCode::where('status', 'ACTIVE')
            ->where(function($q) {
                $q->whereNull('use_started_at')
                  ->orWhere('use_started_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('use_expired_at')
                  ->orWhere('use_expired_at', '>=', now());
            })
            ->count();

        $expiredCodes = PartnersCode::where('status', 'ACTIVE')
            ->whereNotNull('use_expired_at')
            ->where('use_expired_at', '<', now())
            ->count();


        return [
            Stat::make('Total Kode', $totalCodes)
                ->description('Semua Kode Mitra')
                // ->icon(Heroicon::CheckCircle)
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            // Stat::make('Kode Aktif', $activeCodes)
            //     ->description('Kode aktif saat ini')
            //     // ->icon(Heroicon::CheckCircle)
            //     ->color('success')
            //     ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Valid Codes', $validCodes)
                ->description('Kode Aktif & masa berlaku')
                // ->descriptionIcon('heroicon-o-shield-check')
                ->color('info')
                ->chart([10, 12, 15, 14, 16, 15, 17]),

            Stat::make('Inaktif Kode', $inactiveCodes)
                ->description('Kode dinonaktifkan')
                // ->icon(Heroicon::CheckCircle)
                ->color('danger')
                ->chart([17, 16, 14, 15, 14, 13, 12]),

            Stat::make('Kode Kadaluarsa', $expiredCodes)
                ->description('Kadaluarsa')
                // ->icon(Heroicon::CheckCircle)
                ->color('danger')
                ->chart([3, 4, 5, 2, 6, 3, 7]),
        ];
    }
}
