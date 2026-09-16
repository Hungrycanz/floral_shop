<x-layouts.shop :title="'Delivery #'.$order->id.' — Bloom & Petal'">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('courier.deliveries.index') }}" class="text-sm text-gray-500 hover:text-rose-600">&larr; Back to deliveries</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-6">Delivery #{{ $order->id }}</h1>

        <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm mb-6">
            <h2 class="font-semibold text-gray-900 mb-3">Delivery details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Recipient</p>
                    <p class="font-medium text-gray-900">{{ $order->recipient_name }} &middot; {{ $order->recipient_phone }}</p>
                    <p class="text-gray-600">{{ $order->delivery_address }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Deliver by</p>
                    <p class="font-medium text-gray-900">{{ $order->delivery_date->format('M j, Y') }}</p>
                    @if ($order->deliveryZone)
                        <p class="text-gray-600">Zone: {{ $order->deliveryZone->name }}</p>
                    @endif
                    <p class="text-gray-600">Total: <span class="font-semibold text-gray-900">${{ $order->total_amount }}</span></p>
                </div>
            </div>

            <div class="border-t border-gray-100 mt-4 pt-4">
                <h3 class="font-semibold text-gray-900 mb-2">Items</h3>
                <ul class="space-y-1 text-sm">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between gap-2">
                            <span class="text-gray-700">{{ $item->product->name }} <span class="text-gray-400">&times;{{ $item->quantity }}</span></span>
                            <span class="font-medium text-gray-900">${{ $item->subtotal }}</span>
                        </li>
                    @endforeach
                </ul>
                @if ($order->card_message)
                    <p class="text-sm text-gray-600 mt-3">&ldquo;{{ $order->card_message }}&rdquo;</p>
                @endif
            </div>
        </div>

        <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm mb-6">
            <h2 class="font-semibold text-gray-900 mb-3">Update status</h2>

            @php
                $steps = [
                    'confirmed' => ['label' => 'Confirm order', 'desc' => 'Order confirmed, ready for pickup'],
                    'out_for_pickup' => ['label' => 'Out for pickup', 'desc' => 'En route to the shop'],
                    'out_for_delivery' => ['label' => 'Out for delivery', 'desc' => 'Delivering to customer'],
                    'delivered' => ['label' => 'Mark delivered', 'desc' => 'Handed to the recipient'],
                ];
                $sequence = ['placed', 'confirmed', 'out_for_pickup', 'out_for_delivery', 'delivered'];
                $currentIndex = array_search($order->status, $sequence, true);
                $nextStatus = $currentIndex === false ? null : ($sequence[$currentIndex + 1] ?? null);
                $activeIndex = $currentIndex === false ? -1 : $currentIndex;
            @endphp

            <ol class="space-y-3">
                @foreach ($steps as $status => $step)
                    @php
                        $index = array_search($status, array_keys($steps), true);
                        $done = $index <= $activeIndex;
                        $isNext = $status === $nextStatus;
                    @endphp
                    <li class="flex items-center gap-3 {{ $done || $isNext ? '' : 'opacity-45' }}">
                        <span class="h-3 w-3 rounded-full {{ $done ? 'bg-emerald-500' : 'border-2 border-gray-300' }}"></span>
                        <div class="flex-1">
                            <p class="text-sm font-medium {{ $done ? 'text-emerald-700' : 'text-gray-700' }}">{{ $step['label'] }}</p>
                            <p class="text-xs text-gray-500">{{ $step['desc'] }}</p>
                        </div>
                        @if ($isNext)
                            <form method="POST" action="{{ route('courier.deliveries.status', $order) }}"
                                  onsubmit="return {{ $status === 'delivered' ? "confirm('Mark as delivered and complete payment?')" : 'true' }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $status }}">
                                @if (in_array($status, ['out_for_pickup', 'out_for_delivery'], true))
                                    <input type="text" name="location" placeholder="Location" class="rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm me-2">
                                @endif
                                <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg font-medium text-sm">
                                    {{ $step['label'] }}
                                </button>
                            </form>
                        @elseif ($done && $status === 'delivered')
                            <span class="text-xs font-medium text-emerald-700">Completed</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
            <h2 class="font-semibold text-gray-900 mb-3">Tracking log</h2>
            @if ($order->trackingEvents->isEmpty())
                <p class="text-sm text-gray-500">No events recorded yet.</p>
            @else
                <ol class="space-y-3">
                    @foreach ($order->trackingEvents as $event)
                        <li class="flex gap-3">
                            <span class="h-2 w-2 rounded-full bg-rose-500 mt-1.5"></span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $event->statusLabel() }}</p>
                                <p class="text-xs text-gray-400">{{ $event->created_at->format('M j, Y g:i A') }} @if ($event->location) &middot; {{ $event->location }} @endif</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
</x-layouts.shop>