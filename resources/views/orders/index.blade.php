<x-layouts.shop :title="'My orders — Bloom & Petal'">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">My orders</h1>

        @if ($orders->isEmpty())
            <div class="bg-white border border-rose-100 rounded-2xl p-12 text-center shadow-sm">
                <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('home') }}" class="bg-rose-600 hover:bg-rose-500 text-white px-6 py-2.5 rounded-lg font-medium text-sm">
                    Browse flowers
                </a>
            </div>
        @else
            <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
                <ul class="divide-y divide-gray-100">
                    @foreach ($orders as $order)
                        <li class="p-5 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex-1">
                                <a href="{{ route('orders.show', $order) }}" class="font-semibold text-gray-900 hover:text-rose-600">
                                    Order #{{ $order->id }}
                                </a>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $order->order_date->format('M j, Y g:i A') }} &middot; {{ $order->items->sum('quantity') }} item(s)</p>
                                <div class="mt-1">
                                    @php
                                        $statusColor = match ($order->status) {
                                            'delivered' => 'bg-emerald-50 text-emerald-700',
                                            'cancelled' => 'bg-gray-100 text-gray-500',
                                            'out_for_delivery' => 'bg-amber-50 text-amber-700',
                                            default => 'bg-rose-50 text-rose-700',
                                        };
                                    @endphp
                                    <span class="text-xs font-medium {{ $statusColor }} rounded-full px-2.5 py-1 capitalize">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-900">${{ $order->total_amount }}</span>
                                <a href="{{ route('orders.show', $order) }}" class="text-sm text-rose-600 hover:text-rose-500 font-medium">View</a>
                                @if (in_array($order->status, ['placed', 'confirmed'], true))
                                    <a href="{{ route('orders.show', $order) }}#tracking" class="text-sm text-rose-600 hover:text-rose-500 font-medium">Track</a>
                                @elseif ($order->status === 'delivered')
                                    <form method="POST" action="{{ route('orders.reorder', $order) }}">
                                        @csrf
                                        <button type="submit" class="text-sm text-rose-600 hover:text-rose-500 font-medium">Reorder</button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.shop>