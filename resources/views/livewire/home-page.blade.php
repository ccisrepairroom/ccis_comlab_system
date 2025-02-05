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
            <li>
              <div class="flex items-center">
                <input id="checkbox-item-1" type="checkbox" value="" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="checkbox-item-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Keyboard</label>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                  <input checked id="checkbox-item-2" type="checkbox" value="" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                  <label for="checkbox-item-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Monitor</label>
                </div>
            </li>
            <li>
              <div class="flex items-center">
                <input id="checkbox-item-3" type="checkbox" value="" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="checkbox-item-3" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Laptop</label>
              </div>
            </li>
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
            <li>
              <div class="flex items-center">
                <input id="checkbox-item-1" type="checkbox" value="" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="checkbox-item-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CL1</label>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                  <input checked id="checkbox-item-2" type="checkbox" value="" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                  <label for="checkbox-item-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Monitor</label>
                </div>
            </li>
            <li>
              <div class="flex items-center">
                <input id="checkbox-item-3" type="checkbox" value="" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="checkbox-item-3" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Laptop</label>
              </div>
            </li>
          </ul>
      </div>
      </div>
      </div>
      </div>
      
      <!-- End Facility Dropdown Section -->



            
    <!-- Start Facilities Section -->
        <!-- </div>
          <div class="p-4 mb-5 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
            <h2 class="text-2xl font-bold dark:text-gray-400"> Facilities</h2>
            <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
            <ul>
              <li class="mb-4">
                <label for="" class="flex items-center dark:text-gray-400 ">
                  <input type="checkbox" class="w-4 h-4 mr-2 accent-orange-500">
                  <span class="text-lg">CL1</span>
                </label>
              </li>
            </ul> -->
        <!-- End Facilities Section -->
    <!-- </div>
    </div> -->
      <!-- <div class="w-full px-3 lg:w-3/4">
          <div class="px-3 mb-4">
            <div class="items-center justify-between hidden px-3 py-2 bg-gray-100 md:flex dark:bg-gray-900 ">
              <div class="flex items-center justify-between">
                <select name="" id="" class="block w-40 text-base bg-gray-100 cursor-pointer dark:text-gray-400 dark:bg-gray-900">
                  <option value="">Sort by latest</option>
                  <option value="">Sort by Price</option>
                </select>
              </div>
            </div>
          </div> -->

 <!-- Start Sorting Section  -->
 <div class="w-full px-3 lg:w-3/4">
  <div class="px-3 mb-4">
    <!-- Ensure flex layout changes based on screen size -->
    <div class="flex flex-col md:flex-row items-start md:items-center gap-3 px-3 py-2 bg-orange-100 dark:bg-gray-900 rounded-md shadow-sm">
      
      <!-- Sort Dropdown (always first) -->
      <select wire:model.live="sort" class="w-full md:w-40 text-base bg-white border border-orange-300 dark:text-gray-400 dark:bg-gray-900 focus:ring-2 focus:ring-orange-500  
                px-3 py-2 rounded-md cursor-pointer accent-orange-500">
        <option value="latest">Sort by latest</option>
        <option value="price">Sort by alphabet</option>
      </select>

      <!-- Search Input (below dropdown on small screens) -->
      <input type="text" wire:model.live="search" class="w-full md:w-46 px-4 py-2 border border-orange-300 dark:bg-orange-500 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 rounded-md" placeholder="Search keyword for an equipment brand name, category name and etc.">
    
    </div>
  </div>


<!--Start Equipment Card Section -->
  <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-4">
    <a href="#" class="block bg-white shadow-md hover:shadow-xl transition-shadow duration-300 rounded-lg overflow-hidden">
      <div class="h-48 bg-gray-100 overflow-hidden">
        <img src="{{ asset('images/equipment/keyboard.png') }}" alt="Keyboard" class="w-full h-full object-cover">
      </div>
      <div class="p-4">
        <div class="flex flex-wrap gap-2 mb-2">
          <span class="px-2 py-1 bg-orange-200 text-orange-800 rounded-full font-semibold uppercase text-xs">Keyboard</span>
          <span class="px-2 py-1 bg-orange-200 text-orange-800 rounded-full font-semibold uppercase text-xs">CL1</span>
        </div>
        <h2 class="font-bold text-lg mb-1">A4TECH</h2>
        <p class="text-sm text-gray-600 mb-3">No Description.</p>
        <div class="flex justify-end">
          <button class="flex items-center gap-1 mt-3 px-3 pt-2 py-2 bg-orange-500 text-white  text-xs font-semibold hover:bg-orange-600 transition-colors">Request <x-eva-plus class="w-4 h-4"/></button>
        </div>
      </div>
    </a>
    <!--End Equipment Card Section -->

  </div>               
  </div>
</body>




                        