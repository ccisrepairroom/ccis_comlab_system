<div 
    x-data="{ open: false, selectedRequest: null }"
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
                <!-- <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Serial Number</th> -->
                <!-- <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Property Number</th> -->
                <!-- <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Purpose</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Remarks</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Date and Time of Use</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Date Returned</th> -->

                <!-- <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Purpose</th> -->

                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($borrowed_items as $request)
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
                <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 ">{{$request->equipment->serial_no}}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 ">{{$request->equipment->property_no}}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 ">{{$request->purpose}}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 ">{{$request->remarks}}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                    {{ \Carbon\Carbon::parse($request->start_date_and_time_of_use)->format('m-d-Y h:i A') }} to 
                    {{ \Carbon\Carbon::parse($request->end_date_and_time_of_use)->format('m-d-Y h:i A') }}
                </td> -->
                <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{optional($request->returned_date)->format('m-d-Y')}}</td> -->
                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                   <a 
                    @click="open = true; selectedRequest = {{ json_encode([
                      'remarks' => $request->remarks,
                      'request_code' => $request->request_code,
                      'borrowed_by' => $request->borrowed_by,
                      'college_department' => $request->college_department,
                      'phone_number' => $request->phone_number,
                      'college_department' => $request->college_department,
                    ]) }}"
                    class="cursor-pointer bg-slate-600 text-white py-2 px-4 rounded-md hover:bg-slate-500"
                  >
                      View Details
                  </a>

                                  
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
      {{$borrowed_items->links()}}
    </div>
  </div>

   <!-- Modal -->
<div 
  x-show="open"
  style="display: none"
  class="fixed inset-0 flex items-start justify-center backdrop-brightness-75 z-50"
  x-cloak
>

  <div class="relative p-4 w-full max-w-[300mm] max-h-[400mm] top-0">
    <div class="my-10 bg-white p-6 rounded shadow-lg max-h-full overflow-y-auto">
      <h3 class="text-xl font-semibold" x-text="'Request Code: ' + (selectedRequest?.request_code ?? '')"></h3>
      <p class="text-gray-600 text-sm pt-5">
        <strong>Borrower:</strong> <span x-text="selectedRequest?.borrowed_by"></span><br>
        <strong>College/Department:</strong> <span x-text="selectedRequest?.college_department"></span><br>
        <strong>Phone Number:</strong> <span x-text="selectedRequest?.phone_number"></span><br></p>

<table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                </tr>
            </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">ob_end_clean</td>
                        <td class="px-6 py-4 whitespace-nowrap"></td>
                        <td class="px-6 py-4 whitespace-nowrap"></td>
                        <td class="px-6 py-4 whitespace-nowrap"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                           
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"></td>
                        <td class="px-6 py-4 whitespace-nowrap"></td>
                        <td class="px-6 py-4 whitespace-nowrap"></td>
                        <td class="px-6 py-4 whitespace-nowrap"></td>
                    </tr>
            </tbody>
        </table>

        
        <strong>Remarks:</strong><br>
        <span x-text="selectedRequest?.remarks"></span>
      </p>  
      <div class="flex justify-end">
        <button class="mt-4 px-4 py-2 bg-orange-500 text-white rounded" @click="open = false">Close</button>
      </div> 
    </div>
  </div>
</div>
