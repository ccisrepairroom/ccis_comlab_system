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

    public function mount(Facility $facility)
    {
        $this->facility = Facility::with('user')->find($facility->id);

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

    public function downloadpdf()
    {
        if (!$this->facility) {
            abort(404, 'Facility not found.');
        }

        $pdf = Pdf::loadView('livewire.facility-monitoring-page', [
            'facility' => $this->facility,
            'monitorings' => $this->monitorings
        ]);

        return $pdf->stream('facilitymonitoring.pdf');
    }
}
