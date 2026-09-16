<x-layouts.shop :title="'My Account — Bloom & Petal'">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">My account</h1>

        <div class="space-y-6">
            <a href="{{ route('orders.index') }}"
               class="block bg-white border border-rose-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-900">My Orders</h2>
                        <p class="text-sm text-gray-500 mt-1">View your order history and track deliveries.</p>
                    </div>
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="block bg-white border border-rose-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-900">Profile Settings</h2>
                        <p class="text-sm text-gray-500 mt-1">Update your name, email, password and phone number.</p>
                    </div>
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</x-layouts.shop>
