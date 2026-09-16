<x-layouts.shop :title="'Order #'.$order->id.' — Admin'">
    <x-admin-nav />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-rose-600">&larr; Back to orders</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-6">Order #{{ $order->id }}</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
                    <h2 class="font-semibold text-gray-900 mb-3">Customer</h2>
                    <p class="text-sm">
                        <span class="text-gray-500">Name:</span>
                        <a href="{{ route('admin.customers.show', $order->user) }}" class="text-rose-600 hover:text-rose-500 font-medium">{{ $order->user->name }}</a>
                    </p>
                    <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                    <p class="text-sm text-gray-600 mt-1">Recipient: {{ $order->recipient_name }} &middot; {{ $order->recipient_phone }}</p>
                    <p class="text-sm text-gray-600">{{ $order->delivery_address }}</p>
                    <p class="text-sm text-gray-600 mt-1">Deliver by {{ $order->delivery_date->format('M j, Y') }}</p>
                    @if ($order->card_message)
                        <p class="text-sm text-gray-600 mt-2">&ldquo;{{ $order->card_message }}&rdquo;</p>
                    @endif
                </div>

                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
                    <h2 class="font-semibold text-gray-900 mb-3">Actions</h2>

                    <form method="POST" action="{{ route('admin.orders.assign-courier', $order) }}" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <label for="courier_id" class="block text-sm font-medium text-gray-700 mb-1">Assign courier</label>
                        <div class="flex gap-2">
                            <select id="courier_id" name="courier_id" class="flex-1 rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                                <option value="">No courier</option>
                                @foreach ($couriers as $courier)
                                    <option value="{{ $courier->id }}" @selected($order->courier_id === $courier->id)>{{ $courier->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-sm">Save</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.orders.assign-zone', $order) }}" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <label for="delivery_zone_id" class="block text-sm font-medium text-gray-700 mb-1">Delivery zone</label>
                        <div class="flex gap-2">
                            <select id="delivery_zone_id" name="delivery_zone_id" class="flex-1 rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                                <option value="">No zone</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}" @selected($order->delivery_zone_id === $zone->id)>{{ $zone->name }} — ${{ $zone->price }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-sm">Save</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order status</label>
                        <div class="flex gap-2">
                            <select id="status" name="status" class="flex-1 rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                                @foreach (['placed', 'confirmed', 'out_for_pickup', 'out_for_delivery', 'delivered', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg font-medium text-sm">Update</button>
                        </div>
                    </form>
                </div>

                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
                    <h2 class="font-semibold text-gray-900 mb-3">Tracking</h2>
                    @if ($order->trackingEvents->isEmpty())
                        <p class="text-sm text-gray-500">No tracking events yet.</p>
                    @else
                        <ol class="space-y-3">
                            @foreach ($order->trackingEvents as $event)
                                <li>
                                    <p class="text-sm font-medium text-gray-900">{{ $event->statusLabel() }}</p>
                                    <p class="text-xs text-gray-400">{{ $event->created_at->format('M j, Y g:i A') }} @if ($event->location) &middot; {{ $event->location }} @endif</p>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
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
                        @if ($order->deliveryZone)
                            <li class="flex justify-between gap-2 text-gray-600">
                                <span>Delivery ({{ $order->deliveryZone->name }})</span>
                                <span>${{ $order->deliveryZone->price }}</span>
                            </li>
                        @endif
                    </ul>
                    <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between items-center">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="text-xl font-bold text-gray-900">${{ $order->total_amount }}</span>
                    </div>
                </div>

                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
                    <h2 class="font-semibold text-gray-900 mb-3">Payment</h2>
                    <ul class="space-y-2 text-sm">
                        @foreach ($order->payments as $payment)
                            <li class="flex justify-between items-center gap-2">
                                <span class="text-gray-700 capitalize">{{ str_replace('_', ' ', $payment->method) }}</span>
                                <span class="flex items-center gap-3">
                                    <span class="capitalize {{ $payment->status === 'completed' ? 'text-emerald-700' : ($payment->status === 'failed' ? 'text-red-600' : 'text-gray-600') }}">{{ $payment->status }}</span>
                                    @if ($payment->status === 'pending')
                                        <form method="POST" action="{{ route('payments.process', $payment) }}">
                                            @csrf
                                            <button type="submit" class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg font-medium">Process</button>
                                        </form>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.shop>