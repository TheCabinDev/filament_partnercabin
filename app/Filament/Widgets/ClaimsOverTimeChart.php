<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\ClaimCodeRecord;
use Illuminate\Support\Facades\DB;

class ClaimsOverTimeChart extends ChartWidget
{
    protected ?string $heading = 'Claims Over Time Chart';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {

        $data = ClaimCodeRecord::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(6))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $labels = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');

            $dayData = $data->firstWhere('date', $date->format('Y-m-d'));
            $counts[] = $dayData ? $dayData->count : 0;
        }
        return [
            'datasets' => [
                [
                    'label' => 'Claims',
                    'data' => $counts,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
