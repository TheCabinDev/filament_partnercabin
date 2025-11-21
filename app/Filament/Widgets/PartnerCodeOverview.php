<?php

namespace App\Filament\Widgets;

use App\Models\Partners;
use App\Models\PartnersCode;
use App\Models\PoinActivity;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PartnerCodeOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Code Partner Analytics ';

    protected ?string $description = 'Rangkuman analisa kode unik ';

    public ?PartnersCode $record = null;

    protected function getStats(): array
    {
        if (!$this->record) {
            return [];
        }
        $idPartnerCode = $this->record->id;


        /**
         * get status of code
         *  */
        $CodeData = PartnersCode::where('id', $idPartnerCode)->first();
        $statusCode = $CodeData->status;
        $statusMap = [
            'ACTIVE' => ['success', 'Status Kode Aktif dan dapat dibagikan'],
            'INACTIVE' => ['danger', 'Status Kode Inaktif, tidak dapat digunakan sementara']
        ];

        $clrCode = $statusMap[$statusCode][0] ?? 'warning';
        $descCode = $statusMap[$statusCode][1] ?? 'Status Kode Tidak Dikenal';

        /**
         * get jumlah claim tersisa & total poin didapat
         *  
         * */
        $stats = PoinActivity::where('id_unique_code', $idPartnerCode)
            ->where('type_activity', 'EARN')
            ->selectRaw('COUNT(*) as claim_count, SUM(amount) as total_earn_amount')
            ->first();

        $PoinActivityCount = $stats->claim_count ?? 0;
        $PoinActivityEarnAmountSum = $stats->total_earn_amount ?? 0;

        $remainingQuota = intval($CodeData->claim_quota) - $PoinActivityCount;

        return [

            Stat::make('Status Kode', $statusCode)
                ->description($descCode)
                // ->icon(Heroicon::CheckCircle)
                ->color($clrCode),
            // ->chart([17, 16, 14, 15, 14, 13, 12]),

            Stat::make('Kuota klaim tersisa', $remainingQuota)
                ->description('Kuota yang dapat diklaim oleh tamu')
                // ->icon(Heroicon::CheckCircle)
                ->color('info'),
            // ->chart([3, 4, 5, 2, 6, 3, 7]),

            Stat::make('Poin terkumpul', intval($PoinActivityEarnAmountSum))
                ->description('Poin yang dikumpulkan dari kode voucher ini')
                // ->icon(Heroicon::CheckCircle)
                ->color('info'),
            // ->link('/'),
            // ->chart([3, 4, 5, 2, 6, 3, 7]),
        ];
    }

    public static function canView(): bool
    {
        return true;
    }
}
