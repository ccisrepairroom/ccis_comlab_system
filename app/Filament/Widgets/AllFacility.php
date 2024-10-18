<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Support\Enums\IconPosition;
use App\Models\Facility;


class AllFacility extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];

        return [
            Stat::make(
                '',
                Facility::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                ->count()
                )
                ->description('Total Facilities')
                ->descriptionIcon('heroicon-m-building-office-2', IconPosition::After)
                ->color('info'),
        ];
    }
}
