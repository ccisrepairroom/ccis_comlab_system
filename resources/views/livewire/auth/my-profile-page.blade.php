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
                <div class="flex flex-col items-center text-center">
                    <img class="w-40 h-40 rounded-full object-cover mb-4 mt-6" src="{{ asset('images/profile_avatar.png') }}"  alt="Avatar">
                    <p class="text-xl text-black font-bold">{{ $name }}</p>
                    <p class="text-sm text-gray-500 mb-4">{{ Str::headline($role) }}</p>
                </div>

                <!-- Contact and Department Info -->
                <div class="w-full mt-4 flex flex-col items-center">
                    <div class="mb-4 flex items-center justify-center text-gray-700 text-sm">
                        <x-entypo-mail class="w-5 h-5 mr-2 text-gray-600" />
                        <span>{{ $email }}</span>
                    </div>

                    <div class="mb-4 flex items-center justify-center text-gray-700 text-sm">
                        <x-entypo-briefcase class="w-5 h-5 mr-2 text-gray-600" />
                        <span style="display: inline;">{{ $designation }}</span> - <span style="display: inline;">{{ $department }}</span> 
                        </div>
                </div>
            </div>

            <!-- Grouped right-side cards -->
            <div class="xl:col-span-2 flex flex-col gap-6">
                <!-- Account details card -->
                <div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold mb-4">Edit Account Details</h2>
    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1" for="name">Full Name</label>
            <input wire:model="name" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="name" type="text">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium mb-1" >Department</label>
        <select wire:model="department" id="department" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300">
            <option value="">Select Department</option>
            <option value="Not Applicable">Not Applicable</option>
            <option value="Information Systems">Information Systems</option>
            <option value="Information Technology">Information Technology</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Other Department">Other Department</option>
        </select>
        @error('department')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1" >Designation</label>
        <select wire:model="designation" id="designation" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300">
            <option value="">Select Designation</option>
            <option value="CCIS Dean">CCIS Dean</option>
            <option value="Lab Technician">Lab Technician</option>
            <option value="Comlab Adviser">Comlab Adviser</option>
            <option value="Department Chairperson">Department Chairperson</option>
            <option value="Associate Dean">Associate Dean</option>
            <option value="College Clerk">College Clerk</option>
            <option value="Student Assistant">Student Assistant</option>
            <option value="Instructor">Instructor</option>
            <option value="Lecturer">Lecturer</option>
            <option value="Student">Student</option>
            <option value="Other">Other</option>
        </select>
        @error('designation')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>


        <div class="mb-4">
            <label class="block text-sm font-medium mb-1" for="email">Email address</label>
            <input wire:model="email" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="email" type="email">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600 transition" type="submit">
                Save changes
            </button>
        </div>
    </form>
</div>


                <!-- Password details card -->
                <!-- Password details card -->
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold mb-4">Edit Password Details</h2>
    <form wire:submit.prevent="updatePassword">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div x-data="{ show: false }">
                <label class="block text-sm font-medium mb-1" for="password">New Password</label>
                <div class="relative">

                    <input :type="show ? 'text' : 'password'" wire:model="password" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" id="password" placeholder="(6+ characters required)">
                    
                    <button type="button" @click="show = !show" class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                        <!-- Hidden (Eye Off) Icon -->
                        <x-heroicon-m-eye-slash x-show="!show" class="w-5 h-5" />
                        
                        <!-- Visible (Full Eye) Icon -->
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4c4.97 0 9.13 3.07 10.94 7.36a1.003 1.003 0 010 .68C21.13 16.93 16.97 20 12 20c-4.97 0-9.13-3.07-10.94-7.36a1.003 1.003 0 010-.68C2.87 7.07 7.03 4 12 4zM12 9a3 3 0 100 6 3 3 0 000-6z"></path>
                        </svg>
                    </button>

                </div>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <label class="block text-sm font-medium mb-1" for="confirmNewPassword">Confirm New Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model="confirmPassword" class="w-full rounded-lg border py-2 px-3 focus:ring-orange-500 focus:border-orange-500 border-orange-300" placeholder="(Same with the new password)" id="confirmNewPassword">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                        <!-- Hidden (Eye Off) Icon -->
                        <x-heroicon-m-eye-slash x-show="!show" class="w-5 h-5" />
                        
                        <!-- Visible (Full Eye) Icon -->
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4c4.97 0 9.13 3.07 10.94 7.36a1.003 1.003 0 010 .68C21.13 16.93 16.97 20 12 20c-4.97 0-9.13-3.07-10.94-7.36a1.003 1.003 0 010-.68C2.87 7.07 7.03 4 12 4zM12 9a3 3 0 100 6 3 3 0 000-6z"></path>
                        </svg>
                    </button>
                </div>
                @error('password.same')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
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
