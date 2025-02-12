<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Facility;


#[Title('Facilities - CCIS ERMA')]
class FacilitiesPage extends Component
{
    
    public function render()
    {
        $facilities = Facility::all();

        return view('livewire.facilities-page', [
            'facilities' => $facilities,
        ]);
    }
}
