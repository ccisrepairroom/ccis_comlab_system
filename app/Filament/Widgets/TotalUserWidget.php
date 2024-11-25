<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\BorrowedItems;
use Carbon\Carbon;

class TotalUserWidget extends BaseWidget
{
    //use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 3;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        //$startDate= $this->filters['startDate'];
        //$endDate= $this->filters['endDate'];

        // User statistics
        $previousTotalUsers = User::whereDate('created_at', Carbon::yesterday())->count();
        $currentTotalUsers = User::count();
        $userIncrease = $currentTotalUsers - $previousTotalUsers;

        $userRegistrationsLast7Days = User::where('created_at', '>=', Carbon::now()->subDays(7))
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            })
            ->map(function ($day) {
                return $day->count();
            });

        $userChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $userChartData[] = $userRegistrationsLast7Days->get($date, 0);
        }

        // Borrow statistics
        $borrowRecordsLast7Days = BorrowedItems::where('created_at', '>=', Carbon::now()->subDays(7))
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            })
            ->map(function ($day) {
                return $day->count();
            });

        $borrowChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $borrowChartData[] = $borrowRecordsLast7Days->get($date, 0);
        }

        $totalBorrowRecords = BorrowedItems::count();
        $totalBorrowedToday = BorrowedItems::whereDate('created_at', Carbon::today())->count();


        return [
            Stat::make('Total Users', $currentTotalUsers)
                //->chart($userChartData)
                ->chart([1,2,3,7,3])
                ->description("{$userIncrease} New users that joined")
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('Borrowed Items Last 7 Days', $totalBorrowRecords)
                //->chart($borrowChartData)
                ->chart([1,2,3,7,3])
                ->description('Borrowed items over the last 7 days')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            Stat::make('Total Borrowed Today', $totalBorrowedToday)
                ->description('Borrowed items today')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->chart([1,2,3,7,3])                
                ->color('primary'),
        ];
    }
    
}
