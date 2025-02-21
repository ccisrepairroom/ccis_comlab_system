<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Helpers\RequestManagement;


class Navbar extends Component
{
    public $total_count=0;

    public function mount(){
        $this->total_count = count(RequestManagement::getRequestListEquipmentFromCookie());
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
