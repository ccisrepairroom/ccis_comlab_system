<?php

namespace App\Filament\Resources\EquipmentSummaryResource\Pages;

use App\Filament\Resources\EquipmentSummaryResource;
use Filament\Actions;
use App\Models\Facility;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListEquipmentSummaries extends ListRecords
{
    protected static string $resource = EquipmentSummaryResource::class;

    protected ?string $heading = 'Equipment Monitoring';

    protected static ?string $title = 'Equipment Monitoring';

    public function getTabs(): array
    {
        $facilities = Facility::all();

        return array_merge(
            [
                Tab::make('All')
                    ->modifyQueryUsing(fn($query) => $query),
                
                /*    Tab::make('Returned')
                    ->modifyQueryUsing(fn($query) => $query->where('availability', 'Returned')),
                Tab::make('Unreturned')
                    ->modifyQueryUsing(fn($query) => $query->where('availability', 'Unreturned')),*/
            ],
            $facilities->map(
                fn($facility) =>
                Tab::make($facility->name)
                    ->modifyQueryUsing(fn($query) => $query->where('facility_id', $facility->id))
            )->toArray()
        );
    }
}
