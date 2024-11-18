<?php

namespace App\Filament\Resources\StockMonitoringResource\Pages;

use App\Filament\Resources\StockMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockMonitoring extends EditRecord
{
    protected static string $resource = StockMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
