<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Request Form</h1>
    <div class="grid grid-cols-12 gap-4">
        <div class="md:col-span-12 lg:col-span-8 col-span-12">
            <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                <div class="mb-6">
                    <h2 class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">Fill in</h2>
                    @auth
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                       <div>
    <label class="block text-gray-700 dark:text-white mb-1" for="name">Name</label>
    <div class="relative" wire:model="name">
        <input wire:model.defer="name" class="w-full rounded-lg border py-2 px-3 dark:bg-orange-500 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="name" type="text" placeholder="Rosalyn Banguis" aria-describedby="name-error">
        
        @error('name')
        <div class="absolute inset-y-0 right-3 flex items-center">
            <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
            </svg>
        </div>
        @enderror
    </div>

    @error('name')
    <p class="text-xs text-red-600 mt-2">{{$message}}</p>
    @enderror
</div>

                    @endauth
                        <div>
                            <label for="date_requested" class="block text-gray-700 dark:text-white mb-1">Date</label>
                            <input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="date_requested" type="datetime-local" name="date_requested" wire:model="date_requested" value="{{ now('Asia/Manila')->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-gray-700 dark:text-white mb-1" for="college_department">College/Department</label>
                            <textarea class="h-11 w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="college_department" type="text" placeholder="CCIS - Computer Science"></textarea>
                        </div>
                       <div>
                            <label class="block text-gray-700 dark:text-white mb-1" for="phone_number">Phone Number</label>
                            <input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="phone_number" type="text" placeholder="09918898988">
                        </div>

                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="start_date" class="block text-gray-700 dark:text-white mb-1">Start Date and Time of Use</label>
                            <input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="start_date" type="datetime-local" name="start_date">
                        </div>
                        <div>
                            <label for="end_date" class="block text-gray-700 dark:text-white mb-1">End Date and Time of Use</label>
                            <input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="end_date" type="datetime-local" name="end_date">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 dark:text-white mb-1" for="purpose">Purpose</label>
                        <textarea  class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none focus:ring-orange-500 focus:border-orange-500" id="purpose" type="text" placeholder="Describe the purpose of this request..."></textarea>
                        <label class="block text-gray-700 dark:text-white mb-1 mt-4" for="notes">Notes</label>
                        <textarea class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none focus:ring-orange-500 focus:border-orange-500" id="notes" type="text" placeholder="Add any additional details..."></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:col-span-12 lg:col-span-4 col-span-12">
            <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                <div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">REQUEST SUMMARY</div>
                @foreach ($category_totals as $category => $quantity)
                <div class="flex justify-between mb-2 font-bold">
                    <span>{{$category}}</span>
                    <span>{{$quantity}}</span>
                </div>
                @endforeach
                <hr class="bg-slate-400 my-4 h-1 rounded">
                <div class="flex justify-between mb-2 font-bold">
                    <span>Total Equipment</span>
                    <span>{{$total_request }}</span>
                </div>
            </div>
            <button wire:click="save" type="submit" onclick="window.location.href='/success'" class="bg-orange-500 mt-4 w-full p-3 rounded-lg text-lg text-white hover:bg-orange-600">
                <span wire:loading.remove wire:target='submit'>Submit Request</span>
                <span wire:loading wire:target='submit'>Submitting Request...</span>
            </button>
            <div class="bg-white mt-4 rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                <div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">ITEMS REQUESTED</div>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700" role="list">
                    @foreach($requestlist_equipment as $re)
                    <li class="py-3 sm:py-4" wire:key='{{$re['equipment_id']}}'>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img alt="{{$re['brand_name']}}" class="w-12 h-12 rounded-full" src="{{url('storage', $re['main_image'])}}">
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{$re['brand_name']}}</p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">Quantity: 1</p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
