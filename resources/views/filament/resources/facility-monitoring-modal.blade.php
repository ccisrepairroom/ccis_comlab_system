<div class="p-8 bg-white">
<div class="text-center mb-4">
        <img src="{{ asset('images/ccisheader.png') }}" alt="CCIS Header" class="mx-auto w-full max-w-6xl">
    </div>
    <h3 class="text-lg font-semibold mb-4 mt-10 text-center"> {{$facility->name}}</h3>

    <!-- Facility Details Table -->
    <table class="w-full border-collapse border border-gray-300 mb-6">
        <tbody>
            <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">Facility Image</td>
            <td class="border border-gray-300 px-4 py-2 text-center" colspan="2">
                @if($facility->main_image)
                    <div class="w-128 h-64 mx-auto overflow-hidden bg-gray-200 rounded-lg">
                        <img src="{{ asset('storage/' . $facility->main_image) }}" alt="Facility Image" 
                            class="w-full h-full object-cover">
                    </div>
                @else
                    <p class="text-gray-500">No Image Available</p>
                @endif
                </td>           
            </tr>
            <tr class="border border-gray-300">
                <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">Connection Type</td>
                <td class="border border-gray-300 px-4 py-2">{{ $facility->connection_type ?? 'N/A' }}</td>
            </tr>
            <tr class="border border-gray-300">
                <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">Cooling Tools</td>
                <td class="border border-gray-300 px-4 py-2">{{ $facility->cooling_tools ?? 'N/A' }}</td>
            </tr>
            <tr class="border border-gray-300">
                <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">Floor Level</td>
                <td class="border border-gray-300 px-4 py-2">{{ $facility->floor_level ?? 'N/A' }}</td>
            </tr>
            <tr class="border border-gray-300">
                <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">Building</td>
                <td class="border border-gray-300 px-4 py-2">{{ $facility->building ?? 'N/A' }}</td>
            </tr>
            <tr class="border border-gray-300">
                <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">Remarks</td>
                <td class="border border-gray-300 px-4 py-2">{{ strip_tags($facility->remarks) ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>

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
<div class="flex items-center space-x-1 ml-10">
    <button type="button" class="focus:outline-none text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:ring-orange-500 font-medium rounded-lg text-sm px-5  py-2.5 me-2 mb-2">
        Print
    </button>
    <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Download</button>

</div>

</div>


