<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\RequestManagement;


#[Title('Requests - CCIS ERMA')]
class RequestPage extends Component
{
    public $requestlist_equipment =[];
    public $total_request;

    public function mount(){
        $this->requestlist_equipment = RequestManagement::getRequestListEquipmentFromCookie();
        $this->total_request = RequestManagement::calculateTotalRequestedEquipment($this->requestlist_equipment);
       
    }


    public function render()
    {
        return view('livewire.request-page');
    }
}

