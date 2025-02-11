<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\SuppliesAndMaterials;
use App\Models\StockMonitoring;


class SuppliesAndMaterialsMonitoring extends Component
{
    public $supply;
    public $monitorings;
    
    public function mount(SuppliesAndMaterials $supply)
    {
        $this->supply = $supply->load('user');

        if ($this->supply) {
            $this->monitorings = StockMonitoring::where('supplies_and_materials_id', $this->supply->id)
                ->with(['user', 'suppliesAndMaterials.stockUnit']) // Eager load the stockUnit through suppliesAndMaterials
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->monitorings = collect();
        }
    }


    public function render()
    {
        return view('livewire.supplies-and-materials-monitoring', [
             'supply' => $this->supply,
            'monitorings' => $this->monitorings
        ]);
    }
}
