<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SuppliesAndMaterials;

class SuppliesAndMaterialsPage extends Component
{
    public function render()
    {
        return view('livewire.supplies-and-materials-page', [
            'supplies' => SuppliesAndMaterials::all(),
        ]);
    }
}
