<?php

namespace App\Filament\Resources\FacilityMonitoringResource\Pages;

use App\Filament\Resources\FacilityMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFacilityMonitorings extends ListRecords
{
    protected static string $resource = FacilityMonitoringResource::class;

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
    protected ?string $heading = 'Facility Monitoring History';

}
