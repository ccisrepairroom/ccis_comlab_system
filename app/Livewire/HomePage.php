<?php

namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Category;




#[Title('Equipment - CCIS ERMA')]
class HomePage extends Component
{
    use WithPagination;

   

    public function render()
    {
        $equipment = Equipment::all();
        $categories = Category::all();

        return view('livewire.home-page', [
            'equipment' => $equipment,
            'categories' => $categories,
        ]);
    }
}
