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

                <!-- Start Facility Type Dropdown Section -->
                <div class="pl-10">      
                    <button id="facilityTypeDropdownCheckboxButton" data-dropdown-toggle="facilityTypeDropdownDefaultCheckbox" class="w-36 text-white bg-orange-500 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-between dark:bg-orange-600 dark:hover:bg-orange-600 dark:focus:ring-orange-800" type="button">Facility Type <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                    </button>
                    
                <!-- Dropdown menu -->
                <div id="facilityTypeDropdownDefaultCheckbox" class="pl-9 z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
                <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="categoryDropdownCheckboxButton">
                @foreach ($facilityTypes as $type)      
                    <li>
                        <div class="flex items-center" wire:key ="{{ $type }}">
                            <input id="facility_type_{{ $loop->index }}" name="facility_types[]" value="{{ $type }}" type="checkbox"  class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="facility_type_{{ $loop->index }}"  class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 capitalize">{{ $type }}</label>
                        </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                </div>
                <!-- End Facility Type Dropdown Section -->

                <!-- Start Floor Level Dropdown Section -->
                <div class="pl-10 mt-4">      
                    <button id="floorLevelDropdownCheckboxButton" data-dropdown-toggle="floorLevelDropdownDefaultCheckbox" class="w-36 text-white bg-orange-500 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-between dark:bg-orange-600 dark:hover:bg-orange-600 dark:focus:ring-orange-800" type="button">Floor Level <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                    </button>

                <!-- Dropdown menu -->
                <div id="floorLevelDropdownDefaultCheckbox" class=" pl-9 z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="floorLevelDropdownCheckboxButton">
                    @foreach ($floorLevels as $flevel) 
                    <li>
                        <div class="flex items-center" wire:key ="{{ $flevel }}">
                            <input id="floor_level_{{ $loop->index }}" name = "floor_levels[]"   value="{{ $flevel }}" type="checkbox" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="floor_level_{{ $loop->index }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $flevel }}</label>
                        </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                </div>
              
                <!-- End Floor Level Dropdown Section -->

                <!-- Start Building  Dropdown Section -->
                <div class="pl-10 mt-4">      
                    <button id="buildingsDropdownCheckboxButton" data-dropdown-toggle="buildingsDropdownDefaultCheckbox" class="w-36 text-white bg-orange-500 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-between dark:bg-orange-600 dark:hover:bg-orange-600 dark:focus:ring-orange-800" type="button">Building <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                    </button>

                <!-- Dropdown menu -->
                <div id="buildingsDropdownDefaultCheckbox" class=" pl-9 z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="buildingsDropdownCheckboxButton">
                    @foreach ($buildings as $building) 
                    <li>
                        <div class="flex items-center" wire:key ="{{ $building }}">
                            <input id="building_{{ $loop->index }}" name = "buildings[]"   value="{{ $building }}" type="checkbox" class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-orange-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="building_{{ $loop->index }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $building }}</label>
                        </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                </div>
                </div>
                </div>
                <!-- End Building Dropdown Section -->

            
                <!-- Start Sorting Section  -->
                <div class="w-full px-3 lg:w-3/4">
                    <div class="px-3 mb-4">
                    <!-- Ensure flex layout changes based on screen size -->
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-3 px-3 py-2 bg-orange-100 dark:bg-gray-900 rounded-md shadow-sm">
                    
                    
                        <!-- Search Input (below dropdown on small screens) -->
                        <input type="text" id ="search" name ="search" wire:model.live="search" class="w-full md:w-46 px-4 py-2 border border-orange-300 dark:bg-orange-500 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 rounded-md text-sm" placeholder="Search keyword for an equipment brand name, category name and etc.">
                    </div>
                </div>

      
      <!-- Start Equipment Card Section -->
      <div x-data="{ open: false }" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-1 lg:gap-1">
      @foreach($facilities as $facility)
          <div class="p-4 sm:p-3 md:p-2 lg:p-2" wire:key="{{ $facility->id }}">
              <a  href="#" @click.prevent="open = true" href="#" class="block bg-white shadow-md hover:shadow-xl transition-shadow duration-300 rounded-lg overflow-hidden">
                  <!-- Image Container -->
                  <div class="h-48 sm:h-40 md:h-36 lg:h-36 mt-3 bg-white flex items-center justify-center">
                      <img src="{{ url('storage', $facility->main_image) }}" alt="{{ $facility->name }}" 
                          class="w-full h-full object-contain">
                  </div>
                  <div class="lg:p-2 sm:pl-3 md:pl-2 pl-5 my-3">
                      <div class="flex flex-wrap gap-1 lg:gap-0.5 mb-2 ml-2">
                      <span 
                          class="px-2 py-1 bg-orange-200 text-orange-800 rounded-full font-semibold uppercase text-xs sm:text-[10px] md:text-xs cursor-pointer" 
                          title="{{ $facility->facility_type }}"
                      >
                          {{ Str::limit($facility->facility_type, 13, '...') }}
                      </span>
                      <span 
                          class="px-2 py-1 bg-orange-200 text-orange-800 rounded-full font-semibold uppercase text-xs sm:text-[10px] md:text-xs cursor-pointer" 
                          title="{{ $facility->connection_type }}"
                      >
                          {{ Str::limit($facility->connection_type, 13, '...') }}
                      </span>
                    </div>

                      <h2 class="font-bold text-lg sm:text-md md:text-sm lg:text-md mb-1 px-2">{{ Str::upper($facility->name) }}</h2>
                      
                      <!-- Modal Triggered by See More -->
                      <div x-data="{ open: false }">
                      <p class="text-sm sm:text-xs md:text-xs text-gray-600 mb-2 px-2 text-justify">
                          @if($facility->remarks)
                              {{ Str::title(Str::limit(strip_tags($facility->remarks), 27)) }}
                          @else
                              Remarks is not available.
                          @endif                         
                          <span class="text-orange-500 underline cursor-pointer" @click="open = true">
                              See more
                          </span>
                          </p>
                      <!-- modal -->
                      <div x-show="open" id="seemore-modal" name="seemore-modal" class="fixed inset-0 flex items-center justify-center  backdrop-brightness-75 bg-opacity-50 z-50">
                          <div class="relative p-4 w-full max-w-2xl max-h-full mt-16">
                              <div class="my-10 bg-white p-6 rounded shadow-lg max-h-[80vh] overflow-y-auto">
                                  <h3 class="text-xl font-semibold">{{Str::upper($facility->name)}}</h3>
                                 
                                  <!-- carousel -->
                                  <div class="flex justify-center items-center h-full p-4">
                                  <div class="carousel relative w-full max-w-2xl">
                                    <div class="overflow-hidden bg-gray-300 rounded-xl ">
                                      <div class="carousel-slides relative w-full flex gap-6 snap-x snap-mandatory scroll-smooth overflow-x-auto -mb-10 pt-2 pb-12 px-2">
                                          @if(!empty($facility->alternate_images))
                                          @foreach($facility->alternate_images as $image)                              
                                          <div class="snap-always snap-center shrink-0 relative overflow-hidden aspect-[3/2] w-full rounded-lg bg-white">
                                            <img class="shrink-0 my-0 object-contain w-full h-full" src="{{ url('storage', $image) }}"  alt="{{ $facility->name }}">
                                          </div>
                                          @endforeach
                                          @else
                                          <div class="flex justify-center items-center w-full">
                                              <p class="text-gray-500">No alternate images available.</p>
                                          </div>                                         
                                          @endif              
                                                                            
                                        </div>
                                    </div>
                                    @if (!empty($facility->alternate_images))
                                    <div class="carousel-nav flex justify-center gap-2 pt-2">
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
                                  <p class="text-gray-600 text-sm">
                                      Facility Type: {{Str::Title($facility->facility_type ?? 'N/A')}}<br>
                                      Connection Type: {{Str::Title($facility->connection_type ?? 'N/A')}}<br>
                                      Cooling Tools:  {{Str::Title($facility->cooling_tools ?? 'N/A')}}<br>
                                      Floor Level:   {{Str::Title($facility->floor_level ?? 'N/A')}}<br>
                                      Building: {{Str::Title($facility->building ?? 'N/A')}}<br>
                                  </p>
                                  <p class="text-gray-600 text-sm text-justify pt-5">
                                      Remarks: {{ Str::title(strip_tags($facility->remarks ?? 'N/A')) }}<br>
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
                    <div x-data="{ openModal: false }">
    <!-- Button to trigger the modal -->
    <button
        @click="openModal = true"
        class="flex items-center gap-1 px-3 py-1.5 bg-orange-500 text-white text-xs font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <x-heroicon-o-plus class="w-4 h-4" />Request
    </button>

    <!-- Modal -->
    <div
    x-show="openModal"
    class="fixed inset-0 flex items-center justify-center backdrop-brightness-75 bg-opacity-50 z-50"
    @click.self="openModal = false"
>
    <div class="bg-white p-4 sm:p-6 rounded-sm shadow-lg max-w-xs sm:max-w-sm w-full mx-auto">
        <p class="text-sm sm:text-lg font-semibold pb-4 sm:pb-6 ">
            Are you sure you want to request {{ strtoupper($facility->name) }}?
        </p>
        <div class="mt-4 flex justify-end gap-2 sm:gap-3">
            <button
                @click="openModal = false"
                class="px-3 py-2 sm:px-4 sm:py-2 bg-gray-300 rounded-md text-xs sm:text-sm text-gray-700"
            >
                No
            </button>
            <button
                @click="openModal = false"
                class="px-3 py-2 sm:px-4 sm:py-2 bg-orange-500 rounded-md text-xs sm:text-sm text-white" href="/request-form"
            >
                Yes
            </button>
        </div>
    </div>
</div>
</div>

                      </div>
              </a>
          </div>
          @endforeach
  </div>


    <!-- pagination start -->
    <div class="flex justify-end mt-6">
    {{ $facilities->links() }}
            </div>
            <!-- pagination end -->

            <script>
document.addEventListener("DOMContentLoaded", function () {
    function initCarousel() {
        var carousels = document.querySelectorAll('.carousel');

        carousels.forEach(function (carousel) {
            var carouselSlides = carousel.querySelector('.carousel-slides');
            var slides = carouselSlides.children;
            if (!slides.length) return; // Prevent errors if there are no slides
            
            var slideWidth = slides[0].clientWidth;

            function nextSlide() {
                if (carouselSlides.scrollLeft + slideWidth >= carouselSlides.scrollWidth) {
                    carouselSlides.scrollLeft = 0;
                } else {
                    carouselSlides.scrollBy({ left: slideWidth, behavior: 'smooth' });
                }
            }

            function prevSlide() {
                if (carouselSlides.scrollLeft <= 0) {
                    carouselSlides.scrollLeft = carouselSlides.scrollWidth - slideWidth;
                } else {
                    carouselSlides.scrollBy({ left: -slideWidth, behavior: 'smooth' });
                }
            }

            var autoSlide = setInterval(nextSlide, 4000);

            var carouselNavNext = carousel.querySelector('.carousel-nav-next');
            if (carouselNavNext) {
                carouselNavNext.addEventListener('click', function (event) {
                    event.preventDefault();
                    nextSlide();
                    resetAutoSlide();
                });
            }

            var carouselNavPrev = carousel.querySelector('.carousel-nav-prev');
            if (carouselNavPrev) {
                carouselNavPrev.addEventListener('click', function (event) {
                    event.preventDefault();
                    prevSlide();
                    resetAutoSlide();
                });
            }

            function resetAutoSlide() {
                clearInterval(autoSlide);
                autoSlide = setInterval(nextSlide, 4000);
            }
        });
    }

    initCarousel(); // Run on first load

    // Re-run script when Livewire updates the page
    Livewire.hook('message.processed', (message, component) => {
        initCarousel();
    });
});
</script>


  </body>