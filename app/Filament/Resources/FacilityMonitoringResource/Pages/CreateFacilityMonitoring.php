<?php

namespace App\Filament\Resources\FacilityMonitoringResource\Pages;

use App\Filament\Resources\FacilityMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFacilityMonitoring extends CreateRecord
{
    protected static string $resource = FacilityMonitoringResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
