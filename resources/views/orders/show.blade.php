<x-layouts.shop :title="'Order #'.$order->id.' — Bloom & Petal'">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.orders.index') : route('orders.index') }}" class="text-sm text-gray-500 hover:text-rose-600">
                &larr; Back to orders
            </a>
            @if (in_array($order->status, ['placed', 'confirmed'], true) && $order->user_id === auth()->id())
                <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Cancel this order?')">
                    @csrf
                    <button type="submit" class="text-sm border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2 rounded-lg font-medium">
                        Cancel order
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 text-white px-6 py-5">
                <h1 class="font-bold text-white">Order #{{ $order->id }}</h1>
                <p class="text-sm text-rose-100">{{ $order->order_date->format('M j, Y g:i A') }} &middot; Status:
                    <span class="font-medium capitalize">{{ str_replace('_', ' ', $order->status) }}</span>
                </p>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Deliver to</p>
                        <p class="font-medium text-gray-900">{{ $order->recipient_name }} &middot; {{ $order->recipient_phone }}</p>
                        <p class="text-gray-600">{{ $order->delivery_address }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Delivery date</p>
                        <p class="font-medium text-gray-900">{{ $order->delivery_date->format('M j, Y') }}</p>
                        @if ($order->deliveryZone)
                            <p class="text-gray-600">Zone: {{ $order->deliveryZone->name }} (${{ $order->deliveryZone->price }})</p>
                        @endif
                        @if ($order->courier)
                            <p class="text-gray-600">Courier: {{ $order->courier->name }}</p>
                        @endif
                        @if ($order->card_message)
                            <p class="text-gray-600 mt-2">&ldquo;{{ $order->card_message }}&rdquo;</p>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h2 class="font-semibold text-gray-900 mb-3">Items</h2>
                    <ul class="space-y-2 text-sm">
                        @foreach ($order->items as $item)
                            <li class="flex justify-between gap-2">
                                <span class="text-gray-700">
                                    {{ $item->product->name }}
                                    <span class="text-gray-400">&times;{{ $item->quantity }}</span>
                                    @if ($item->addons)
                                        <span class="block text-xs text-gray-400">+ {{ collect($item->addons)->pluck('name')->join(', ') }}</span>
                                    @endif
                                </span>
                                <span class="font-medium text-gray-900">${{ $item->subtotal }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h2 class="font-semibold text-gray-900 mb-3">Payment</h2>
                    <ul class="space-y-2 text-sm">
                        @foreach ($order->payments as $payment)
                            <li class="flex justify-between items-center gap-2">
                                <span class="text-gray-700 capitalize">{{ str_replace('_', ' ', $payment->method) }}</span>
                                <span class="flex items-center gap-3">
                                    <span class="text-gray-900 capitalize">{{ $payment->status }}</span>
                                    @if ($payment->status === 'pending' && auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('payments.process', $payment) }}">
                                            @csrf
                                            <button type="submit" class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg font-medium">
                                                Process payment
                                            </button>
                                        </form>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                    <span class="font-medium text-gray-700">Total</span>
                    <span class="text-2xl font-bold text-gray-900">${{ $order->total_amount }}</span>
                </div>
            </div>
        </div>

        <div id="tracking" class="mt-8 bg-white border border-rose-100 rounded-2xl shadow-sm p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Tracking</h2>

            @if ($order->trackingEvents->isEmpty())
                <p class="text-sm text-gray-500">No tracking events yet.</p>
            @else
                <ol class="space-y-4">
                    @foreach ($order->trackingEvents as $event)
                        <li class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <span class="h-2.5 w-2.5 rounded-full bg-rose-500 mt-1.5"></span>
                                @if (! $loop->last)
                                    <span class="w-px flex-1 bg-rose-200 my-1"></span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $event->statusLabel() }}</p>
                                <p class="text-xs text-gray-400">{{ $event->created_at->format('M j, Y g:i A') }}</p>
                                @if ($event->location)
                                    <p class="text-xs text-gray-500">Location: {{ $event->location }}</p>
                                @endif
                                @if ($event->note)
                                    <p class="text-xs text-gray-500">{{ $event->note }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>

        @if ($order->status === 'delivered' && $order->user_id === auth()->id())
            <div class="mt-6 flex items-center justify-between bg-white border border-rose-100 rounded-2xl shadow-sm p-5">
                <span class="text-sm text-gray-600">Received it? Order again in one click.</span>
                <form method="POST" action="{{ route('orders.reorder', $order) }}">
                    @csrf
                    <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white px-5 py-2 rounded-lg font-medium text-sm">
                        Reorder
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-layouts.shop>