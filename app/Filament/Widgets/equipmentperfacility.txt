<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Equipment;
use App\Models\Facility;

class EquipmentPerFacility extends ChartWidget
{
    protected static ?string $heading = 'Equipment Count Per Facility';

    protected function getData(): array
    {
        // Fetch equipment counts per facility
        $equipmentCounts = Equipment::selectRaw('facility_id, COUNT(*) as count')
            ->groupBy('facility_id')
            ->pluck('count', 'facility_id')
            ->toArray();

        // Fetch all facilities
        $facilities = Facility::all();

        // Prepare labels and data for the chart
        $labels = [];
        $data = [];

        foreach ($facilities as $facility) {
            // Add facility name to labels
            $labels[] = $facility->name;

            // Default equipment count to 0 if the facility has no equipment
            $data[] = isset($equipmentCounts[$facility->id]) ? $equipmentCounts[$facility->id] : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'No. of Equipment',
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
                    'borderColor' => [ '#FFA500',
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
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
