<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\Category;
use Filament\Support\Enums\IconPosition;

class AllCategory extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];

        return [
            Stat::make(
                '',
                Category::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                         ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                         ->count()
            )
            ->description('Total Equipment Categories')
            ->descriptionIcon('heroicon-m-funnel', IconPosition::After)
            ->color('primary'),
        ];
    }
}
