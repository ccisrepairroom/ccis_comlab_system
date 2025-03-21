<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="flex h-full items-center">
    <main class="w-full max-w-md mx-auto p-6">
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 sm:p-7">
          <div class="text-center">
            <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Sign in</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              Don't have an account yet?
              <a class="text-orange-500 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="https://www.facebook.com/cciscarsu">
                Contact Admin
              </a>
            </p>
          </div>

          <hr class="my-5 border-slate-300">

          <!-- Form -->
          <form wire:submit.prevent='save'>

          @if(session('error'))
          <div class="mt-2 bg-red-500 text-sm text-center text-white rounded-lg p-4 mb-4 " role="alert" tabindex="-1" aria-labelledby="hs-solid-color-danger-label ">
        {{session('error')}}  
        </div>
          @endif

            <div class="grid gap-y-4">
              <!-- Form Group -->
              <div>
                <label for="email" class="block text-sm mb-2 dark:text-white">Email address</label>
                <div class="relative">
                  <input type="email" name="email" autocomplete="username" placeholder="(e.g.,name@gmail.com)" id="email" wire:model="email" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-orange-500 focus:ring-orange-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="email-error">
                  @error('email')
                  <div class=" absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                    <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                  @enderror
                </div>
                @error('email')
                <p class=" text-xs text-red-600 mt-2" id="email-error">{{$message}}</p>
              @enderror
              </div>
              <!-- End Form Group -->

              <!-- Form Group -->
              <div>
                <div class="flex justify-between items-center">
                    <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
                    <a class="text-sm text-orange-500 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/forgot">Forgot password?</a>
                </div>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" autocomplete="current-password" placeholder="(6+ characters required)" id="password" wire:model="password"
                        class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-orange-500 focus:ring-orange-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                        aria-describedby="password-error">
                        <button type="button" @click="show = !show"
                class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                <!-- Hidden (Eye Off) Icon -->
                <x-heroicon-m-eye-slash x-show="!show" class="w-5 h-5" />
                
                <!-- Visible (Full Eye) Icon -->
                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4c4.97 0 9.13 3.07 10.94 7.36a1.003 1.003 0 010 .68C21.13 16.93 16.97 20 12 20c-4.97 0-9.13-3.07-10.94-7.36a1.003 1.003 0 010-.68C2.87 7.07 7.03 4 12 4zM12 9a3 3 0 100 6 3 3 0 000-6z"></path>
                </svg>
            </button>

                    @error('password')
                        <div class="absolute inset-y-0 end-0 flex items-center pe-3">
                            <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                                aria-hidden="true">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                            </svg>
                        </div>
                    @enderror
                </div>
            </div>


                @error('password')
                <p class=" text-xs text-red-600 mt-2" id="password-error">{{$message}}</p>
              @enderror
              </div>

              <div x-data="{ remembered: true }" class="row">
    <div class="col-8">
        <div class="icheck-primary">
            <input type="checkbox" id="remember" wire:model="remember" 
                @click="remembered = !remembered"
                class="checkbox border-orange-500 checked:bg-orange-500 checked:text-orange-500 checked:border-orange-500 focus:ring-1 focus:outline-none focus:ring-orange-500" />
            <label for="remember" class="text-gray-500 text-sm"> Remember Me </label>
        </div>
    </div>
</div>

<!-- Forgot Credentials -->
<div x-show="remembered" class="mt-2">
    <a class="text-sm text-orange-500 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/forgot">
        Forgot credentials?
    </a>
</div>






              <!-- End Form Group -->
              <button type="submit" class="w-full mt-4 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-orange-500 text-white hover:bg-orange-600 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">Sign in</button>
            </div>
          </form>
          <!-- End Form -->
        </div>
      </div>
  </div>
</div>