<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\RequestManagement;


class Navbar extends Component
{
    public $total_count=0;

    public function mount(){
        $this->total_count = count(RequestManagement::getRequestListEquipmentFromCookie());
    }

    #[On('update-requests-count')]
    public function updateRequestsCount($total_count){
        $this->total_count = $total_count;
    }



    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
