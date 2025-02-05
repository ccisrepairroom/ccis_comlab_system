<?php

namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Equipment;
use Illuminate\Http\Request;


#[Title('Equipment Page- CCIS ERMA')]
class HomePage extends Component
{
    public function mount(Request $request)
    {
        $this->equipment = Equipment::where('status', 'working')->get();

    }

    public function render()
    {
        return view('livewire.home-page', [
            'equipment' => $this->equipment,
        ]);
    }
}
