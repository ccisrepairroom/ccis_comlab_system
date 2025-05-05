<div 
    x-data="{ 
        open: false, 
        selectedRequest: null,
        formatDateTime(datetime) {
            if (!datetime) return 'N/A';
            const options = {
                year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit',
                hour12: true,
            };
            return new Date(datetime).toLocaleString('en-US', options);
        }
    }"
    class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto"
>

  <h1 class="text-4xl font-bold text-slate-500">My Requests</h1>
  <div class="flex flex-col bg-white p-5 rounded mt-4 shadow-lg">
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
              <tr>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Request Code</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Date Requested</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Request Status</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Return Status</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Borrower</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Item Name</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Facility</th>
                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($borrowed_items as $request)
                @php
                $status = '';
                $request_status = '';

                if(strtolower($request->status) == 'returned'){
                  $status ='<span class="bg-green-500 py-1 px-3 rounded text-white shadow">Returned</span>'; 
                 }
                if(strtolower($request->status) == 'unreturned'){
                  $status ='<span class="bg-red-500 py-1 px-3 rounded text-white shadow">Unreturned</span>'; 
                 }
                 
                if(strtolower($request->request_status) == 'pending'){
                  $request_status ='<span class="bg-blue-500 py-1 px-3 rounded text-white shadow">Pending</span>'; 
                 }
                if(strtolower($request->request_status) == 'approved'){
                  $request_status ='<span class="bg-green-500 py-1 px-3 rounded text-white shadow">Approved</span>'; 
                 }
                if(strtolower($request->request_status) == 'rejected'){
                  $request_status ='<span class="bg-red-500 py-1 px-3 rounded text-white shadow">Rejected</span>'; 
                 }
                @endphp
                  <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-900 dark:even:bg-slate-800" wire:key='{{$request->id}}'>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{$request->request_code}}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$request->created_at->format('m-d-Y')}}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $request_status !!}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $status !!}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 capitalize">{{$request->borrowed_by}}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 uppercase">{{$request->equipment->brand_name}}- {{$request->equipment->category->description}}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 ">{{$request->equipment->facility->name}}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                      <a 
                      @click.prevent="open = true; selectedRequest = {{ 
                          json_encode([
                            'request_code' => $request->request_code,
                            'created_at' => $request->created_at->format('F d, Y h:i A'),
                            'borrowed_by' => $request->borrowed_by,
                            'college_department' => $request->college_department,
                            'phone_number' => $request->phone_number,
                            'purpose' => $request->purpose,
                            'start_date_and_time_of_use' => $request->start_date_and_time_of_use,
                            'end_date_and_time_of_use' => $request->end_date_and_time_of_use,
                            'expected_return_date' => $request->expected_return_date,
                            'returned_date' => $request->returned_date,
                            'received_by' => $request->received_by,
                            'remarks' => $request->remarks,
                            'equipment_brand_name' => $request->equipment->brand_name,
                            'equipment_serial_no' => $request->equipment->serial_no,
                            'equipment_property_no' => $request->equipment->property_no,
                            'category_description' => $request->equipment->category->description,
                            'facility_name' => $request->equipment->facility->name,
                            'request_status' => $request->request_status,
                            'status' => $request->status,
                          ]) 
                        }}"

                        class="cursor-pointer bg-orange-500 text-white py-2 px-4 rounded-md hover:bg-orange-600"
                      >
                        View Details
                      </a>               
                    </td>
                  </tr>
              @empty
                <tr>
                  <td colspan="7" class="px-6 py-4 text-center text-sm font-medium text-gray-800 dark:text-gray-200">No requests have been made."</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      {{$borrowed_items->links()}}
    </div>
  </div>

  <x-view-details-modal :showVar="'open'" :dataVar="'selectedRequest'" />
</div>
