<x-layouts.shop :title="'Orders — Admin'">
    <x-admin-nav />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Orders</h1>

        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer..." class="rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm flex-1 max-w-sm">
            <select name="status" class="rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                <option value="">All statuses</option>
                @foreach (['placed', 'confirmed', 'out_for_pickup', 'out_for_delivery', 'delivered', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-sm">Filter</button>
        </form>

        <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Courier</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-6 py-3 font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $order->user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $order->courier?->name ?? '—' }}</td>
                            <td class="px-6 py-3 font-medium">${{ $order->total_amount }}</td>
                            <td class="px-6 py-3">
                                <span class="text-xs font-medium bg-rose-50 text-rose-700 rounded-full px-2.5 py-1 capitalize">{{ str_replace('_', ' ', $order->status) }}</span>
                            </td>
                            <td class="px-6 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-rose-600 hover:text-rose-500 font-medium">Manage</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-layouts.shop>