<div class="p-4 bg-white">
    <div class="text-center mb-4">
        <img src="{{ asset('images/ccisheader.png') }}" alt="CCIS Header" class="mx-auto w-full max-w-6xl">
    </div>
    <h3 class="text-lg font-semibold mb-4 mt-6 text-center"> {{$facility->name}}</h3>

    <!-- Facility Details Table -->
    <table class="w-full border-collapse border border-gray-300 text-xs">
        <tbody>
            <tr>
                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100 w-1/4">Facility Image</td>
                <td class="border border-gray-300 px-2 py-1 text-center" colspan="2">
                    @if($facility->main_image)
                        <div class="w-64 h-32 mx-auto overflow-hidden bg-gray-200 rounded-lg">
                            <img src="{{ asset('storage/' . $facility->main_image) }}" alt="Facility Image" 
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <p class="text-gray-500">No Image Available</p>
                    @endif
                </td>           
            </tr>
            <tr>
                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Connection Type</td>
                <td class="border border-gray-300 px-2 py-1">{{ $facility->connection_type ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Cooling Tools</td>
                <td class="border border-gray-300 px-2 py-1">{{ $facility->cooling_tools ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Floor Level</td>
                <td class="border border-gray-300 px-2 py-1">{{ $facility->floor_level ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Building</td>
                <td class="border border-gray-300 px-2 py-1">{{ $facility->building ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-100">Remarks</td>
                <td class="border border-gray-300 px-2 py-1">{{ strip_tags($facility->remarks) ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-lg font-semibold mb-4 mt-6 text-center">Monitoring Records</h3>
    
    @if($monitorings->isEmpty())
        <p class="text-center text-xs">No monitoring records found for this facility.</p>
    @else
        <table class="w-full border-collapse border border-gray-300 text-xs">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">Monitored By</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">Monitored Date</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monitorings->sortByDesc('created_at') as $monitoring)
                <tr class="hover:bg-gray-50 transition duration-200">
                    <td class="border border-gray-300 px-2 py-1">{{ $monitoring->user->name ?? 'Unknown' }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ \Carbon\Carbon::parse($monitoring->monitored_date)->format('F d, Y') }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ strip_tags($monitoring->remarks) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>  
    @endif
</div>

<!-- Buttons Section -->
<div class="flex items-center space-x-2 mt-4 ml-4">
    <a href="{{ route('facility-monitoring-page')}}"   type="button" class="text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:ring-orange-500 font-medium rounded-lg text-xs px-4 py-2" target="_blank">
        Show in Browser
</a>
</div>
