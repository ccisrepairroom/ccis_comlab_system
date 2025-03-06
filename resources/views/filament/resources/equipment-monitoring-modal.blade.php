<div class="p-8 bg-white">
<!-- <div class="text-center mb-4">
        <img src="{{ asset('images/ccisheader.png') }}" alt="CCIS Header" class="mx-auto w-full max-w-6xl">
    </div> -->
    <h3 class="text-lg font-semibold mb-4 text-center">ACER</h3>

    <table class="mx-auto w-full max-w-full border-collapse border border-gray-300 text-xs break-inside-avoid">
    <tbody>
        <!-- First Row:  -->
        <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100 w-1/4">Equipment Image</td>
            <td class="border border-gray-300 px-2 py-1 text-center w-1/4">
                
                    <div class="w-full h-32 mx-auto overflow-hidden bg-white rounded-lg">
                        <img src="" alt="Equipment Image" class="w-full h-full object-contain">
                    </div>
                    <p class="text-gray-500">No Image Available</p>
            </td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100 w-1/4">QR Code</td>
            <td class="border border-gray-300 px-2 py-1 text-center w-1/4">
                
                    <div class="w-full h-32 mx-auto overflow-hidden bg-white rounded-lg">
                        <img src="" alt="Equipment QR Code" class="w-full h-full object-contain">
                    </div>
                    <p class="text-gray-500">No QR Code Available</p>
            </td>        
        </tr>

        <!-- Second Row: -->
        <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Description</td>
            <td class="border border-gray-300 px-2 py-1 text-justify">jdf</td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Serial Number</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"> </td>
        </tr>

        <!-- Third Row: -->
        <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Facility</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Property Number</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>
        </tr>

        <!-- Fourth Row:  -->
        <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Category</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">PO Number</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>
        </tr>

        <!-- Fifth Row: -->
        <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Status</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Unit Number</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>
        </tr>

        <!-- Sixth Row:  -->
        <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Date Acquired</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Control Number</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>
        </tr>

         <!-- Seventh Row:-->
         <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Amount</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Item Number</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>
        </tr>

         <!-- Eight Row:-->
         <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Person Liable</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Supplier</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>
        </tr>

         <!-- 9th Row:-->
         <tr>
            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Remarks</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>

            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Estimated Life</td>
            <td class="border border-gray-300 px-2 py-1 text-justify"></td>
        </tr>
    </tbody>
</table>


<h3 class="text-lg font-semibold mb-4 mt-6 text-center">Monitoring Records</h3>

        @if($monitorings->isEmpty())
            <p class="text-center text-xs">No monitoring records found for this equipment.</p>
        @else
            <table class="mx-auto w-full max-w-full table-fixed border-collapse border border-gray-300 text-xs break-inside-avoid">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Monitored By</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Monitored Date</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Status</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Facility</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/2">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monitorings as $monitoring)
                        <tr class="hover:bg-gray-50 transition duration-200 avoid-break">
                            <td class="border border-gray-300 px-2 py-1 break-words">
                                {{ $monitoring->user->name ?? 'Unknown' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                {{ \Carbon\Carbon::parse($monitoring->monitored_date)->format('F d, Y') }}
                            </td>
                            <td class="border border-gray-300 px-2 py-1 break-words">
                                {{ $monitoring->status ?? 'Unknown' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-1 break-words">
                                {{ $monitoring->facility->name ?? 'Unknown' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-1 break-words text-justify">
                                {{ strip_tags($monitoring->remarks) }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>  
        @endif 
    </div>
</div> 

