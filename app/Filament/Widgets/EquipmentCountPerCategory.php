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
            return $b['count'] <=> $a['count']; // Sort descending
        });

        // Extract sorted labels and data
        $labels = array_column($categoryData, 'description');
        $data = array_column($categoryData, 'count');

        return [
            'datasets' => [
                [
                    'label' => "Equipment Count",
                    'data' => $data,
                    'backgroundColor' => '#f4b028', // Customize bar color
                ],
            ],
            'labels' => $labels, // Use sorted category descriptions as labels
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
