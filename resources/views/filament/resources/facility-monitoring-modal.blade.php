<div id="printableModal" class="p-8 bg-white">
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

    <button onclick="printModal()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Print</button>
</div>

<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 1in;
        }
        body * {
            visibility: hidden;
        }
        #printableModal, #printableModal * {
            visibility: visible;
        }
        #printableModal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            font-size: 12px;
            padding: 8px;
            border: 1px solid black;
        }
    }
</style>

<script>
    function printModal() {
        window.print();
    }
</script>
