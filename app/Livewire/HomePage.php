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

    #[Url]
    public $sort='latest';



    

   

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
            $equipment->where(function ($query) {
                $query->where('brand_name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('facility', function ($facilityQuery) { 
                        $facilityQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                      ->orWhereHas('category', function ($categoryQuery) { 
                            $categoryQuery->where('description', 'like', '%' . $this->search . '%');
                        });  
                    
            });
        }

        switch ($this->sort) {
            case 'latest':
                $equipment->latest(); // Orders by `created_at` DESC
                break;
    
            case 'facility':
                $equipment->orderBy('facility_id')->orderBy('id', 'desc'); // Sort by facility then newest
                break;
    
            case 'category':
                $equipment->orderBy('category_id')->orderBy('id', 'desc'); // Sort by category then newest
                break;
        }
        
        $noEquipmentFound = $equipment->get()->isEmpty();

        return view('livewire.home-page', [
            'equipment' => $equipment->orderBy('id')->cursorPaginate(15, ['*'], 'cursor', 'id'),
            'categories' => Category::whereHas('equipment')->get(),
            'facilities' => Facility::whereHas('equipment')->get(),
            'noEquipmentFound' => $noEquipmentFound,



        ]);
    }
}
