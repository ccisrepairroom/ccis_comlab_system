<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="#" class="mt-6 space-y-6">
        <!-- CSRF and PUT method removed -->

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">
                Current Password
            </label>
            <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password">
            <p class="mt-2 text-sm text-red-600">Current password is required.</p>
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">
                New Password
            </label>
            <input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password">
            <p class="mt-2 text-sm text-red-600">New password is required.</p>
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">
                Confirm Password
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password">
            <p class="mt-2 text-sm text-red-600">Password confirmation does not match.</p>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                Save
            </button>

            <p class="text-sm text-gray-600">Saved.</p>
        </div>
    </form>
</section>
