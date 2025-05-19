<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Facility;
use App\Models\FacilityMonitoring;

class ViewFacility extends ViewRecord
{
    protected static string $resource = FacilityResource::class;

    public function getView(): string
    {
        return 'filament.resources.facility-monitoring-modal';
    }

    public function getViewData(): array
    {
        return [
            'facility' => $this->record, // Facility data
            'monitorings' => $this->record->monitorings ?? collect([]), // Monitoring data
        ];
    }
}

