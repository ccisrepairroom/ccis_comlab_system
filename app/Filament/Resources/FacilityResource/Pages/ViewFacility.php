<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFacility extends ViewRecord
{
    protected static string $resource = FacilityResource::class;
   
    public function getRedirectUrl(): string
{
    return route('facility-monitoring-modal', ['record' => $this->record->id]);
}
}
