<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Category;
use App\Models\Facility;
use App\Helpers\RequestManagement;
use App\Livewire\Partials\Navbar;


#[Title('Equipment - CCIS ERMA')]
class EquipmentPage extends Component
{
    use WithPagination;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_facilities = [];

    #[Url]
    public  $search = '';

    #[Url]
    public $sort='latest';

    public $alternateImages = [];

    public $requestedEquipments = [];

    public function mount()
    {
        // Load requested equipment IDs from the cookie
        $this->requestedEquipments = RequestManagement::getRequestedEquipmentIds();
    }

    //add to request list method
    public function addToRequestList($equipment_id){
        $total_count = RequestManagement::addEquipmentToRequestList($equipment_id);

 // Update Livewire state to reflect the change
        $this->requestedEquipments = RequestManagement::getRequestedEquipmentIds();

        $this->dispatch('update-requests-count', total_count: $total_count)->to(Navbar::class);
        $this->dispatch('equipment-requested', equipmentId: $equipment_id);

        // $this->alert('success', 'Equipment added to your request list', [
        //     'position' => 'top-right',
        //     'timer' => 3000,
        //     'toast' => true
        // ]);
    }

    public function removeFromRequestList($equipment_id)
{
    RequestManagement::removeRequestListEquipment($equipment_id);

    // Update Livewire state
    $this->requestedEquipments = RequestManagement::getRequestedEquipmentIds();

    // Dispatch event to update the frontend
    $this->dispatch('equipment-removed', equipmentId: $equipment_id);
}



    public function render()
    {
        //Only show working equipment
        $equipment = Equipment::query()->where('status', 'working');

        // Get alternate images from the first equipment (or handle selection properly)
        $firstEquipment = $equipment->first(); 
        $this->alternateImages = $firstEquipment ? ($firstEquipment->alternate_images ?? []) : [];
            
        //Equipment filter based on categories
        if(!empty($this->selected_categories)){
            $equipment-> whereIn('category_id', $this->selected_categories);
        }
        
        //Equipment filter based on facilities
        if(!empty($this->selected_facilities)){
            $equipment-> whereIn('facility_id', $this->selected_facilities);
        }

        if ($this->search) {
            $equipment->where(function ($query) {
                $query->where('brand_name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('serial_no', 'like', '%' . $this->search . '%')
                      ->orWhere('property_no', 'like', '%' . $this->search . '%')
                      ->orWhere('control_no', 'like', '%' . $this->search . '%')
                      ->orWhere('item_no', 'like', '%' . $this->search . '%')
                      ->orWhere('unit_no', 'like', '%' . $this->search . '%')
                      ->orWhere('po_number', 'like', '%' . $this->search . '%')
                      ->orWhere('supplier', 'like', '%' . $this->search . '%')
                      ->orWhere('date_acquired', 'like', '%' . $this->search . '%')
                      ->orWhere('amount', 'like', '%' . $this->search . '%')
                      ->orWhere('estimated_life', 'like', '%' . $this->search . '%')
                      ->orWhere('person_liable', 'like', '%' . $this->search . '%')
                      ->orWhere('remarks', 'like', '%' . $this->search . '%')
                      ->orWhereHas('facility', function ($facilityQuery) { 
                        $facilityQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                      ->orWhereHas('category', function ($categoryQuery) { 
                            $categoryQuery->where('description', 'like', '%' . $this->search . '%');
                        });  
                    
            });
        }

        switch ($this->sort) {
            case 'latest':
                $equipment->latest(); // Orders by `created_at` DESC
                break;
    
            case 'facility':
                $equipment->orderBy('facility_id')->orderBy('id', 'desc'); // Sort by facility then newest
                break;
    
            case 'category':
                $equipment->orderBy('category_id')->orderBy('id', 'desc'); // Sort by category then newest
                break;
        }
        
        $noEquipmentFound = !$equipment->exists(); 

        return view('livewire.equipment-page', [
            'equipment' => $equipment->paginate(15),
            'categories' => Category::whereHas('equipment')->get(),
            'facilities' => Facility::whereHas('equipment')->get(),
            'noEquipmentFound' => $noEquipmentFound,
            // 'equipment' => $equipment->orderBy('id')->cursorPaginate(15, ['*'], 'cursor', 'id'),
            // $requestedEquipmentIds = RequestManagement::getRequestedEquipmentIds(),





        ]);
    }
}