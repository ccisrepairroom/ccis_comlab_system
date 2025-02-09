<div class="p-8 bg-white">
<div class="text-center mb-4">
        <img src="{{ asset('images/ccisheader.png') }}" alt="CCIS Header" class="mx-auto w-full max-w-6xl">
    </div>
    <h3 class="text-lg font-semibold mb-4 text-center">Monitoring Records</h3>
    
    @if($monitorings->isEmpty())
        <p class="text-center">No monitoring records found for this facility.</p>
    @else
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored By</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitored Date</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                </tr>
            </thead>
            <tbody>
            @foreach($monitorings->sortByDesc('created_at') as $monitoring)
            <tr class="hover:bg-gray-50 transition duration-200">
                <td class="border border-gray-300 px-6 py-4">{{ $monitoring->user->name ?? 'Unknown' }}</td>
                        <td class="border border-gray-300 px-6 py-4">{{ \Carbon\Carbon::parse($monitoring->monitored_date)->format('F d, Y') }}</td>
                        <td class="border border-gray-300 px-6 py-4">{{ strip_tags($monitoring->remarks) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>