<x-layouts.shop :title="'Customer — Admin'">
    <x-admin-nav />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-500 hover:text-rose-600">&larr; Back to customers</a>

        <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm mt-4 mb-8">
            <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-gray-600 text-sm mt-1">
                {{ $user->email }} &middot; {{ $user->phone ?? 'No phone' }} &middot; Joined {{ $user->created_at->format('M j, Y') }}
            </p>
        </div>

        <h2 class="text-lg font-bold text-gray-900 mb-4">Orders</h2>
        @if ($orders->isEmpty())
            <div class="bg-white border border-rose-100 rounded-2xl p-8 text-center shadow-sm">
                <p class="text-gray-500 text-sm">This customer has no orders.</p>
            </div>
        @else
            <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="px-6 py-3">Order</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-6 py-3 font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $order->order_date->format('M j, Y g:i A') }}</td>
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
            </div>
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.shop>