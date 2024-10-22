<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Equipment;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class EquipmentCountPerStatus extends ChartWidget
{
    use InteractsWithPageFilters; // Use to interact with page filters

    protected static ?string $heading = 'Equipment Count per Status';
    protected static string $color = 'primary';
    protected int | string | array $columnSpan = 3;

    protected function getData(): array
    {
        // Get start and end dates from the filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Fetch equipment counts grouped by status within the date range
        $statusCounts = Equipment::select('status', \DB::raw('count(*) as count'))
            ->when($startDate, fn($query) => $query->whereDate('created_at', '>=', Carbon::parse($startDate)))
            ->when($endDate, fn($query) => $query->whereDate('created_at', '<=', Carbon::parse($endDate)))
            ->groupBy('status')
            ->orderBy('count', 'desc') // Sort by count in descending order
            ->get();

        // Prepare labels and data for the chart
        $labels = $statusCounts->pluck('status')->toArray();
        $data = $statusCounts->pluck('count')->toArray();

        // ROY G. BIV colors
        $colors = ['#2da905', '#b3d809', '#FFFF00', '#00FF00', '#0000FF', '#4B0082', '#9400D3'];

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
            'labels' => $labels, // Use status labels
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
