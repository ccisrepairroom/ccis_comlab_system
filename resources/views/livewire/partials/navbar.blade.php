<header class="flex z-50 sticky top-0 flex-wrap md:justify-start md:flex-nowrap w-full bg-white text-sm py-3 md:py-0 dark:bg-gray-800 shadow-md">
  <nav class="max-w-[85rem] w-full mx-auto px-4 md:px-6 lg:px-8" aria-label="Global">
    <div class="relative md:flex md:items-center md:justify-between">
      <div class="flex items-center justify-between">


         <!-- Start Logo Section -->
        <a class="hidden md:flex flex-none" href="/" aria-label="logo">
                <img src="{{ asset('images/ccislogo1.1.png') }}" alt="CCIS ERMA Logo" class="h-12 md:h-16 lg:h-20 w-auto">
            </a>

        <a class="flex md:hidden flex-none" href="/" aria-label="Brand">
            <img src="{{ asset('images/ermashortlogo.png') }}" alt="SM Supermarket Logo" class="h-12 md:h-16 lg:h-20 w-auto">
        </a>
        <!-- End Logo Section -->
       

        <div class="md:hidden">
          <button type="button" class="hs-collapse-toggle flex justify-center items-center w-9 h-9 text-sm font-semibold rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-collapse="#navbar-collapse-with-animation" aria-controls="navbar-collapse-with-animation" aria-label="Toggle navigation">
            <svg class="hs-collapse-open:hidden flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="3" x2="21" y1="6" y2="6" />
              <line x1="3" x2="21" y1="12" y2="12" />
              <line x1="3" x2="21" y1="18" y2="18" />
            </svg>
            <svg class="hs-collapse-open:block hidden flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>
      </div>

      <div id="navbar-collapse-with-animation" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block">
        <div class="overflow-hidden overflow-y-auto max-h-[75vh] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500">
          <div class="flex flex-col gap-x-0 mt-5 divide-y divide-dashed divide-gray-200 md:flex-row md:items-center md:justify-end md:gap-x-7 md:mt-0 md:ps-7 md:divide-y-0 md:divide-solid dark:divide-gray-700">

            <a class="font-medium {{ request()->is('/')? 'text-orange-600' : 'text-gray-500 '}} py-3 md:py-6 dark:text-orange-500 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/" aria-current="page">Home</a>

            <div class="hs-dropdown [--strategy:static] md:[--strategy:fixed] [--adaptive:none] [--trigger:click] md:py-4">
              <button type="button" class="flex items-center w-full text-gray-500 hover:text-gray-400 font-medium dark:text-gray-400 dark:hover:text-gray-500">
                Resources
                <svg class="ms-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m6 9 6 6 6-6" />
                </svg>
              </button>

              <div class="hs-dropdown-menu transition-[opacity,margin] duration-[0.1ms] md:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 md:w-48 hidden z-10 bg-white md:shadow-md rounded-lg p-2 dark:bg-gray-800 md:dark:border dark:border-gray-700 dark:divide-gray-700 before:absolute top-full md:border before:-top-5 before:start-0 before:w-full before:h-5">
                
                <!-- Equipment Link -->
                <a href="/equipment" class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm {{ request()->is('equipment') ? 'text-orange-600' : 'text-gray-800' }} hover:bg-gray-100 focus:ring-2 focus:ring-orange-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                  Equipment
                </a>

                <!-- Facilities Link -->
                <a href="/facilities" class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm {{ request()->is('facilities') ? 'text-orange-600' : 'text-gray-800' }} hover:bg-gray-100 focus:ring-2 focus:ring-orange-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                  Facilities
                </a>

                <!-- Supplies & Materials Link -->
                <!-- <a href="/supplies-and-materials" class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm {{ request()->is('supplies') ? 'text-orange-600' : 'text-gray-800' }} hover:bg-gray-100 focus:ring-2 focus:ring-orange-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                  Supplies & Materials
                </a> -->
              </div>
            </div>

            <a  class="font-medium flex items-center {{ request()->is('requests') ? 'text-orange-600' : 'text-gray-500 '}} hover:text-gray-400 py-3 md:py-6 dark:text-gray-400 dark:hover:text-gray-500 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/requests">
              <!-- <x-heroicon-o-plus class="flex-shrink-0 w-5 h-5 mr-0" /> -->
              <span class="mr-1">Requests List</span>
              <span class="py-0.5 px-1.5 rounded-full text-xs font-medium bg-orange-50 border border-orange-200 text-orange-600">{{$total_count}}</span>
            </a>
          @guest
            <div class="pt-3 md:pt-0">
              <a class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-orange-500 text-white hover:bg-orange-600 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/signin">
                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
                Sign In
              </a>
            </div>
          @endguest


           @auth
           <div class="hs-dropdown [--strategy:static] md:[--strategy:fixed] [--adaptive:none] [--trigger:click] md:py-4">
              <button type="button" class="flex items-center w-full text-gray-500 hover:text-gray-400 font-medium dark:text-gray-400 dark:hover:text-gray-500">
                {{auth()->user()->name}}
            <svg class="ms-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6" />
            </svg>
            </button>

            <div class="hs-dropdown-menu transition-[opacity,margin] duration-[0.1ms] md:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 md:w-48 hidden z-10 bg-white md:shadow-md rounded-lg p-2 dark:bg-gray-800 md:dark:border dark:border-gray-700 dark:divide-gray-700 before:absolute top-full md:border before:-top-5 before:start-0 before:w-full before:h-5">
            @if(in_array(auth()->user()->role_id, [1, 2, 3]))
              <a href="{{ url('ccis_erma/management') }}" wire:ignore class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-orange-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" >
                My Dashboard
              </a>
              @endif
            
              <a href="/my-requests" class="cursor-pointer  flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-orange-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                My Requests
              </a>

              <a href="/account-details" class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-orange-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                My Profile
              </a>
              <form method="POST" action="/signout" class="m-0">
              @csrf
              <button type="submit" class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-orange-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                  Logout
              </button>
          </form>
            </div>
          </div>
           @endauth

          </div>
        </div>
      </div>
    </div>
  </nav>
</header>