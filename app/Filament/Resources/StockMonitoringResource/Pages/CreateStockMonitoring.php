<?php

namespace App\Filament\Resources\StockMonitoringResource\Pages;

use App\Filament\Resources\StockMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStockMonitoring extends CreateRecord
{
    protected static string $resource = StockMonitoringResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
