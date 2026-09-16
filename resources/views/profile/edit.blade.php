<x-layouts.shop :title="'Profile — Bloom & Petal'">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Profile</h1>

        <div class="space-y-6">
            <div class="p-6 bg-white border border-rose-100 rounded-2xl shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h2>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 bg-white border border-rose-100 rounded-2xl shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Password</h2>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 bg-white border border-rose-100 rounded-2xl shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Delete Account</h2>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-layouts.shop>