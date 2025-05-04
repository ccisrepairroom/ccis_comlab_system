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
    <h2 class="text-2xl font-bold mb-20 text-center uppercase">
      Request for Facility/Equipment Use
    </h2>

    <div class="flex flex-col gap-4 md:flex-row md:gap-8">
      
      <!-- Left Column: Borrower Info & Table -->
      <div class="w-full md:w-1/2">
        <p><strong>Request Code:</strong> <span x-text="selectedRequest?.request_code || 'N/A'"></span></p>
        <p><strong>Date:</strong> <span x-text="selectedRequest?.created_at || 'N/A'"></span></p>
        <p><strong>Borrower:</strong> <span x-text="selectedRequest?.borrowed_by || 'N/A'"></span></p>
        <p><strong>College/Department:</strong> <span x-text="selectedRequest?.college_department || 'N/A'"></span></p>
        <p><strong>Phone Number:</strong> <span x-text="selectedRequest?.phone_number || 'N/A'"></span></p>
        <p><strong>Purpose:</strong> <span x-text="selectedRequest?.purpose || 'N/A'"></span></p>
        <p><strong>Date and Time of Use:</strong>
          <span x-text="(selectedRequest?.start_date_and_time_of_use || 'N/A') + ' - ' + (selectedRequest?.end_date_and_time_of_use || 'N/A')"></span>
        </p>
        <p><strong>Expected Return Date:</strong> <span x-text="selectedRequest?.expected_return_date || 'N/A'"></span></p>
        <p x-show="selectedRequest?.returned_date" class="text-sm">
          <strong>Date Returned:</strong> <span x-text="selectedRequest?.returned_date"></span>
        </p>

        <p x-show="selectedRequest?.received_by" class="text-sm">
          <strong>Received By:</strong> <span x-text="selectedRequest?.received_by"></span>
        </p>

        <!-- Equipment/Facility Requested -->
        <p class="pt-7 font-semibold">Equipment/Facility Requested:</p>
        <table class="w-full table-fixed border border-gray-300 text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="border px-4 py-2 text-left">Equipment</th>
              <th class="border px-4 py-2 text-center">Facility</th>
              <th class="border px-4 py-2 text-left">Request (Return Status)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="border px-4 py-2 w-full md:w-1/3 break-words">
                <div x-text="(selectedRequest?.equipment_brand_name || 'N/A') + ' (' + (selectedRequest?.category_description || 'N/A') + ')'"></div>
                <div class="text-sm text-gray-600">
                  <div class="flex flex-wrap gap-2">
                    <span class="block w-full sm:inline sm:w-auto">
                      Serial No.: <span x-text="selectedRequest?.equipment_serial_no || 'N/A'"></span>
                    </span>
                    <span class="block w-full sm:inline sm:w-auto">
                      Property No.: <span x-text="selectedRequest?.equipment_property_no || 'N/A'"></span>
                    </span>
                  </div>
                </div>
              </td>
              <td class="border px-4 py-2 w-full md:w-1/3 text-center" x-text="selectedRequest?.facility_name || 'N/A'"></td>
              <td class="border px-4 py-2 w-full md:w-1/3">
                <div x-text="((selectedRequest?.request_status || 'N/A') + ' (' + (selectedRequest?.status || 'N/A') + ')').replace(/^./, c => c.toUpperCase())"></div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Remarks -->
        <div x-show="selectedRequest?.remarks" class="mt-4">
          <p><strong>Remarks:</strong></p>
          <div 
            class="border border-yellow-500 bg-yellow-100 text-yellow-900 p-2 rounded"
            x-text="selectedRequest?.remarks.replace(/^./, c => c.toUpperCase())">
          </div>
        </div>
      </div>

      <!-- Right Column: Terms and Conditions -->
      <div class="w-full md:w-1/2 border-2 border-black rounded-md p-4">
        <h2 class="text-xl font-semibold mb-2 text-left">Terms and Conditions</h2>
        <p class="text-sm mb-2 pr-1">
          I hereby acknowledge the following terms and conditions in relation to using a facility or borrowing equipment from the computer laboratory:
        </p>
        <ol class="list-decimal list-inside space-y-2 text-sm pr-1 pl-4">
          <li>I understand that the facility/equipment is the property of CSU and is being borrowed for educational or work-related purposes only.</li>
          <li>I agree to return the facility/equipment in the same condition as it was used/borrowed, and I will be held responsible for any damages or loss incurred during the period of using/borrowing.</li>
          <li>I will not use the facility/equipment for any illegal activities, including but not limited to unauthorized downloading of copyrighted material or engaging in any form of cybercrime.</li>
          <li>I understand that I am responsible for the facility’s/equipment's safekeeping and security while it is in my possession, both on and off-campus.</li>
          <li>If I choose to take the equipment outside the campus, I release CSU from any liability related to loss, damage, or theft that may occur while the equipment is off campus.</li>
        </ol>
        <p class="text-sm mb-2 pr-1">
          I have read and understood the terms and conditions outlined in this waiver, and I agree to abide by them during the entire duration of the equipment/facility borrowing period.
        </p>
      </div>
    </div>

    <!-- Close Button -->
    <div class="mt-4 text-end">
      <button 
        @click="open = false" 
        class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
        Close
      </button>
    </div>
  </div>
</div>
