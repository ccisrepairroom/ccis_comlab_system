<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto min-h-screen flex flex-col ">
  <div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold text-orange-500 mb-4">Requests List</h1>
    <div class="flex flex-col md:flex-row gap-4">
      <div class="md:w-3/4">
        <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
          <!-- Added min-w-full and block table wrapper -->
          <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] sm:min-w-full">
              <thead>
                <tr>
                  <th class="text-left font-semibold p-2 whitespace-nowrap">Items</th>
                  <th class="text-left font-semibold p-2 whitespace-nowrap">Category</th>
                  <th class="text-left font-semibold p-2 whitespace-nowrap">Facility</th>
                  <th class="text-left font-semibold p-2 whitespace-nowrap">Serial No.</th>
                  <th class="text-left font-semibold p-2 whitespace-nowrap">Property No.</th>
                  <th class="text-left font-semibold p-2 whitespace-nowrap">Remove</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($requestlist_equipment as $item)
                <tr wire:key='{{ $item['equipment_id'] }}' class="border-b text-sm sm:text-base">
                  <td class="py-4 px-2">
                    <div class="flex flex-col items-center text-center">
                      <img class="h-12 w-12 sm:h-16 sm:w-16 rounded-lg mb-2" 
                          src="{{ url('storage', $item['main_image']) }}" 
                          alt="{{ $item['brand_name']}}">
                      <span class="font-semibold">{{ $item['brand_name']}}</span>
                    </div>
                  </td>
                  <td class="py-4 px-2 whitespace-nowrap">{{ $item['category_description'] ?? 'N/A' }}</td>
                  <td class="py-4 px-2 whitespace-nowrap">{{ $item['facility_name'] ?? 'N/A' }}</td>
                  <td class="py-4 px-2 whitespace-nowrap">{{ $item['serial_no'] ?? 'N/A' }}</td>
                  <td class="py-4 px-2 whitespace-nowrap">{{ $item['property_no'] ?? 'N/A'}}</td>
                  <td class="py-4 px-2">
                    <button wire:click='removeItem ({{ $item['equipment_id'] }})' class="bg-orange-500 text-white border-2 border-orange-400 rounded-lg px-3 py-1 text-xs sm:text-sm hover:bg-red-500 hover:text-white hover:border-red-500">Remove</button>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center py-4 text-gray-500">No items in request list.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="md:w-1/4">
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-lg font-semibold mb-4">Summary</h2>
          <div class="flex justify-between mb-2">
            <span>Keyboard</span>
            <span>2</span>
          </div>
          <div class="flex justify-between mb-2">
            <span>Projector</span>
            <span>1</span>
          </div>
          <div class="flex justify-between mb-2">
            <span>Mouse</span>
            <span>1</span>
          </div>
          <hr class="my-2">
          <div class="flex justify-between mb-2">
            <span class="font-semibold">Total</span>
            <span class="font-semibold">4</span>
          </div>
          @if ($requestlist_equipment)
          <button class="bg-orange-500 text-white py-2 px-4 rounded-lg mt-4 w-full">Proceed</button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
