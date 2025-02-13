<?php

namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Category;
use App\Models\Facility;



#[Title('Equipment - CCIS ERMA')]
class HomePage extends Component
{
    use WithPagination;

   

    public function render()
    {
        $equipment = Equipment::query()->where('status', 'working');
        $categories = Category::all();
        $facilities = Facility::all();


        return view('livewire.home-page', [
            'equipment' => $equipment->orderBy('id')->cursorPaginate(15, ['*'], 'cursor', 'id'),
            'categories' => $categories,
            'facilities' => $facilities,

        ]);
    }
}
