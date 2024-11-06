<div>
    @if($equipment->isEmpty())
        <p>No equipment records found for this facility.</p>
    @else
        @php
            // Sort equipment collection by 'unit_no' in ascending order
            $equipment = $equipment->sortBy('unit_no');
        @endphp

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
                @foreach($equipment as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->po_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->unit_no }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->brand_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap"
                            style="color: {{ $item->status === 'Working' ? 'green' : 
                                             ($item->status === 'For Repair' ? 'orange' : 
                                             ($item->status === 'For Replacement' ? 'blue' : 
                                             ($item->status === 'Lost' ? 'red' : 
                                             ($item->status === 'For Disposal' ? 'blue' : 
                                             ($item->status === 'Disposed' ? 'red' : 
                                             ($item->status === 'Borrowed' ? 'indigo' : 'gray')))))) }};">
                            {{ $item->status }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ optional($item->category)->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->property_no }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->control_no }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->serial_no }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Showing {{ $equipment->count() }} results
            </div>
        </div>
    @endif
</div>

<style>
    .pagination-links nav p {
        display: none;
    }
</style>
