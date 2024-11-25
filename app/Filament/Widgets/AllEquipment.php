<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\Category;


use Filament\Support\Enums\IconPosition;

class AllEquipment extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 3;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];

        return [
            Stat::make(
                '',
                Equipment::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                         ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                         ->count()
            )
            ->description('Total Equipment')
            ->descriptionIcon('heroicon-m-cube', IconPosition::After)
            ->chart([1,2,3,7,3])
            ->color('success'),
            
            Stat::make(
                '',
            
                Facility::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                ->count()
                )
                ->description('Total Facilities')
                ->descriptionIcon('heroicon-m-building-office-2', IconPosition::After)
                ->chart([1,2,3,7,3])
                ->color('info'),

                Stat::make(
                    '',
                Category::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                             ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                             ->count()
                )
                ->description('Total Equipment Categories')
                ->descriptionIcon('heroicon-m-funnel', IconPosition::After)
                ->chart([1,2,3,7,3])
                ->color('primary'),
        ];

        
    }
}
