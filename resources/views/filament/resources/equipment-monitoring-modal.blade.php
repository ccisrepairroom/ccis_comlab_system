<div class="p-8 bg-white">
    <h3 class="text-lg font-semibold mb-4 text-center">Monitoring Records</h3>
    
    @if($monitorings->isEmpty())
        <p class="text-center">No monitoring records found for this equipment.</p>
    @else
        <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored By</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored Date</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($monitorings->sortByDesc('created_at') as $monitoring)
                <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="border border-gray-300 px-6 py-4">{{ $monitoring->user->name ?? 'Unknown' }}</td>
                        <td class="border border-gray-300 px-6 py-4">{{ \Carbon\Carbon::parse($monitoring->monitored_date)->format('F d, Y') }}</td>
                        <td class="border border-gray-300 px-6 py-4 font-bold"
                            style="color: {{ $monitoring->status === 'working' ? 'green' : 
                                             ($monitoring->status === 'for repair' ? 'orange' : 
                                             ($monitoring->status === 'for replacement' ? 'blue' : 
                                             ($monitoring->status === 'lost' ? 'red' : 
                                             ($monitoring->status === 'for disposal' ? 'blue' : 
                                             ($monitoring->status === 'disposed' ? 'red' : 
                                             ($monitoring->status === 'borrowed' ? 'indigo' : 'gray')))))) }};">
                            {{ ucwords(strtolower($monitoring->status)) }}                        </td>
                        
                        <td class="border border-gray-300 px-6 py-4">{{ $monitoring->facility->name ?? 'Unknown' }}</td>
                        <td class="border border-gray-300 px-6 py-4">{{ $monitoring->remarks }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
