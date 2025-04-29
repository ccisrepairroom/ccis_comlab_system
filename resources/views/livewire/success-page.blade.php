<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <section class="flex items-center font-poppins dark:bg-gray-800">
    <div class="justify-center flex-1 max-w-6xl px-4 py-4 mx-auto bg-white border rounded-md dark:border-gray-900 dark:bg-gray-900 md:py-10 md:px-10">
      <div>
        <h1 class="px-4 mb-8 text-2xl font-semibold tracking-wide text-gray-700 dark:text-gray-300">
          Thank you. Your request has been received.
        </h1>
        
        <div class="flex border-b border-gray-200 dark:border-gray-700  items-stretch justify-start w-full h-full px-4 mb-8 md:flex-row xl:flex-col md:space-x-6 lg:space-x-8 xl:space-x-0">
          <div class="flex items-start justify-start flex-shrink-0">
            <div class="flex items-center justify-center w-full pb-6 space-x-4 md:justify-start">
              <div class="flex flex-col items-start justify-start space-y-2">
                <p class="text-lg font-semibold leading-4 text-left text-gray-800 dark:text-gray-400">
                  {{ $requestDetails['name'] }}</p>
                <p class="text-sm leading-4 text-gray-600 dark:text-gray-400">Phone: {{ $requestDetails['phone_number'] }}</p>
                <p class="text-sm leading-4 text-gray-600 dark:text-gray-400">College/Department: {{ $requestDetails['college_department'] }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap items-center pb-4 mb-10 border-b border-gray-200 dark:border-gray-700">
          <div class="w-full px-4 mb-4 md:w-1/4">
              <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400 ">Request Code: </p>
              <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">{{ $requestDetails['request_code'] }}</p>
          </div>  
          <div class="w-full px-4 mb-4 md:w-1/4">
              <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400">Date Requested: </p>
              <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">{{ \Carbon\Carbon::now()->toFormattedDateString() }}</p>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/4">
          <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400">Start Date and Time of Use: </p>
          <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">
            {{ \Carbon\Carbon::parse($requestDetails['start_date'])->format('F j, Y, g:i A') }}
          </p>
          </div>

          <div class="w-full px-4 mb-4 md:w-1/4">
          <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400">End Date and Time of Use: </p>
          <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">
            {{ \Carbon\Carbon::parse($requestDetails['end_date'])->format('F j, Y, g:i A') }}
          </p>
          </div>
          <div class="w-full px-4 mb-4 md:w-1/4">
            <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400 ">Purpose: </p>
            <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">{{ $requestDetails['purpose'] }}</p>
          </div>
        </div>
      
        <!-- Requested Items Table -->
        <div class="px-4 mb-10">
          <h2 class="mb-2 text-xl font-semibold text-gray-700 dark:text-gray-400">Requested Items</h2>
          <!-- Populate requested items here -->
        </div>
      
        <!-- Buttons -->
        <div class="flex justify-start gap-4 px-4 mt-6">
          <a href="/" class="px-4 py-2 text-orange-500 border border-orange-500 rounded-md hover:text-white hover:bg-orange-600 dark:border-gray-700 dark:hover:bg-gray-700 dark:text-gray-300">
            Go to home
          </a>
          <a href="/orders" class="px-4 py-2 bg-orange-500 rounded-md text-gray-50 hover:bg-orange-600 dark:hover:bg-gray-700 dark:bg-gray-800">
            View My Requests
          </a>
        </div>
      </div>
    </div>
  </section>
</div>
