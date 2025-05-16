<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Equipment;
use App\Models\EquipmentMonitoring;

#[Title('Equipment Monitoring - CCIS ERMA')]
class EquipmentMonitoringPage extends Component
{
    public $equipment;
    public $monitorings;


    public function mount(Equipment $equipment)
    {
        $this->equipment = Equipment::with('user')->find($equipment->id);

        if ($this->equipment) {
            $this->monitorings = EquipmentMonitoring::where('equipment_id', $this->equipment->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->monitorings = collect();
        }
    }
    public function render()
    {
        return view('livewire.equipment-monitoring-page', [
            'equipment' => $this->equipment,
            'monitorings' => $this->monitorings
        ]);
    }
}
