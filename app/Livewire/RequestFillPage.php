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
    public $start_date_and_time_of_use;
    public $end_date_and_time_of_use;
    public $purpose;
    public $remarks;
    public $status;

    public $request_status;


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
        'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9 .]+$/'],
        'phone_number' => ['required', 'regex:/^[0-9]+$/', 'max:11'],
        'college_department' => 'required|string|max:255',
        'start_date_and_time_of_use' => 'required|date|before:end_date_and_time_of_use',
        'end_date_and_time_of_use' => 'required|date',
        'expected_return_date' => 'required|date|after_or_equal:end_date_and_time_of_use',
        'purpose' => 'required|string',
        'remarks' => 'nullable|string',
    ], [
        'name.required' => 'The name field is required.',
        'name.regex' => 'The name may only contain letters, numbers, spaces, and dots.',
        'name.max' => 'The name may not be greater than 255 characters.',
        'phone_number.required' => 'The phone number field is required.',
        'phone_number.regex' => 'The phone number may only contain numbers.',
        'phone_number.max' => 'The phone number may not be greater than 11 digits.',
        'college_department.required' => 'The college department field is required.',
        'college_department.string' => 'The college department must be a valid string.',
        'college_department.max' => 'The college department may not be greater than 255 characters.',
        'start_date.required' => 'The start date field is required.',
        'start_date_and_time_of_use.date' => 'The start date must be a valid date.',
        'start_date_and_time_of_use.before' => 'The start date must be earlier than the end date.',
        'end_date_and_time_of_use.required' => 'The end date field is required.',
        'end_date_and_time_of_use.date' => 'The end date must be a valid date.',
        'expected_return_date.required' => 'The expected return date field is required.',
        'expected_return_date.date' => 'The expected return date must be a valid date.',
        'expected_return_date.after_or_equal' => 'The expected return date must be the same or after the end date.',
        'purpose.required' => 'The purpose field is required.',
        'purpose.string' => 'The purpose must be a valid string.',
        'remarks.string' => 'The remarks must be a valid string.',
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
            'equipment_id' => $equipment['equipment_id'], 
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
    // Generate the request code here as well
    $latestRequestCode = BorrowedItems::latest()->first();  // Get the most recent request_code
    $nextRequestCode = $latestRequestCode ? str_pad((int)substr($latestRequestCode->request_code, 1) + 1, 5, '0', STR_PAD_LEFT) : '00001';

    return view('livewire.request-fill-page', [
        'requestlist_equipment' => $this->requestlist_equipment,
        'nextRequestCode' => $nextRequestCode,  
    ]);
}

}
