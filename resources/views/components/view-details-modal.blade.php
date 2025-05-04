
<!-- View Details Modal -->
<div 
  x-show="open" 
  class="fixed inset-0 flex items-center justify-center backdrop-brightness-75 bg-opacity-50 z-50"
  style="display: none;"
>


  <div 
    class="bg-white p-6 rounded shadow-lg w-full max-w-screen-xl mx-4 overflow-y-auto max-h-[90vh]" 
    style="font-family: 'Cambria', sans-serif;"
  >
  <!-- <div class="text-center mb-4">
        <img src="{{ asset('images/ccisheader.png') }}" alt="CCIS Header" class="mx-auto w-full max-w-6xl">
    </div>  -->
    <h2 class="text-2xl font-bold mb-20 text-center uppercase" style="font-size: 1.5rem; font-weight: bold;">
      Request for Facility/Equipment Use
    </h2>

    <div class="flex flex-col gap-4 md:flex-row md:gap-8">
      <!-- Left: Borrower Info -->
      <div class="w-full md:w-1/2">
        <p><strong>Request Code:</strong> <span x-text="selectedRequest?.request_code"></span></p>
        <p><strong>Date:</strong> <span x-text="selectedRequest?.created_at"></span></p>
        <p><strong>Borrower:</strong> <span x-text="selectedRequest?.borrowed_by"></span></p>
        <p><strong>College/Department:</strong> <span x-text="selectedRequest?.college_department"></span></p>
        <p><strong>Phone Number:</strong> <span x-text="selectedRequest?.phone_number"></span></p>
        <p><strong>Purpose:</strong> <span x-text="selectedRequest?.purpose"></span></p>
        <p><strong>Date and Time of Use:</strong>
                <span x-text="selectedRequest?.start_date_and_time_of_use + ' - ' + selectedRequest?.end_date_and_time_of_use"></span>
        </p>
        <p><strong>Expected Return Date:</strong> <span x-text="selectedRequest?.expected_return_date"></span></p>
        
        <p class="pt-7">Equipment/Facility Requested:</p> 
       <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">Equipment </th>
                    <th class="border px-4 py-2 text-left">Facility</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example row, replace with dynamic data -->
                <tr>
                    <td class="border px-4 py-2" 
                        x-text="(selectedRequest?.equipment_brand_name || 'N/A') + ' (' + (selectedRequest?.category_description || 'N/A') + ')'">
                    </td>
                    <td class="border px-4 py-2" x-text="selectedRequest?.facility_name"></td>
                </tr>
                <!-- Add more rows dynamically -->
            </tbody>
        </table>

        <p><strong>Remarks:</strong> <span x-text="selectedRequest?.remarks"></span></p>
      </div>

      <!-- Right: Terms and Conditions -->
      <div class="w-full md:w-1/2 border-l border-gray-300 pl-4 outline" >
        <h2 class="text-xl font-semibold mb-2 text-left">Terms and Conditions</h2>
        <p class="text-sm mb-2 pr-1">
            I hereby acknowledge the following terms and conditions in relation to using a facility or borrowing equipment from the computer laboratory:
        </p>
        <ol class="list-decimal list-inside space-y-2 text-sm pr-1 pl-4">
          <li>I understand that the facility/equipment is the
                property of CSU and is being borrowed for
                educational or work-related purposes only.
          </li>
          <li>I agree to return the facility/equipment in the same
                condition as it was used/borrowed, and I will be held
                responsible for any damages or loss incurred during
                the period of using/borrowing.
          </li>
          <li>I will not use the facility/equipment for any illegal
                activities, including but not limited to unauthorized
                downloading of copyrighted material or engaging in
                any form of cybercrime.
          </li>
          <li>I understand that I am responsible for the
                facility’s/equipment's safekeeping and security while
                it is in my possession, both on and off-campus.
          </li>
          <li>If I choose to take the equipment outside the
                campus, I release CSU from any liability related to
                loss, damage, or theft that may occur while the
                equipment is off campus.
          </li>
                        
        </ol>
        <p class="text-sm mb-2 pr-1">
           I have read and understood the terms and conditions outlined
            in this waiver, and I agree to abide by them during the entire
            duration of the equipment/facility borrowing period.
        </p>
      </div>
    </div>

    <div class="mt-4 text-end">
      <button @click="open = false" class="bg-slate-600 text-white px-4 py-2 rounded hover:bg-slate-500">Close</button>
    </div>
  </div>
</div>
