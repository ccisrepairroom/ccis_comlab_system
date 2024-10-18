

<div>
    <h3 class="text-lg font-semibold mb-4">Monitoring Records</h3>
    @if($monitorings->isEmpty())
    <p>No monitoring records found for this facility.</p>
    @else
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored Date</th>
                <!--<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>-->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($monitorings->sortByDesc('monitored_date') as $monitoring)
        <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $monitoring->user->name ?? 'Unknown' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($monitoring->monitored_date)->format('F d, Y') }}</td>
                <!--<td class="px-6 py-4 whitespace-nowrap">{{ $monitoring->status }}</td>-->
                <td class="px-6 py-4 whitespace-nowrap">{{ $monitoring->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>