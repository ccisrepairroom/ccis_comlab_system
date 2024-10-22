<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Equipment;
use App\Models\Facility;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class EquipmentCountPerFacility extends ChartWidget
{
    use InteractsWithPageFilters; // Use to interact with page filters

    protected static ?string $heading = 'Equipment Count per Facility';
    protected static string $color = 'primary';
    protected int | string | array $columnSpan = 3;

    protected function getData(): array
    {
        // Get start and end dates from the filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Fetch all facilities and their equipment counts within the date range
        $facilities = Facility::withCount(['equipment' => function ($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('created_at', '>=', Carbon::parse($startDate));
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', Carbon::parse($endDate));
            }
        }])->get();

        // Create an array to hold facility descriptions and their equipment counts
        $facilityData = [];

        // Populate the facility data array
        foreach ($facilities as $facility) {
            $facilityData[] = [
                'description' => $facility->name,
                'count' => $facility->equipment_count,
            ];
        }

        // Sort the facility data by equipment count in descending order
        usort($facilityData, function ($a, $b) {
            return $b['count'] <=> $a['count']; // Sort descending
        });

        // Extract sorted labels and data
        $labels = array_column($facilityData, 'description');
        $data = array_column($facilityData, 'count');

        // ROY G. BIV colors
        $colors = ['#FFCC80', '#FF9800', '#F57C00', '#FFC107', '#FFEB3B', '#FFD700', '#795548'];

        // Assign colors to each data point
        $backgroundColors = [];
        foreach ($data as $index => $count) {
            $backgroundColors[] = $colors[$index % count($colors)]; // Cycle through ROY G. BIV
        }

        return [
            'datasets' => [
                [
                    'label' => "Equipment Count",
                    'data' => $data,
                    'backgroundColor' => $backgroundColors, // Use dynamic colors
                ],
            ],
            'labels' => $labels, // Use sorted facility descriptions as labels
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bar chart type
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', // Rotates chart to horizontal
        ];
    }
}
