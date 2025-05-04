<?php

namespace App\Livewire;

use App\Models\BorrowedItems;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\RequestManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


#[Title('Request - CCIS ERMA')]
class RequestFillPage extends Component
{
    public $requestlist_equipment = [];
    public $total_request;
    public $category_totals = [];
    public $date_requested;
    public $name;
    public $phone_number;
    public $college_department;
    public $expected_return_date;
    public $start_date;
    public $end_date;
    public $purpose;
    public $remarks;

    public function mount()
    {
        $this->requestlist_equipment = RequestManagement::getRequestListEquipmentFromCookie();
        $this->total_request = RequestManagement::calculateTotalRequestedEquipment($this->requestlist_equipment);
        $this->category_totals = RequestManagement::calculateTotalByCategory();

        // If no items are in the cart, redirect
        if (count($this->requestlist_equipment) == 0) {
            return redirect('equipment');
        }
    }

    public function save()
{
    $user = Auth::user();
    
    // Validate required fields (adjust as necessary)
    $this->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15',
        'college_department' => 'required|string|max:255',
        'expected_return_date' => 'required|date',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'purpose' => 'required|string',
        'remarks' => 'nullable|string',
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
            'equipment_id' => $equipment['equipment_id'],  // Assuming equipment_id is passed in the request
            'request_status' => 'Pending',
            'request_form' => 'Request Form Data', // Optional, add data if needed
            'date' => Carbon::now()->toDateString(),
            'purpose' => $this->purpose,
            'start_date_and_time_of_use' => $this->start_date,
            'end_date_and_time_of_use' => $this->end_date,
            'expected_return_date' => $this->expected_return_date,
            'received_by' => null, // Add logic if required
            'college_department' => $this->college_department,
            'borrowed_date' => Carbon::now()->toDateString(),
            'remarks' => $this->remarks,
            'status' => 'Unreturned',
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
        'start_date' => $this->start_date,
        'end_date' => $this->end_date,
        'expected_return_date' => $this->expected_return_date,
    ]);
    
    // Optionally, you can redirect to a success page or show a message
    session()->flash('message', 'Request submitted successfully!');
    return redirect()->route('success');  // Or any route you want after submission
}

    

public function render()
{
    // Generate the request code here as well
    $latestRequestCode = BorrowedItems::latest()->first();  // Get the most recent request_code
    $nextRequestCode = $latestRequestCode ? str_pad((int)substr($latestRequestCode->request_code, 1) + 1, 5, '0', STR_PAD_LEFT) : '00001';

    return view('livewire.request-fill-page', [
        'requestlist_equipment' => $this->requestlist_equipment,
        'nextRequestCode' => $nextRequestCode,  // Pass the request_code to the view
    ]);
}

}
