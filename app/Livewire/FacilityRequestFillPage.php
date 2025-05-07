<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BorrowedItems;
use Illuminate\Support\Facades\Auth;

class FacilityRequestFillPage extends Component
{

    public function save()
{
    $user = Auth::user();
    
    // Validate required fields (adjust as necessary)
    $this->validate([
        'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9 .]+$/'],
        'phone_number' => ['required', 'regex:/^[0-9]+$/', 'max:11'],
        'college_department' => 'required|string|max:255',
        'start_date_and_time_of_use' => 'required|date|before:end_date_and_time_of_use',
        'end_date_and_time_of_use' => 'required|date',
        'expected_return_date' => 'required|date|after_or_equal:end_date_and_time_of_use',
        'purpose' => 'required|string',
        'remarks' => 'nullable|string',
    ], [
        // Custom validation messages...
    ]);
    
    // Generate the unique request code
    $latestRequestCode = BorrowedItems::latest()->first();  // Get the most recent request_code
    $nextRequestCode = $latestRequestCode ? str_pad((int)substr($latestRequestCode->request_code, 1) + 1, 5, '0', STR_PAD_LEFT) : '00001';
    
    // Log the generated request code for debugging
    Log::info('Generated Request Code: ' . $nextRequestCode);
    
    // Loop through each item in the request list and save them with the generated request_code
    foreach ($this->requestlist_equipment as $equipment) {
        BorrowedItems::create([
            'user_id' => $user->id,
            'equipment_id' => $equipment['equipment_id'] ?? null, // Nullable equipment_id
            'facility_id' => $equipment['facility_id'] ?? null, // Nullable facility_id
            'request_status' => 'Pending',
            'request_form' => 'Request Form Data', 
            'date' => Carbon::now()->toDateString(),
            'purpose' => $this->purpose,
            'start_date_and_time_of_use' => $this->start_date_and_time_of_use,
            'end_date_and_time_of_use' => $this->end_date_and_time_of_use,
            'expected_return_date' => $this->expected_return_date,
            'received_by' => null, // Add logic if required
            'college_department' => $this->college_department,
            'borrowed_date' => Carbon::now()->toDateString(),
            'remarks' => $this->remarks,
            'status' => '------',
            'borrowed_by' => $this->name,
            'phone_number' => $this->phone_number,
            'request_code' => $nextRequestCode,  // Ensure the request_code is passed here
        ]);
    }
    
    // Clear the request list from the cookie
    RequestManagement::clearRequestListEquipment();
    
    // Optionally, clear the request session data as well
    session()->forget('success_request');
    
    // Save the request data to the session
    session()->put('success_request', [
        'request_code' => $nextRequestCode,
        'name' => $this->name,
        'phone_number' => $this->phone_number,
        'college_department' => $this->college_department,
        'purpose' => $this->purpose,
        'start_date_and_time_of_use' => $this->start_date_and_time_of_use,
        'end_date_and_time_of_use' => $this->end_date_and_time_of_use,
        'expected_return_date' => $this->expected_return_date,
    ]);
    
    // Optionally, you can redirect to a success page or show a message
    session()->flash('message', 'Request submitted successfully!');
    return redirect()->route('success');  // Or any route you want after submission
}


public function render()
{
    $latestRequestCode = BorrowedItems::latest()->first();  
    $nextRequestCode = $latestRequestCode ? str_pad((int)substr($latestRequestCode->request_code, 1) + 1, 5, '0', STR_PAD_LEFT) : '00001';

    return view('livewire.request-fill-page', [
        'nextRequestCode' => $nextRequestCode,  // Make sure to pass this to the view
    ]);
}
}
