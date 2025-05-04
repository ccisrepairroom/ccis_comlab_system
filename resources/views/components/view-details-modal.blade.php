<!-- View Details Modal -->
<div 
  x-show="open" 
  class="fixed inset-0 flex items-center justify-center backdrop-brightness-75 bg-opacity-50 z-50"
  style="display: none;"
>

  <div class="bg-white p-6 rounded shadow-lg w-full max-w-screen-xl mx-4 overflow-y-auto max-h-[90vh]">
    <h2 class="mb-4 uppercase text-center" style="font-family: 'Times New Roman', Times, serif; font-size: 1.5rem; font-weight: bold;">
    Request for Facility/Equipment Use</h2>    
    <h2 class="text-2xl font-bold mb-4">Request Code: <span x-text="selectedRequest?.request_code"></span></p></h2>
    <p><strong>Borrower:</strong> <span x-text="selectedRequest?.borrowed_by"></span></p>
    <p><strong>College/Department:</strong> <span x-text="selectedRequest?.college_department"></span></p>
    <p><strong>Phone Number:</strong> <span x-text="selectedRequest?.phone_number"></span></p>
    <p><strong>Remarks:</strong> <span x-text="selectedRequest?.remarks"></span></p>

    <div class="mt-4 text-end">
      <button @click="open = false" class="bg-slate-600 text-white px-4 py-2 rounded hover:bg-slate-500">Close</button>
    </div>
  </div>
</div>
