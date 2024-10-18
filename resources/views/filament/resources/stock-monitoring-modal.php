{{-- resources/views/filament/resources/stock-monitoring-modal.blade.php --}}

<div>
    <h2>Stock Monitoring Records</h2>
    
    @if($stockHistory->isEmpty())
        <p>No stock records found for this equipment.</p>
    @else
        <table class="min-w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Monitored By</th>
                    <th class="border border-gray-300 px-4 py-2">Date Monitored</th>
                    <th class="border border-gray-300 px-4 py-2">No. of Stocks</th>
                    <th class="border border-gray-300 px-4 py-2">Stocks Deducted</th>
                    <th class="border border-gray-300 px-4 py-2">Stocks Left</th>
                    <th class="border border-gray-300 px-4 py-2">Added At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockHistory as $stock)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $stock->user->name ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $stock->deducted_at }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $stock->no_of_stocks }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $stock->no_of_stocks_deducted }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $stock->stocks_left }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $stock->added_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
