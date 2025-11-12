<?php
// filepath: c:\Users\ThinkPad X280\BE-Partnership\app\Filament\Widgets\TopPerformingCodesChart.php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PartnersCode;

class TopPerformingCodesChart extends ChartWidget
{
    protected ?string $heading = 'Top Performing Codes';

    protected static ?string $icon = 'heroicon-o-chart-bar';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $topCodes = PartnersCode::withCount('claimRecords')
            ->orderByDesc('claim_records_count')
            ->take(5)
            ->get();

        if ($topCodes->isEmpty() || $topCodes->sum('claim_records_count') == 0) {
            return [
                'datasets' => [
                    [
                        'label' => 'Total Claims',
                        'data' => [234, 156, 123, 98, 76],
                        'backgroundColor' => [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                        ],
                        'borderColor' => [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                            'rgb(255, 206, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(153, 102, 255)',
                        ],
                        'borderWidth' => 2,
                    ],
                ],
                'labels' => ['PROMO2025', 'DISCOUNT10', 'NEWYEAR', 'FLASH50', 'SAVE20'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Claims',
                    'data' => $topCodes->pluck('claim_records_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                    ],
                    'borderColor' => [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $topCodes->pluck('unique_code')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
                'y' => [
                    'ticks' => [
                        'autoSkip' => false,
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
