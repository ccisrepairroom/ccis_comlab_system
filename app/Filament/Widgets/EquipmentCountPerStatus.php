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
    //protected static string $color = 'primary';
    protected static bool $isLazy = false;

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
    
        // Check if data is empty to avoid max() error
        if (empty($data)) {
            return [
                'datasets' => [
                    [
                        'label' => "No Data Available",
                        'data' => [0],  // Return default data in case of no records
                        'backgroundColor' => ['rgba(0, 0, 0, 0.3)'],  // Default gray color
                    ],
                ],
                'labels' => ['No Data'],
            ];
        }
    
        $baseGreen = 'AACB73'; 
    
        // Calculate dynamic opacity based on max count
        $maxCount = max($data);
        $backgroundColors = [];
        foreach ($data as $count) {
            $opacity = 0.3 + (0.7 * $count / $maxCount); // Adjust darkness based on count
            $backgroundColors[] = $this->adjustColorOpacity($baseGreen, $opacity); // Apply dynamic opacity
        }
    
        return [
            'datasets' => [
                [
                    'label' => "Equipment Count",
                    'data' => $data,
                    'backgroundColor' => $backgroundColors, // Use green gradient colors
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    
    // Helper function to apply opacity to a hex color
    protected function adjustColorOpacity($hexColor, $opacity)
    {
        $hexColor = ltrim($hexColor, '#');
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));
    
        return "rgba($r, $g, $b, $opacity)";
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
