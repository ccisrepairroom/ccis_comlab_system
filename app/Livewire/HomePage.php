<?php

namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Category;
use App\Models\Facility;



#[Title('Equipment - CCIS ERMA')]
class HomePage extends Component
{
    use WithPagination;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_facilities = [];

    #[Url]
    public  $search = '';

   

    public function render()
    {
        //Only show working equipment
        $equipment = Equipment::query()->where('status', 'working');

        //Equipment filter based on categories
        if(!empty($this->selected_categories)){
            $equipment-> whereIn('category_id', $this->selected_categories);
        }
        
        //Equipment filter based on facilities
        if(!empty($this->selected_facilities)){
            $equipment-> whereIn('facility_id', $this->selected_facilities);
        }

        if ($this->search) {
            $equipment->where('brand_name', 'like', '%' . $this->search . '%');
        }


        return view('livewire.home-page', [
            'equipment' => $equipment->orderBy('id')->cursorPaginate(30, ['*'], 'cursor', 'id'),
            'categories' => Category::get(),
            'facilities' => Facility::get(),

        ]);
    }
}
