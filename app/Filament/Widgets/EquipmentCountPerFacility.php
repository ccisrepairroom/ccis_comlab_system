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
    protected static bool $isLazy = false;

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
    
    // Check if data is empty to avoid division by zero
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
    
    // Define base color (#F87A53) and apply dynamic opacity
    $baseColor = '#F87A53';
    $maxCount = max($data);
    
    // Avoid division by zero
    $backgroundColors = [];
    foreach ($data as $count) {
        $opacity = $maxCount > 0 ? 0.3 + (0.7 * $count / $maxCount) : 1; // If maxCount is 0, set opacity to 1
        $backgroundColors[] = $this->adjustColorOpacity($baseColor, $opacity); // Apply dynamic opacity
    }
    
    return [
        'datasets' => [
            [
                'label' => "Equipment Count",
                'data' => $data,
                'backgroundColor' => $backgroundColors, // Use gradient colors
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
