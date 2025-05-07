<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Facility Request Form</h1>
    <div class="grid grid-cols-12 gap-4">
        <div class="md:col-span-12 lg:col-span-8 col-span-12">
            <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                <div class="mb-6">
                    <h2 class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">Fill in</h2>
                    <h2 class="text-xl font-bold text-gray-300 dark:text-white mb-5 text-right">
                        Request Code: {{ $nextRequestCode }}  <!-- Here, the request_code will be displayed -->
                    </h2>                    
                    @auth
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                       <div>
                            <label class="block text-gray-700 dark:text-white mb-1" for="name">Name*</label>
                            <div class="relative">
                                <input wire:model.defer="name" class="w-full rounded-lg border py-2 px-3 dark:bg-orange-500 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="name" type="text" placeholder="Juan Dela Cruz" >
                            </div>
                            @error('name')
                            <p class="text-xs text-red-600 mt-2">{{$message}}</p>
                            @enderror
                        </div>
                    @endauth

                    <div>
                        <label class="block text-gray-700 dark:text-white mb-1" for="phone_number">Phone Number*</label>
                        <input wire:model.defer="phone_number" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="phone_number" type="text" placeholder="09918898988">
                            @error('phone_number')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-gray-700 dark:text-white mb-1" for="facility_name">Facility Name*</label>
                            <textarea wire:model.defer="facility_name" class="h-11 w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="facility_name" type="text" placeholder="Building A - Conference Room"></textarea>
                            @error('facility_name')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="facility_type" class="block text-gray-700 dark:text-white mb-1">Facility Type*</label>
                            <input wire:model.defer="facility_type" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="facility_type" type="text" name="facility_type" placeholder="Meeting Room">
                            @error('facility_type')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="start_date_and_time" class="block text-gray-700 dark:text-white mb-1">Start Date and Time*</label>
                            <input wire:model.defer="start_date_and_time" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="start_date_and_time" type="datetime-local" name="start_date_and_time">
                            @error('start_date_and_time')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date_and_time" class="block text-gray-700 dark:text-white mb-1">End Date and Time*</label>
                            <input wire:model.defer="end_date_and_time" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="end_date_and_time" type="datetime-local" name="end_date_and_time">
                            @error('end_date_and_time')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-700 dark:text-white mb-1" for="purpose">Purpose*</label>
                        <textarea wire:model.defer="purpose" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none focus:ring-orange-500 focus:border-orange-500" id="purpose" placeholder="Describe the purpose of this request..."></textarea>
                        @error('purpose')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        <label class="block text-gray-700 dark:text-white mb-1 mt-4" for="remarks">Remarks</label>
                        <textarea wire:model.defer="remarks" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none focus:ring-orange-500 focus:border-orange-500" id="remarks" placeholder="Add any additional details..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-12 lg:col-span-4 col-span-12">
            <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                <div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">REQUEST SUMMARY</div>
                <div class="flex justify-between mb-2 font-bold">
                    <span></span>
                    <span></span>
                </div>
                <hr class="bg-slate-400 my-4 h-1 rounded">
                <div class="flex justify-between mb-2 font-bold">
                    <span>Total Items</span>
                    <span></span>
                </div>
            </div>

            <button wire:click="save" type="submit" class="bg-orange-500 mt-4 w-full p-3 rounded-lg text-lg text-white hover:bg-orange-600">
                <span wire:loading.remove wire:target='submit'>Submit Request</span>
                <span wire:loading wire:target='submit'>Submitting Request...</span>
            </button>
        </div>
    </div>
</div>
