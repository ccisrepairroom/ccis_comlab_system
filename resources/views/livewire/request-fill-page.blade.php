<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
	<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
		Request Form
	</h1>
	<div class="grid grid-cols-12 gap-4">
		<div class="md:col-span-12 lg:col-span-8 col-span-12">
			<!-- Card -->
			<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<!-- Shipping Address -->
				<div class="mb-6">
					<h2 class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
						Fill in 
					</h2>
					<div class="grid grid-cols-2 gap-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="first_name">
								First Name
							</label>
							<input class="w-full rounded-lg border py-2 px-3 dark:bg-orange-500 dark:text-white dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="first_name" type="text">
							</input>
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="last_name">
								Last Name
							</label>
							<input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white  dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="last_name" type="text">
							</input>
						</div>
					</div>
					<div class="grid grid-cols-2 gap-4 mt-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="date">
								Date
							</label>
							<input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white  dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="date" type="text">
							</input>
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="college_department">
								College/Department
							</label>
							<input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white  dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="college_department" type="text">
							</input>
						</div>
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="phone_number">
							Phone Number
						</label>
						<input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white  dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="phone_number" type="text">
						</input>
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="purpose">
							Purpose
						</label>
						<input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white  dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="phone_number" type="text">
						</input>
					</div>
					<div class="grid grid-cols-1 gap-4 mt-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="state">
								Start Date and Time of Use
							</label>
							<input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white  dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="state" type="text">
							</input>
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="zip">
								End Date and Time of Use
							</label>
							<input class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white  dark:border-orange-500 focus:ring-orange-500 focus:border-orange-500" id="zip" type="text">
							</input>
						</div>
					</div>
				</div>
			</div>
			<!-- End Card -->
		</div>
		<div class="md:col-span-12 lg:col-span-4 col-span-12">
			<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
					REQUEST SUMMARY
				</div>
				<div class="flex justify-between mb-2 font-bold">
					<span>
						Keyboard
					</span>
					<span>
						5
					</span>
				</div>
				<hr class="bg-slate-400 my-4 h-1 rounded">
				<div class="flex justify-between mb-2 font-bold">
					<span>
						Total Equipment
					</span>
					<span>
						5
					</span>
				</div>
				</hr>
			</div>
			<button type="submit" onclick="window.location.href='/success'" class="bg-orange-500 mt-4 w-full p-3 rounded-lg text-lg text-white hover:bg-orange-600">
			<span wire:loading.remove>Submit Request</span>
			<span wire:loading>Processing...</span>
			</button>
			<div class="bg-white mt-4 rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
					ITEMS REQUESTED
				</div>
				<ul class="divide-y divide-gray-200 dark:divide-gray-700" role="list">
					<li class="py-3 sm:py-4">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<img alt="Neil image" class="w-12 h-12 rounded-full" src="https://iplanet.one/cdn/shop/files/iPhone_15_Pro_Max_Blue_Titanium_PDP_Image_Position-1__en-IN_1445x.jpg?v=1695435917">
								</img>
							</div>
							<div class="flex-1 min-w-0 ms-4">
								<p class="text-sm font-medium text-gray-900 truncate dark:text-white">
									ACER
								</p>
								<p class="text-sm text-gray-500 truncate dark:text-gray-400">
									Quantity: 1
								</p>
							</div>	
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>