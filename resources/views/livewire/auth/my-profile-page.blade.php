<div x-data="{ openModal: false, error: '', file: null }">
<div class="container mx-auto px-4 mt-8 mb-12">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Profile picture card -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Profile Picture</h2>
            <div class="flex flex-col items-center text-center">
                <img class="w-40 h-40 rounded-full object-cover mb-4" src="http://bootdey.com/img/Content/avatar/avatar1.png" alt="Avatar">
                <p class="text-xl text-black font-bold">Claire Nakila</p>
                <p class="text-sm text-gray-500 mb-4">Administrator</p>
                <p class="text-sm text-gray-500 mb-4">JPG or PNG no larger than 5 MB</p>
                <button class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition">Upload new image</button>
            </div>

            <!-- Contact and Department Info -->
            <div class="w-full mt-4 flex flex-col items-center">
                <div class="mb-4 flex items-center justify-center text-gray-700 text-sm">
                    <x-entypo-mail class="w-5 h-5 mr-2 text-gray-600" />
                    <span>clairenakila6@gmail.com</span>
                </div>

                <div class="mb-4 flex items-center justify-center text-gray-700 text-sm">
                    <x-entypo-briefcase class="w-5 h-5 mr-2 text-gray-600" />
                    <span>Marketing</span> - <span>Marketing</span> 
                </div>
            </div>

        </div>

        <!-- Grouped right-side cards -->
        <div class="xl:col-span-2 flex flex-col gap-6">
            <!-- Account details card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Account Details</h2>
                <form>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" for="name">Full Name</label>
                        <input class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="name" type="text" >
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" for="name">Department</label>
                            <input class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="name" type="text" >
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="inputLastName">Designation</label>
                            <input class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="inputLastName" type="text">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" for="inputEmailAddress">Email address</label>
                        <input class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="inputEmailAddress" type="email" >
                    </div>
                    <div class="flex justify-end">
                        <button class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600 transition" type="submit">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password details card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Password Details</h2>
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" for="newPassword">New Password</label>
                            <input class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="currentPassword" type="password">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="confirmNewPassword">Confirm New Password</label>
                            <input class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="newPassword" type="password">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600 transition" type="submit">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
