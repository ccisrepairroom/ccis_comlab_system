<?php

namespace App\Filament\Resources\EquipmentMonitoringResource\Pages;

use App\Filament\Resources\EquipmentMonitoringResource;
use Filament\Actions;
use App\Models\Facility;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use App\Models\Equipment;


class ListEquipmentMonitorings extends ListRecords
{
    protected static string $resource = EquipmentMonitoringResource::class;

    protected ?string $heading = 'Equipment Monitoring';

    protected static ?string $title = 'Equipment Monitoring';

    protected function getAllEquipmentCount(): int
    {
        return Equipment::count();
    }
    protected function getEquipmentCountForFacility(int $facilityId): int
    {
        return Equipment::where('facility_id', $facilityId)->count();
    }
    public function getTabs(): array
    {
        $facilities = Facility::all();

        return array_merge(
            [
                Tab::make('All')
                ->badge($this->getAllEquipmentCount())
                    ->modifyQueryUsing(fn($query) => $query),
                
                /*    Tab::make('Returned')
                    ->modifyQueryUsing(fn($query) => $query->where('availability', 'Returned')),
                Tab::make('Unreturned')
                    ->modifyQueryUsing(fn($query) => $query->where('availability', 'Unreturned')),*/
            ],
            $facilities->map(
                fn($facility) =>
                Tab::make($facility->name)
                    ->badge($this->getEquipmentCountForFacility($facility->id))
                    ->modifyQueryUsing(fn($query) => $query->where('facility_id', $facility->id))
            )->toArray()
        );
    }
    
}
