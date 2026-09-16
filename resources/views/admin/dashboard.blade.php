<x-layouts.shop :title="'Admin dashboard — Bloom & Petal'">
    <x-admin-nav />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-rose-100 rounded-2xl p-5 shadow-sm">
                <p class="text-sm text-gray-500">Active products</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $activeProducts }} <span class="text-sm font-medium text-gray-400">/ {{ $totalProducts }}</span></p>
            </div>
            <div class="bg-white border border-rose-100 rounded-2xl p-5 shadow-sm">
                <p class="text-sm text-gray-500">Pending orders</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $pendingOrders }} <span class="text-sm font-medium text-gray-400">/ {{ $totalOrders }}</span></p>
            </div>
            <div class="bg-white border border-rose-100 rounded-2xl p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total orders</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalOrders }}</p>
            </div>
            <div class="bg-white border border-rose-100 rounded-2xl p-5 shadow-sm">
                <p class="text-sm text-gray-500">Customers</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalUsers }}</p>
            </div>
        </div>

        <div class="mt-8 bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-rose-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Recent orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-rose-600 hover:text-rose-500 font-medium">View all</a>
            </div>
            @if ($recentOrders->isEmpty())
                <p class="p-6 text-sm text-gray-500">No orders yet.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="px-6 py-3">Order</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-3 font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $order->user->name }}</td>
                                <td class="px-6 py-3 font-medium">${{ $order->total_amount }}</td>
                                <td class="px-6 py-3">
                                    <span class="text-xs font-medium bg-rose-50 text-rose-700 rounded-full px-2.5 py-1 capitalize">{{ str_replace('_', ' ', $order->status) }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-rose-600 hover:text-rose-500 font-medium">Manage</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-layouts.shop>