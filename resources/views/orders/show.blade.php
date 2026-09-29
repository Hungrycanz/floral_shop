<x-layouts.shop :title="'Order #'.$order->id.' — Bloom & Petal'">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.orders.index') : route('orders.index') }}"
               class="text-sm text-[#3d2020]/50 hover:text-[#c0565a] transition-colors">
                &larr; Back to orders
            </a>
            @if (in_array($order->status, ['placed', 'confirmed'], true) && $order->user_id === auth()->id())
                <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Cancel this order?')">
                    @csrf
                    <button type="submit" class="text-sm border border-[#e8a0a0] text-[#c0565a] hover:bg-[#fdf0f0] px-4 py-2 rounded-full font-medium transition-colors">
                        Cancel order
                    </button>
                </form>
            @endif
        </div>

        {{-- Order header card --}}
        <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#e8a0a0]/20" style="background-color: #3d2020;">
                <p class="text-xs uppercase tracking-widest text-[#e8a0a0] font-medium mb-1">Order</p>
                <h1 class="font-normal text-white" style="font-family: 'Playfair Display', serif; font-size: 1.5rem;">
                    #{{ $order->id }}
                </h1>
                <p class="text-sm text-white/50 mt-1">
                    {{ $order->order_date->format('M j, Y g:i A') }} &middot;
                    <span class="capitalize text-white/70">{{ str_replace('_', ' ', $order->status) }}</span>
                </p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Delivery info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-[#3d2020]/40 font-medium mb-1.5">Deliver to</p>
                        <p class="font-medium text-[#3d2020]">{{ $order->recipient_name }} &middot; {{ $order->recipient_phone }}</p>
                        <p class="text-[#3d2020]/60 mt-0.5">{{ $order->delivery_address }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-[#3d2020]/40 font-medium mb-1.5">Delivery date</p>
                        <p class="font-medium text-[#3d2020]">{{ $order->delivery_date->format('M j, Y') }}</p>
                        @if ($order->deliveryZone)
                            <p class="text-[#3d2020]/60 mt-0.5">Zone: {{ $order->deliveryZone->name }} (${{ $order->deliveryZone->price }})</p>
                        @endif
                        @if ($order->courier)
                            <p class="text-[#3d2020]/60">Courier: {{ $order->courier->name }}</p>
                        @endif
                        @if ($order->card_message)
                            <p class="text-[#3d2020]/50 mt-2 italic">&ldquo;{{ $order->card_message }}&rdquo;</p>
                        @endif
                    </div>
                </div>

                {{-- Items --}}
                <div class="border-t border-[#e8a0a0]/20 pt-5">
                    <p class="text-xs uppercase tracking-widest text-[#3d2020]/40 font-medium mb-3">Items</p>
                    <ul class="space-y-2.5 text-sm">
                        @foreach ($order->items as $item)
                            <li class="flex justify-between gap-2">
                                <span class="text-[#3d2020]/70">
                                    {{ $item->product->name }}
                                    <span class="text-[#3d2020]/40">&times;{{ $item->quantity }}</span>
                                    @if ($item->addons)
                                        <span class="block text-xs text-[#3d2020]/30 mt-0.5">+ {{ collect($item->addons)->pluck('name')->join(', ') }}</span>
                                    @endif
                                </span>
                                <span class="font-medium text-[#3d2020]">${{ $item->subtotal }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Payment --}}
                <div class="border-t border-[#e8a0a0]/20 pt-5">
                    <p class="text-xs uppercase tracking-widest text-[#3d2020]/40 font-medium mb-3">Payment</p>
                    <ul class="space-y-2.5 text-sm">
                        @foreach ($order->payments as $payment)
                            <li class="flex justify-between items-center gap-2">
                                <span class="text-[#3d2020]/70 capitalize">{{ str_replace('_', ' ', $payment->method) }}</span>
                                <span class="flex items-center gap-3">
                                    <span class="text-[#3d2020] capitalize">{{ $payment->status }}</span>
                                    @if ($payment->status === 'pending' && auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('payments.process', $payment) }}">
                                            @csrf
                                            <button type="submit" class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-full font-medium transition-colors">
                                                Process payment
                                            </button>
                                        </form>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Total --}}
                <div class="border-t border-[#e8a0a0]/20 pt-4 flex justify-between items-center">
                    <span class="font-medium text-[#3d2020]">Total</span>
                    <span class="text-2xl font-semibold text-[#c0565a]">${{ $order->total_amount }}</span>
                </div>
            </div>
        </div>

        {{-- Tracking timeline --}}
        <div id="tracking" class="mt-8 bg-white border border-[#e8a0a0]/40 rounded-2xl shadow-sm p-6">
            <p class="text-xs uppercase tracking-widest text-[#c0565a] font-medium mb-1">Live updates</p>
            <h2 class="font-normal text-[#3d2020] mb-6" style="font-family: 'Playfair Display', serif; font-size: 1.15rem;">Tracking</h2>

            @if ($order->trackingEvents->isEmpty())
                <p class="text-sm text-[#3d2020]/40">No tracking events yet.</p>
            @else
                <ol class="space-y-5">
                    @foreach ($order->trackingEvents as $event)
                        <li class="flex gap-4">
                            <div class="flex flex-col items-center flex-shrink-0">
                                <span class="h-3 w-3 rounded-full mt-0.5" style="background-color: #c0565a;"></span>
                                @if (! $loop->last)
                                    <span class="w-px flex-1 mt-1" style="background-color: #e8a0a0;"></span>
                                @endif
                            </div>
                            <div class="pb-2">
                                <p class="text-sm font-medium text-[#3d2020]">{{ $event->statusLabel() }}</p>
                                <p class="text-xs text-[#3d2020]/40 mt-0.5">{{ $event->created_at->format('M j, Y g:i A') }}</p>
                                @if ($event->location)
                                    <p class="text-xs text-[#3d2020]/50 mt-0.5">Location: {{ $event->location }}</p>
                                @endif
                                @if ($event->note)
                                    <p class="text-xs text-[#3d2020]/50">{{ $event->note }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>

        {{-- Reorder --}}
        @if ($order->status === 'delivered' && $order->user_id === auth()->id())
            <div class="mt-6 flex items-center justify-between bg-[#fdf0f0] border border-[#e8a0a0]/40 rounded-2xl shadow-sm p-5">
                <span class="text-sm text-[#3d2020]/60">Loved it? Order the same arrangement again.</span>
                <form method="POST" action="{{ route('orders.reorder', $order) }}">
                    @csrf
                    <button type="submit" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-6 py-2 rounded-full font-medium text-sm transition-colors">
                        Reorder
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-layouts.shop>
