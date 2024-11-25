<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class EquipmentCountPerCategory extends ChartWidget
{
    use InteractsWithPageFilters; // Use to interact with page filters

    protected static ?string $heading = 'Equipment Count per Category';
    protected static string $color = 'primary';
    protected int | string | array $columnSpan = 3;
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        // Get start and end dates from the filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Fetch all categories and their equipment counts within the date range
        $categories = Category::withCount(['equipment' => function ($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('created_at', '>=', Carbon::parse($startDate));
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', Carbon::parse($endDate));
            }
        }])->get();

        // Create an array to hold category descriptions and their equipment counts
        $categoryData = [];

        // Populate the category data array
        foreach ($categories as $category) {
            $categoryData[] = [
                'description' => $category->description,
                'count' => $category->equipment_count,
            ];
        }

        // Sort the category data by equipment count in descending order
        usort($categoryData, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        // Extract sorted labels and data
        $labels = array_column($categoryData, 'description');
        $data = array_column($categoryData, 'count');

        // Handle case where data might be empty
        if (empty($data)) {
            $backgroundColor = [];
            $maxCount = 0;
        } else {
            // Handle division by zero
            $maxCount = max($data);

            // Handle case where maxCount is zero
            if ($maxCount == 0) {
                $maxCount = 1; // Set a fallback value, so division by zero doesn't occur
            }
            
            $backgroundColor = array_map(function ($count) use ($maxCount) {
                $opacity = 0.3 + (0.7 * $count / $maxCount); // Adjust darkness based on count
                return "rgba(244, 176, 40, $opacity)"; // Yellow-orange with varying opacity
            }, $data);
        }

        return [
            'datasets' => [
                [
                    'label' => "Equipment Count",
                    'data' => $data,
                    'backgroundColor' => $backgroundColor, // Use dynamic colors
                ],
            ],
            'labels' => $labels,
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
