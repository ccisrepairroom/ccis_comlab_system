
<div>
    @if($monitorings->isEmpty())
        <p>No stock records found for this equipment.</p>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored By</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Monitored</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Quantity</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Added</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Quantity</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($monitorings as $stock)
                <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $stock->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $stock->monitored_date ?? 'N/A' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $stock->facility->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $stock->current_quantity }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $stock->quantity_to_add }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $stock->new_quantity }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $stock->supplier }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
