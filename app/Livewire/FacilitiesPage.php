<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Facility;

#[Title('Facilities - CCIS ERMA')]
class FacilitiesPage extends Component
{
    use WithPagination;

    #[Url]
    public $selected_facility_types = [];

    #[Url]
    public $selected_floor_levels = [];

    #[Url]
    public $selected_buildings = [];

    #[Url]
    public $search = '';

    #[Url]
    public $sort = 'latest';

    public function render()
    {
        // Build base query with filters and search
        $query = Facility::query();

        if (!empty($this->selected_facility_types)) {
            $query->whereIn('facility_type', $this->selected_facility_types);
        }

        if (!empty($this->selected_floor_levels)) {
            $query->whereIn('floor_level', $this->selected_floor_levels);
        }

        if (!empty($this->selected_buildings)) {
            $query->whereIn('building', $this->selected_buildings);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('connection_type', 'like', '%' . $this->search . '%')
                  ->orWhere('facility_type', 'like', '%' . $this->search . '%')
                  ->orWhere('cooling_tools', 'like', '%' . $this->search . '%')
                  ->orWhere('floor_level', 'like', '%' . $this->search . '%')
                  ->orWhere('building', 'like', '%' . $this->search . '%')
                  ->orWhere('remarks', 'like', '%' . $this->search . '%');
            });
        }

        // Use filtered query for pagination
        $facilities = $query->paginate(15);

        // Determine if no results found
        $noFacilityFound = $facilities->isEmpty();

        // Get distinct filter options
        $facilityTypes = Facility::select('facility_type')
            ->distinct()
            ->whereNotNull('facility_type')
            ->pluck('facility_type');

        $floorLevels = Facility::select('floor_level')
            ->distinct()
            ->whereNotNull('floor_level')
            ->pluck('floor_level');

        $buildings = Facility::select('building')
            ->distinct()
            ->whereNotNull('building')
            ->pluck('building');

        return view('livewire.facilities-page', [
            'facilities' => $facilities,
            'facilityTypes' => $facilityTypes,
            'floorLevels' => $floorLevels,
            'buildings' => $buildings,
            'noFacilityFound' => $noFacilityFound,
        ]);
    }
}
