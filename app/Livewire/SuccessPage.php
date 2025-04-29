<?php

namespace App\Livewire;

namespace App\Livewire;

use Livewire\Component;

class SuccessPage extends Component
{
    public $requestDetails;

    public function mount()
    {
        // Retrieve the request data from the session
        $this->requestDetails = session()->get('success_request', null);

        // Optional: Check if data exists in the session
        if (!$this->requestDetails) {
            // Handle the case where session data is missing
            session()->flash('error', 'No request data found.');
            return redirect()->route('equipment'); // or any fallback route
        }
    }

    public function render()
    {
        return view('livewire.success-page', [
            'requestDetails' => $this->requestDetails
        ]);
    }
}

