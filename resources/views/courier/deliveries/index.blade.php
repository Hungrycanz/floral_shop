<x-layouts.shop :title="'My deliveries — Bloom & Petal'">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My deliveries</h1>
            <span class="text-sm bg-emerald-50 text-emerald-700 rounded-full px-3 py-1.5 font-medium">
                {{ $completedToday }} delivered today
            </span>
        </div>

        @if ($orders->isEmpty())
            <div class="bg-white border border-rose-100 rounded-2xl p-12 text-center shadow-sm">
                <p class="text-gray-500">No deliveries assigned yet. Check back soon.</p>
            </div>
        @else
            <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
                <ul class="divide-y divide-gray-100">
                    @foreach ($orders as $order)
                        <li class="p-5 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex-1">
                                <a href="{{ route('courier.deliveries.show', $order) }}" class="font-semibold text-gray-900 hover:text-rose-600">
                                    Delivery #{{ $order->id }}
                                </a>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    {{ $order->user->name }} &middot; {{ $order->delivery_address }}
                                </p>
                                @if ($order->deliveryZone)
                                    <p class="text-xs text-gray-400 mt-0.5">Zone: {{ $order->deliveryZone->name }} &middot; Deliver by {{ $order->delivery_date->format('M j') }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-medium bg-amber-50 text-amber-700 rounded-full px-2.5 py-1 capitalize">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                                <span class="font-bold text-gray-900">${{ $order->total_amount }}</span>
                                <a href="{{ route('courier.deliveries.show', $order) }}" class="text-sm text-rose-600 hover:text-rose-500 font-medium">Start</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-layouts.shop>