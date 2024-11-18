<?php

namespace App\Filament\Resources\EquipmentMonitoringResource\Pages;

use App\Filament\Resources\EquipmentMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEquipmentMonitoring extends CreateRecord
{
    protected static string $resource = EquipmentMonitoringResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
