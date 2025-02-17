<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
    <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
      <div class="flex flex-wrap mb-24 -mx-3">
        <div class="w-full pr-2 lg:w-1/4 lg:block">
          <div class="p-4 mb-5 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
            <h2 class="text-2xl font-bold dark:text-gray-400"> Filter By:</h2>
            <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>

      <!-- Start Category Dropdown Section -->
      <div class="pl-10">      
        <button id="categoryDropdownCheckboxButton" data-dropdown-toggle="categoryDropdownDefaultCheckbox" class="w-36 text-white bg-orange-500 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-between dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800" type="button">Categories <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
        </button>
          
      <!-- Dropdown menu -->
      <div id="categoryDropdownDefaultCheckbox" class="pl-9 z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
      <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="categoryDropdownCheckboxButton">
            @foreach ($categories as $category)
          <li>
              <div class="flex items-center" wire:key ="$category->id">
                <input id="category-{{ $category->id }}"  name = "selected_categories[]" wire:model.live= "selected_categories" type="checkbox" value="{{ $category->id }}" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="category-{{ $category->id}}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 capitalize">{{Str::title ($category->description)}}</label>
              </div>
            </li>
           @endforeach
          </ul>
      </div>
      </div>
      <!-- End Category Dropdown Section -->

      
      <!-- Start Facility Dropdown Section -->
      <div class="pl-10 mt-4">      
        <button id="facilityDropdownCheckboxButton" data-dropdown-toggle="facilityDropdownDefaultCheckbox" class="w-36 text-white bg-orange-500 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-between dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800" type="button">Facilities <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
        </button>

      <!-- Dropdown menu -->
      <div id="facilityDropdownDefaultCheckbox" class=" pl-9 z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
          <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="facilityDropdownCheckboxButton">
          @foreach ($facilities as $facility)  
          <li>
              <div class="flex items-center" wire:key ="$facility->id">
                <input id="facility-{{$facility->id}}" name = "selected_facilities[]" type="checkbox" wire:model.live= "selected_facilities" value="{{ $facility->id }}" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="facility-{{$facility->id}}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ Str::upper($facility->name)}}</label>
              </div>
            </li>
            @endforeach
          </ul>
      </div>
      </div>
      </div>
      </div>
      <!-- End Facility Dropdown Section -->

      
 <!-- Start Sorting Section  -->
 <div class="w-full px-3 lg:w-3/4">
  <div class="px-3 mb-4">
    <!-- Ensure flex layout changes based on screen size -->
    <div class="flex flex-col md:flex-row items-start md:items-center gap-3 px-3 py-2 bg-orange-100 dark:bg-gray-900 rounded-md shadow-sm">
      
      <!-- Sort Dropdown (always first) -->
      <!-- <select name ="sort" wire:model.live="sort" class="w-full md:w-40 text-base bg-white border border-orange-300 dark:text-gray-400 dark:bg-gray-900 focus:ring-2 focus:ring-orange-500  
                px-3 py-2 rounded-md cursor-pointer accent-orange-500 appearance-none">
        <option value="latest" class ="hover:bg-orange-600">Sort by latest</option>
        <option value="facility" class ="hover:bg-orange-600">Sort by facility</option>
      </select> -->

      <select id="sort" name ="sort"  wire:model.live="sort" class="w-full md:w-40 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500">
      <option value="latest">Sort by Latest</option>
      <option value="facility">Sort by Facility</option>
      <option value="category">Sort by Category</option>
    </select>
      <!-- Search Input (below dropdown on small screens) -->
      <input type="text" id ="search" name ="search" wire:model.live="search" class="w-full md:w-46 px-4 py-2 border border-orange-300 dark:bg-orange-500 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 rounded-md" placeholder="Search keyword for an equipment brand name, category name and etc.">
    </div>
  </div>

  @if ($noEquipmentFound)
      <p class="text-center text-gray-500 mb-4">No equipment found.</p>
  @endif
<!--Start Equipment Card Section -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-1 lg:gap-1">
    @foreach($equipment as $equip)
        <div class="p-4 lg:p-2" wire:key="{{ $equip->id }}">
            <a  class="block bg-white shadow-md hover:shadow-xl transition-shadow duration-300 rounded-lg overflow-hidden">
                <!-- Image Container -->
                <div class="h-48 lg:h-36 mt-3 bg-white flex items-center justify-center">
                    <img src="{{ url('storage', $equip->main_image) }}" alt="{{ $equip->name }}" 
                         class="w-full h-full object-contain">
                </div>
                <div class=" lg:p-2 mr-5">
                    <div class="flex flex-wrap gap-1 lg:gap-0.5 mb-2 ml-2">
                        <span class="px-2 py-1 bg-orange-200 text-orange-800 rounded-full font-semibold uppercase text-xs">{{ $equip->category->description}}</span>
                        <span class="px-2 py-1 bg-orange-200 text-orange-800 rounded-full font-semibold uppercase text-xs">{{ $equip->facility->name}}</span>
                    </div>
                    <h2 class="font-bold text-lg lg:text-md mb-1 px-2">{{ $equip->brand_name }}</h2>
                    <!-- Modal Triggered by See More -->
                    <p class="text-sm md:text-xs text-gray-600 mb-2 px-2 text-justify">
                        @if($equip->description)
                            {{ Str::limit($equip->description, 27) }}
                            @if(strlen($equip->description) > 80)
                                <span class="text-orange-500 underline cursor-pointer" 
                                        data-modal-target="equipment-seemore-modal-{{ $equip->id }}" 
                                        data-modal-toggle="equipment-seemore-modal-{{ $equip->id }}">
                                      See more
                                </span>
                            @endif
                        @else
                            Description is not available.
                        @endif
                    </p>



<!-- Main modal -->
<div id="equipment-seemore-modal-{{ $equip->id }}" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ Str::title($equip->brand_name)}}
                </h3>
                <button type="button" class="text-gray-20 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="equipment-seemore-modal-{{ $equip->id }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <p class="text-justify text-base leading-relaxed text-gray-500 dark:text-gray-400">
                  {{ $equip->description }}
                </p>

                <p class="text-justify text-base leading-relaxed text-gray-500 dark:text-gray-400">
                  Serial Number: {{$equip->serial_no ?? 'N/A'}} <br>
                  Property Number: {{$equip->_no ?? 'N/A'}}

                </p>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="equipment-seemore-modal-{{ $equip->id }}" type="button" class="text-white bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">Request</button>
                <button data-modal-hide="equipment-seemore-modal-{{ $equip->id }}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-orange-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Request button -->

                 
                    <div class="flex justify-end">
                        <button class="flex items-center gap-1  mb-2 mt-2 px-3 py-1.5 bg-orange-500 text-white text-xs font-semibold hover:bg-orange-600 transition-colors">
                            Request 
                            <x-heroicon-o-plus class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
  <!-- pagination start -->
  <div class="flex justify-end mt-6">
    {{ $equipment->links() }}
          </div>
          <!-- pagination end -->
</body>


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[id^='equipment-seemore-modal-']").forEach((modal) => {
        const modalId = modal.id;
        if (!window.Flowbite || !window.Flowbite.instances[modalId]) {
            new Modal(modal);
        }
    });
});
</script>





                        