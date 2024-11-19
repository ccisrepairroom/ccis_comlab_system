<?php

namespace App\Filament\Resources\StockMonitoringResource\Pages;

use App\Filament\Resources\StockMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockMonitorings extends ListRecords
{
    protected static string $resource = StockMonitoringResource::class;

    /*protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }*/
    public function getBreadcrumbs(): array
    {
        return [];
    }
    protected ?string $heading = 'Stock Monitoring History';

}
