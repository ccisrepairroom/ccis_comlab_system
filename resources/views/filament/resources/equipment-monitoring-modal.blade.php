<div>
    @if($monitorings->isEmpty())
        <p>No monitoring records found for this equipment.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored By</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($monitorings as $monitoring)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $monitoring->user->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($monitoring->monitored_date)->format('F d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap"
                            style="color: {{ $monitoring->status === 'Working' ? 'green' : 
                                             ($monitoring->status === 'For Repair' ? 'orange' : 
                                             ($monitoring->status === 'For Replacement' ? 'blue' : 
                                             ($monitoring->status === 'Lost' ? 'red' : 
                                             ($monitoring->status === 'For Disposal' ? 'blue' : 
                                             ($monitoring->status === 'Disposed' ? 'red' : 
                                             ($monitoring->status === 'Borrowed' ? 'indigo' : 'gray')))))) }};">
                            {{ $monitoring->status }}
                        </td>
                        
                        <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $monitoring->facility->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $monitoring->remarks }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
