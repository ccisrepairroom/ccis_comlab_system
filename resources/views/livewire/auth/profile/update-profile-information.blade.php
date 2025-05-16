<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Update your account's profile information and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="#">
        <!-- CSRF removed -->
    </form>

    <form method="post" action="#" class="mt-6 space-y-6">
        <!-- CSRF & PATCH removed -->

        <div>
            <label for="firstname" class="block text-sm font-medium text-gray-700">First name</label>
            <input id="firstname" name="firstname" type="text" class="mt-1 block w-full" value="John" required autofocus autocomplete="firstname">
            <p class="mt-2 text-sm text-red-600"> <!-- Example error message -->
                First name is required.
            </p>
        </div>

        <div>
            <label for="lastname" class="block text-sm font-medium text-gray-700">Last name</label>
            <input id="lastname" name="lastname" type="text" class="mt-1 block w-full" value="Doe" required autofocus autocomplete="lastname">
            <p class="mt-2 text-sm text-red-600">
                Last name is required.
            </p>
        </div>

        <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <input id="address" name="address" type="text" class="mt-1 block w-full" value="123 Example St" required autofocus autocomplete="address">
            <p class="mt-2 text-sm text-red-600">
                Address is required.
            </p>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full" value="user@example.com" required autocomplete="username">
            <p class="mt-2 text-sm text-red-600">
                Email is required.
            </p>

            <div>
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <button form="send-verification"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Click here to re-send the verification email.
                    </button>
                </p>

                <p class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Save</button>

            <p class="text-sm text-gray-600">Saved.</p>
        </div>
    </form>
</section>
