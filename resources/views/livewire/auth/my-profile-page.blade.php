@php use Illuminate\Support\Str; @endphp

<div x-data="{ openModal: false, error: '', file: null, showSuccess: true }">
    <div class="container mx-auto px-4 mt-8 mb-12">

        @if (session()->has('success'))
            <div x-show="showSuccess" class="relative bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
                <button @click="showSuccess = false" class="absolute top-1 right-2 text-green-800 hover:text-green-600 text-xl leading-none">
                    &times;
                </button>
            </div>
        @endif


        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Profile picture card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Profile Picture</h2>
                <div class="flex flex-col items-center text-center">
                    <img class="w-40 h-40 rounded-full object-cover mb-4" src="http://bootdey.com/img/Content/avatar/avatar1.png" alt="Avatar">
                    <p class="text-xl text-black font-bold">{{ $name }}</p>
                    <p class="text-sm text-gray-500 mb-4">{{ Str::headline($role) }}</p>
                    <p class="text-sm text-gray-500 mb-4">JPG or PNG no larger than 5 MB</p>
                    <button class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition">Upload new image</button>
                </div>

                <!-- Contact and Department Info -->
                <div class="w-full mt-4 flex flex-col items-center">
                    <div class="mb-4 flex items-center justify-center text-gray-700 text-sm">
                        <x-entypo-mail class="w-5 h-5 mr-2 text-gray-600" />
                        <span>{{ $email }}</span>
                    </div>

                    <div class="mb-4 flex items-center justify-center text-gray-700 text-sm">
                        <x-entypo-briefcase class="w-5 h-5 mr-2 text-gray-600" />
                        <span>{{ $department }}</span> - <span>{{ $designation }}</span> 
                    </div>
                </div>
            </div>

            <!-- Grouped right-side cards -->
            <div class="xl:col-span-2 flex flex-col gap-6">
                <!-- Account details card -->
                <div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold mb-4">Account Details</h2>
    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1" for="name">Full Name</label>
            <input wire:model="name" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="name" type="text">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium mb-1" for="department">Department</label>
        <select wire:model="department" id="department" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300">
            <option value="">Select Department</option>
            <option value="not_applicable">Not Applicable</option>
            <option value="information_system">Information System</option>
            <option value="information_technology">Information Technology</option>
            <option value="computer_science">Computer Science</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1" for="designation">Designation</label>
        <select wire:model="designation" id="designation" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300">
            <option value="">Select Designation</option>
            <option value="ccis_dean">CCIS Dean</option>
            <option value="lab_technician">Lab Technician</option>
            <option value="comlab_adviser">Comlab Adviser</option>
            <option value="department_chairperson">Department Chairperson</option>
            <option value="associate_dean">Associate Dean</option>
            <option value="college_clerk">College Clerk</option>
            <option value="student_assistant">Student Assistant</option>
            <option value="instructor">Instructor</option>
            <option value="lecturer">Lecturer</option>
            <option value="other">Other</option>
        </select>
    </div>
</div>


        <div class="mb-4">
            <label class="block text-sm font-medium mb-1" for="email">Email address</label>
            <input wire:model="email" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="email" type="email">
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
                    <form wire:submit.prevent="updatePassword">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-1" for="password">New Password</label>
                                <input wire:model="password" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="password" type="password">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" for="confirmNewPassword">Confirm New Password</label>
                                <input wire:model="confirmPassword" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="confirmNewPassword" type="password">
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
</div>
