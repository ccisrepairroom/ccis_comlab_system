<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Equipment;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ForDisposal extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Equipment Count (For Disposal) by Category';
    protected static string $color = 'primary';
    protected int | string | array $columnSpan = 3;
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        // Get start and end dates from the filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Fetch all categories and count their equipment with status 'For Disposal' within the date range
        $categories = Equipment::where('status', 'For Disposal')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', Carbon::parse($startDate));
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', Carbon::parse($endDate));
            })
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Create an array to hold category descriptions and their equipment counts
        $categoryData = [];

        foreach ($categories as $category) {
            $categoryData[] = [
                'description' => $category->category->description ?? 'Unknown',
                'count' => $category->count,
            ];
        }

        // Sort the data by equipment count in descending order
        usort($categoryData, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        // Extract sorted labels and data
        $labels = array_column($categoryData, 'description');
        $data = array_column($categoryData, 'count');
    
       
        return [
            'datasets' => [
                [
                    'label' => "Equipment Count (For Disposal)",
                    'data' => $data,
                    'backgroundColor' => '#fa5b3c', // Customize the bar color
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
            'indexAxis' => 'y', // Horizontal bar chart
        ];
    }
}
