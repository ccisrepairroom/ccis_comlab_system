<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Facility;

class FacilitiesPage extends Component
{
    
    public function render()
    {
        return view('livewire.facilities-page', [
            'facilities' => Facility::all(),
        ]);
    }
}
