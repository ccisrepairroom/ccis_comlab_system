<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\RequestManagement;
use App\Livewire\Partials\Navbar;



#[Title('Request - CCIS ERMA')]
class RequestFillPage extends Component
{
    public $requestlist_equipment =[];
    public $total_request;
    public $category_totals = [];
    public $first_name;
    public $last_name;
    public $date_requested;


    public function mount(){
        $this->requestlist_equipment = RequestManagement::getRequestListEquipmentFromCookie();
        $this->total_request = RequestManagement::calculateTotalRequestedEquipment($this->requestlist_equipment);
        $this->category_totals = RequestManagement::calculateTotalByCategory();
        $this->date_requested = Carbon::now('Asia/Manila')->format('Y-m-d\TH:i');

        
        $requestlist_equipment = RequestManagement::getRequestListEquipmentFromCookie();
        if (count($requestlist_equipment) == 0) {
            return redirect('equipment');
        }
    }



    public function render()
    {
        $requestlist_equipment = RequestManagement::getRequestListEquipmentFromCookie();

        return view('livewire.request-fill-page', [
            'requestlist_equipment' => $requestlist_equipment,
        ]);
    }
}
