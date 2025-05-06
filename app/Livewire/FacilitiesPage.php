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

        return view('livewire.facilities-page', [
            'facilities' => $facilities,
        ]);
    }
}
