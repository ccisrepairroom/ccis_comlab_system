<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\RequestManagement;
use App\Livewire\Partials\Navbar;



#[Title('Request - CCIS ERMA')]
class RequestPage extends Component
{
    public $requestlist_equipment =[];
    public $total_request;
    public $category_totals = [];


    public function mount(){
        $this->requestlist_equipment = RequestManagement::getRequestListEquipmentFromCookie();
        $this->total_request = RequestManagement::calculateTotalRequestedEquipment($this->requestlist_equipment);
        $this->category_totals = RequestManagement::calculateTotalByCategory($this->requestlist_equipment);


        $this->dispatch('update-requests-count', total_count: count($this->requestlist_equipment))->to(Navbar::class);

       
    }

    public function removeItem($equipment_id){
        // Remove the item and update the list
        $this->requestlist_equipment = RequestManagement::removeRequestListEquipment($equipment_id);
        
        // Recalculate totals based on the updated list
        $this->total_request = RequestManagement::calculateTotalRequestedEquipment($this->requestlist_equipment);
        $this->category_totals = RequestManagement::calculateTotalByCategory($this->requestlist_equipment);
    
        // Dispatch update event for UI changes
        $this->dispatch('update-requests-count', total_count: count($this->requestlist_equipment))->to(Navbar::class);
        
        // Force re-rendering
        $this->dispatch('refresh');
    }
    
    public function proceed()
    {
        if (!auth()->check()) {
            return redirect()->to('/signin'); // Redirect to login if not authenticated
        }
    
        return redirect()->to('/request-form');
    }
    

    public function render()
    {
        return view('livewire.request-page',[
            // 'category_totals' => $this->category_totals

        ]);
    }
}

