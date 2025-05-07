<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Facility;


#[Title('Facilities - CCIS ERMA')]
class FacilitiesPage extends Component
{
    use WithPagination;

    public function render()
    {
        $facilities = Facility::paginate(15);
        $facilityTypes = Facility::select('facility_type')
                                ->distinct()
                                ->whereNotNull('facility_type')
                                ->pluck('facility_type');

        $floorLevels = Facility::select('floor_level')
                                ->distinct()
                                ->whereNotNull('floor_level')
                                ->pluck('floor_level');

        return view('livewire.facilities-page', [
            'facilities' => $facilities,
            'facilityTypes' => $facilityTypes,
            'floorLevels' => $floorLevels,
        ]);
    }

}
