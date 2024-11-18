<?php

namespace App\Filament\Resources\FacilityMonitoringResource\Pages;

use App\Filament\Resources\FacilityMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFacilityMonitoring extends EditRecord
{
    protected static string $resource = FacilityMonitoringResource::class;

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
