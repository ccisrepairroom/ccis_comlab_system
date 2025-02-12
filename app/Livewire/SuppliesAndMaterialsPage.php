<?php

namespace App\Livewire;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\SuppliesAndMaterials;
use App\Models\Category;
use App\Models\Facility;


#[Title('Supplies and Materials - CCIS ERMA')]
class SuppliesAndMaterialsPage extends Component
{
    public function render()
    {
        $supplies_and_materials = SuppliesAndMaterials::all();
        $categories = Category::all();
        $facilities = Facility::all();

        return view('livewire.supplies-and-materials-page', [
            'supplies_and_materials' => $supplies_and_materials,
            'categories' => $categories,
            'facilities' => $facilities,
        ]);
    }
}
