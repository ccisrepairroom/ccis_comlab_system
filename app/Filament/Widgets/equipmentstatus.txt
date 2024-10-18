<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Equipment;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;


class EquipmentStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Equipment Count Per Status';

    // Set the chart's filter to 'working' or any other desired default status
    // protected static ?string $filter = 'working';

    // Configure the chart to refresh automatically every minute
    // protected static ?int $pollingInterval = 60;

    protected static ?string $pollingInterval = '2s';

    protected function getData(): array
    {

        $statusCounts = Equipment::selectRaw('status, COUNT(*) as count')
            ->groupBy('equipment.status')
            ->pluck('count', 'status')
            ->toArray();

        $statuses = [
            'Working',
            'For Repair',
            'For Replacement',
            'Lost',
            'For Disposal',
            'Disposed',
            'Borrowed',
        ];

        $data = [];
        foreach ($statuses as $status) {
            $data[] = $statusCounts[$status] ?? 0; // Default to 0 if status is not found
        }

        return [
            'datasets' => [
                [
                    'label' => 'Equipment Count',
                    'data' => $data,
                    'backgroundColor' => ['#FFA500',
                        /*'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(201, 203, 207, 0.2)',*/
                    ],
                    'borderColor' => ['#FFA500',
                        /*'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)',*/
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $statuses,
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
