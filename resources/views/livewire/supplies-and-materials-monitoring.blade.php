<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
<div class="w-[297mm] min-h-[210mm] mx-auto bg-white shadow-lg p-8 overflow-visible">    
    <div class="p-4 bg-white">
        <div class="text-center mb-4">
            <img src="{{ asset('images/ccisheader.png') }}" alt="CCIS Header" class="mx-auto w-full max-w-full">
        </div>

        <h3 class="text-lg font-semibold mb-4 mt-20 text-center">
            {{ $supply->item ?? 'Unknown Item' }}
        </h3>

        <table class="mx-auto w-full max-w-full border-collapse border border-gray-300 text-xs break-inside-avoid">
    <tbody>
        <!-- First Row:  -->
        <tr>
        <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100 w-1/4">Item Image</td>
                    <td class="border border-gray-300 px-2 py-1 text-center" colspan="2">
                        @if($supply?->main_image)
                            <div class="w-64 h-32 mx-auto overflow-hidden bg-gray-200 rounded-lg">
                                <img src="{{ asset('storage/' . $supply->main_image) }}" alt="item Image" class="w-full h-full object-contain bg-white">
                            </div>
                        @else
                            <p class="text-gray-500">No Image Available</p>
                        @endif
                    </td>           
                </tr>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Description</td>
                    <td class="border border-gray-300 px-2 py-1 text-justify">{{ $supply?->description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Category</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $supply?->category->description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Facility</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $supply?->facility->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Quantity</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $supply?->quantity ?? 'N/A'  }} {{ $supply?->stockunit->description ?? 'N/A'  }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Restocking Point</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $supply?->stocking_point ?? 'N/A' }} {{ $supply?->stockunit->description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Date Acquired</td>
                    <td class="border border-gray-300 px-2 py-1">{{ strip_tags($supply?->date_acquired) ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Supplier</td>
                    <td class="border border-gray-300 px-2 py-1">{{ strip_tags($supply?->supplier) ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Remarks</td>
                    <td class="border border-gray-300 px-2 py-1">{{ strip_tags($supply?->remarks) ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>


         <h3 class="text-lg font-semibold mb-4 mt-6 text-center">Monitoring Records</h3>

        @if($monitorings->isEmpty())
            <p class="text-center text-xs">No monitoring records found for this item.</p>
        @else
            <table class="mx-auto w-full max-w-full table-fixed border-collapse border border-gray-300 text-xs break-inside-avoid">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Restocked By</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Restocked On</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Facility</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Supplier</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/2">Current Quantity</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">Quantity Added</th>
                        <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider w-1/4">New Quantity</th>


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
                                {{ $monitoring->facility->name ?? 'Unknown' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-1 break-words">
                                {{ $monitoring->supplier ?? 'Unknown' }}
                            </td>
                            <td class="border border-gray-300 px-2 py-1 break-words">
                                {{ $monitoring->current_quantity ?? 'Unknown' }} {{ $monitoring->suppliesAndMaterials->stockUnit->description ?? '' }}

                            </td>
                            <td class="border border-gray-300 px-2 py-1 break-words">
                                {{ $monitoring->quantity_to_add ?? 'Unknown' }} {{ $monitoring->suppliesAndMaterials->stockUnit->description ?? '' }}

                            </td>
                            <td class="border border-gray-300 px-2 py-1 break-words">
                                {{ $monitoring->new_quantity ?? 'Unknown' }} {{ $monitoring->suppliesAndMaterials->stockUnit->description ?? '' }}

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
