<?php

namespace App\Filament\Resources\EquipmentMonitoringResource\Pages;

use App\Filament\Resources\EquipmentMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEquipmentMonitorings extends ListRecords
{
    protected static string $resource = EquipmentMonitoringResource::class;
    protected ?string $heading = 'Equipment Monitoring History';

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
    
}
