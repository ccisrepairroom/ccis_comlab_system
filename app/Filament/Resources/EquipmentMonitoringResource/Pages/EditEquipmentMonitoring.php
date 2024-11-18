<?php

namespace App\Filament\Resources\EquipmentMonitoringResource\Pages;

use App\Filament\Resources\EquipmentMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquipmentMonitoring extends EditRecord
{
    protected static string $resource = EquipmentMonitoringResource::class;

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
