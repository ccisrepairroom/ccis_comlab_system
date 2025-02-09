<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Facility;
use App\Models\FacilityMonitoring;
use Barryvdh\DomPDF\Facade\Pdf;


class FacilityMonitoringPage extends Component
{
    public $facility;
    public $monitorings;

    public function mount()
    {
        $this->facility = Facility::with('user')->latest()->first();

        if ($this->facility) {
            $this->monitorings = FacilityMonitoring::where('facility_id', $this->facility->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->monitorings = collect();
        }
    }

    public function render()
    {
        return view('livewire.facility-monitoring-page', [
            'facility' => $this->facility,
            'monitorings' => $this->monitorings
        ]);
    }

    // public function downloadpdf(){
    //     $pdf = Pdf::loadView('livewire.facility-monitoring-page');
    //     return $pdf->stream('facilitymonitoring.pdf');
    // }

    public function downloadpdf()
    {
        $facility = Facility::find(1); // Replace with actual facility fetching logic
        $monitorings = FacilityMonitoring::where('facility_id', $facility->id)->get();

        $pdf = Pdf::loadView('livewire.facility-monitoring-page', compact('facility', 'monitorings'));
        return $pdf->stream('facilitymonitoring.pdf');
    }
}
