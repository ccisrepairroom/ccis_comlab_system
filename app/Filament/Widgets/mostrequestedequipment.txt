<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Borrow;
use App\Models\Equipment;

class MostRequestedEquipment extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {

        $borrowCounts = Borrow::selectRaw('equipment_id, COUNT(*) as count')
            ->groupBy('equipment_id')
            ->pluck('count', 'equipment_id')
            ->toArray();

        // Get all equipment names to use their names as labels
        $equipments = Equipment::whereIn('id', array_keys($borrowCounts))->get();

        // Prepare labels and data for the chart
        $labels = [];
        $data = [];

        foreach ($equipments as $equipment) {
            $labels[] = $equipment->description;  // Assuming the 'name' field is the equipment name
            $data[] = $borrowCounts[$equipment->id] ?? 0;  // Default to 0 if no borrow count is found
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Times Borrowed',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)',
                    ],
                    'borderWidth' => 1,
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
