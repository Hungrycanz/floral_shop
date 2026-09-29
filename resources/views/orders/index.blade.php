<x-layouts.shop :title="'My orders — Bloom & Petal'">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <p class="text-xs uppercase tracking-widest text-[#c0565a] font-medium mb-1">Your history</p>
        <h1 class="font-normal text-[#3d2020] mb-8" style="font-family: 'Playfair Display', serif; font-size: 2rem;">My Orders</h1>

        @if ($orders->isEmpty())
            {{-- Empty state --}}
            <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl p-14 text-center shadow-sm">
                <svg class="h-20 w-20 mx-auto mb-5 text-[#e8a0a0]" viewBox="0 0 80 80" fill="none">
                    <circle cx="40" cy="40" r="38" stroke="#e8a0a0" stroke-width="1.5" fill="#fdf0f0"/>
                    <circle cx="40" cy="40" r="5" stroke="#c0565a" stroke-width="1.5"/>
                    <path stroke="#c0565a" stroke-linecap="round" stroke-width="1.5"
                          d="M40 18c2.5 4 2.5 9 0 13M40 62c-2.5-4-2.5-9 0-13M18 40c4-2.5 9-2.5 13 0M62 40c-4 2.5-9 2.5-13 0"/>
                    <path stroke="#e8a0a0" stroke-linecap="round" stroke-width="1.2"
                          d="M26 26c4-.6 7.8 1.2 10 4.8M54 54c-4 .6-7.8-1.2-10-4.8M26 54c-.6-4 1.2-7.8 4.8-10M54 26c.6 4-1.2 7.8-4.8 10"/>
                </svg>
                <p class="font-normal text-[#3d2020] mb-2" style="font-family: 'Playfair Display', serif; font-size: 1.25rem;">No orders yet</p>
                <p class="text-sm text-[#3d2020]/50 mb-6">Your beautiful arrangements will appear here once you place your first order.</p>
                <a href="{{ route('home') }}" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-7 py-2.5 rounded-full font-medium text-sm transition-colors">
                    Browse flowers
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    @php
                        $statusColor = match ($order->status) {
                            'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'cancelled' => 'bg-[#fdf8f4] text-[#3d2020]/40 border-[#e8a0a0]/30',
                            'out_for_delivery' => 'bg-amber-50 text-amber-700 border-amber-200',
                            default => 'bg-[#fdf0f0] text-[#c0565a] border-[#e8a0a0]/50',
                        };
                    @endphp
                    <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl shadow-sm p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1">
                            <a href="{{ route('orders.show', $order) }}"
                               class="font-normal text-[#3d2020] hover:text-[#c0565a] transition-colors"
                               style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">
                                Order #{{ $order->id }}
                            </a>
                            <p class="text-xs text-[#3d2020]/40 mt-1">{{ $order->order_date->format('M j, Y g:i A') }} &middot; {{ $order->items->sum('quantity') }} item(s)</p>
                            <div class="mt-2">
                                <span class="text-xs font-medium {{ $statusColor }} border rounded-full px-2.5 py-1 capitalize">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-semibold text-[#c0565a]">${{ $order->total_amount }}</span>
                            <a href="{{ route('orders.show', $order) }}" class="text-sm text-[#3d2020] border border-[#e8a0a0] hover:bg-[#fdf0f0] px-4 py-1.5 rounded-full font-medium transition-colors">
                                View
                            </a>
                            @if (in_array($order->status, ['placed', 'confirmed'], true))
                                <a href="{{ route('orders.show', $order) }}#tracking" class="text-sm text-[#c0565a] hover:text-[#a84b4f] font-medium transition-colors">
                                    Track
                                </a>
                            @elseif ($order->status === 'delivered')
                                <form method="POST" action="{{ route('orders.reorder', $order) }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-[#c0565a] hover:text-[#a84b4f] font-medium transition-colors">Reorder</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.shop>
