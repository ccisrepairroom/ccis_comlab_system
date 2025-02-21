@php
    use Illuminate\Support\Str;
@endphp

<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
    <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
      <div class="flex flex-wrap mb-24 -mx-3">
        <div class="w-full pr-2 lg:w-1/4 lg:block">
          <div class="p-4 mb-5 mx-6 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
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
            <select name ="sort" wire:model.live="sort" class="w-full md:w-40 text-base bg-white border border-orange-300 dark:text-gray-400 dark:bg-gray-900 focus:ring-2 focus:ring-orange-500  
                      px-3 py-2 rounded-md cursor-pointer accent-orange-500 appearance-none">
              <option value="latest" class ="hover:bg-orange-600">Sort by latest</option>
              <option value="facility" class ="hover:bg-orange-600">Sort by facility</option>
              <option value="category" class ="hover:bg-orange-600">Sort by category</option>
            </select>

           
            <!-- Search Input (below dropdown on small screens) -->
            <input type="text" id ="search" name ="search" wire:model.live="search" class="w-full md:w-46 px-4 py-2 border border-orange-300 dark:bg-orange-500 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 rounded-md text-sm" placeholder="Search keyword for an equipment brand name, category name and etc.">
          </div>
        </div>

        @if ($noEquipmentFound)
            <p class="text-center text-gray-500 mb-4">No equipment found.</p>
        @endif
      <!-- Start Equipment Card Section -->
<div x-data="{ open: false }" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-1 lg:gap-1">
      @foreach($equipment as $equip)
          <div class="p-4 sm:p-3 md:p-2 lg:p-2" wire:key="{{ $equip->id }}">
              <a  href="#" @click.prevent="open = true" href="#" class="block bg-white shadow-md hover:shadow-xl transition-shadow duration-300 rounded-lg overflow-hidden">
                  <!-- Image Container -->
                  <div class="h-48 sm:h-40 md:h-36 lg:h-36 mt-3 bg-white flex items-center justify-center">
                      <img src="{{ url('storage', $equip->main_image) }}" alt="{{ $equip->name }}" 
                          class="w-full h-full object-contain">
                  </div>
                  <div class="lg:p-2 sm:pl-3 md:pl-2 pl-5 my-3">
                      <div class="flex flex-wrap gap-1 lg:gap-0.5 mb-2 ml-2">
                          <span class="px-2 py-1 bg-orange-200 text-orange-800 rounded-full font-semibold uppercase text-xs sm:text-[10px] md:text-xs cursor-pointer" title="{{ $equip->category->description }}">
                              {{ Str::limit($equip->category->description, 13, '...') }}
                          </span>
                          <span class="px-2 py-1 bg-orange-200 text-orange-800 rounded-full font-semibold uppercase text-xs sm:text-[10px] md:text-xs cursor-pointer" title="{{ $equip->facility->name }}">
                              {{ Str::limit($equip->facility->name, 13, '...') }}
                          </span>
                      </div>

                      <h2 class="font-bold text-lg sm:text-md md:text-sm lg:text-md mb-1 px-2">{{ Str::upper($equip->brand_name) }}</h2>
                      <!-- Modal Triggered by See More -->
                      <div x-data="{ open: false }">
                      <p class="text-sm sm:text-xs md:text-xs text-gray-600 mb-2 px-2 text-justify">
                          @if($equip->description)
                              {{ Str::limit($equip->description, 27) }}
                          @else
                              Description is not available.
                          @endif
                          
                          <span class="text-orange-500 underline cursor-pointer" @click="open = true">
                              See more
                          </span>
                          </p>
                      <!-- modal -->
                      <div x-show="open" id="seemore-modal" name="seemore-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                          <div class="relative p-4 w-full max-w-2xl max-h-full mt-16">
                              <div class="my-10 bg-white p-6 rounded shadow-lg max-h-[80vh] overflow-y-auto">
                                  <h3 class="text-xl font-semibold">{{Str::upper($equip->brand_name)}}</h3>
                                  
                                  <!-- carousel -->
                                  <div class="flex justify-center items-center h-full p-4">
                                  <div class="carousel relative w-full max-w-2xl">
                                    <div class="overflow-hidden bg-gray-300 rounded-xl ">
                                      <div class="carousel-slides relative w-full flex gap-6 snap-x snap-mandatory scroll-smooth overflow-x-auto -mb-10 pt-2 pb-12 px-2">
                                      @if(!empty($equip->alternate_images))
                                          @foreach($equip->alternate_images as $image)                                        
                                          <div class="snap-always snap-center shrink-0 relative overflow-hidden aspect-[3/2] w-full rounded-lg bg-gray-200">
                                            <img class="shrink-0 my-0 object-contain w-full h-full" src="{{ url('storage', $image) }}"  alt="{{ $equip->brand_name }}">
                                          </div>
                                          @endforeach
                                          @else
                                          <div class="flex justify-center items-center w-full">
                                              <p class="text-gray-500">No alternate images available.</p>
                                          </div>                                         
                                          @endif                
                                                                            
                                        </div>
                                    </div>
                                    @if (!empty($equip->alternate_images))
                                    <div class="carousel-nav flex justify-end gap-2 pt-2">
                                      <button type="button" class="carousel-nav-prev rounded-full bg-gray-200 p-1.5 text-gray-600 shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-gray-200 transition-all duration-300">
                                        <svg class="lucide lucide-chevron-left w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                      </button>
                                      <button type="button" class="carousel-nav-next rounded-full bg-gray-200 p-1.5 text-gray-600 shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-gray-200 transition-all duration-300">
                                        <svg class="lucide lucide-chevron-right w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                      </button>
                                    </div>
                                    @endif
                                  </div>
                                </div>
                                  <p class="text-gray-600 py-6 text-justify">{{$equip->description}}</p>
                                  <p class="text-gray-600 text-sm">
                                      Serial Number: {{$equip->serial_no ?? 'N/A'}} <br>
                                      Property Number: {{$equip->property_no ?? 'N/A'}} <br>
                                      Control Number: {{$equip->control_no ?? 'N/A'}} <br>
                                      PO Number: {{$equip->po_number ?? 'N/A'}} <br>
                                      Unit Number: {{$equip->unit_no ?? 'N/A'}} - {{$equip->facility->name ?? 'N/A'}} <br>
                                      Date Acquired: {{$equip->date_acquired ?? 'N/A'}} <br>
                                      Person Liable: {{$equip->person_liable ?? 'N/A'}} <br>
                                  </p>
                                  <p class="text-gray-600 text-sm text-justify pt-5">
                                      Remarks:  {{$equip->remarks ?? 'N/A'}} <br>
                                  </p>
                                  <div class="flex justify-end">
                                  <button class=" mt-4 px-4 py-2 bg-orange-500 text-white rounded" @click="open = false">Close</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      </div> 
                  </div>

                <!-- Request button -->
                <div class="flex justify-end mt-1 mb-5 mr-5">
                          <button class="flex items-center gap-1  px-3 py-1.5 bg-orange-500 text-white text-xs font-semibold hover:bg-orange-600 transition-colors">
                              Request 
                              <x-heroicon-o-plus class="w-4 h-4" />
                          </button>
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

  <script>
// Select all carousel components
var carousels = document.querySelectorAll('.carousel');

carousels.forEach(function (carousel) {
  var carouselSlides = carousel.querySelector('.carousel-slides');
  var slides = carouselSlides.children;
  var slideWidth = slides[0].clientWidth; // Get the width of a single slide

  // Function to go to the next slide
  function nextSlide() {
    if (carouselSlides.scrollLeft + slideWidth >= carouselSlides.scrollWidth) {
      carouselSlides.scrollLeft = 0; // Loop back to start
    } else {
      carouselSlides.scrollBy({ left: slideWidth, behavior: 'smooth' });
    }
  }

  // Function to go to the previous slide
  function prevSlide() {
    if (carouselSlides.scrollLeft <= 0) {
      carouselSlides.scrollLeft = carouselSlides.scrollWidth - slideWidth; // Loop to end
    } else {
      carouselSlides.scrollBy({ left: -slideWidth, behavior: 'smooth' });
    }
  }

  // Auto-slide every 3 seconds
  var autoSlide = setInterval(nextSlide, 1000);

  // Next button 
  var carouselNavNext = carousel.querySelector('.carousel-nav-next');
  carouselNavNext.addEventListener('click', function (event) {
    event.preventDefault();
    nextSlide();
    resetAutoSlide(); 
  });

  // Previous button 
  var carouselNavPrev = carousel.querySelector('.carousel-nav-prev');
  carouselNavPrev.addEventListener('click', function (event) {
    event.preventDefault();
    prevSlide();
    resetAutoSlide(); 
  });

  // Reset auto-slide when user interacts
  function resetAutoSlide() {
    clearInterval(autoSlide);
    autoSlide = setInterval(nextSlide, 4000);
  }
});

</script>

  </body>