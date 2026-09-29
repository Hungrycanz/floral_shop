<x-layouts.shop :title="'Cart — Bloom & Petal'">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <p class="text-xs uppercase tracking-widest text-[#c0565a] font-medium mb-1">Shopping</p>
        <h1 class="font-normal text-[#3d2020] mb-8" style="font-family: 'Playfair Display', serif; font-size: 2rem;">Your Cart</h1>

        @if ($lines === [])
            <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl p-14 text-center shadow-sm">
                <svg class="h-12 w-12 mx-auto text-[#e8a0a0] mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
                <p class="text-[#3d2020]/50 mb-5">Your cart is empty.</p>
                <a href="{{ route('home') }}" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-7 py-2.5 rounded-full font-medium text-sm transition-colors">
                    Browse flowers
                </a>
            </div>
        @else
            <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl shadow-sm overflow-hidden">
                <ul class="divide-y divide-[#e8a0a0]/20">
                    @foreach ($lines as $line)
                        <li class="p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex-1">
                                <p class="font-medium text-[#3d2020]" style="font-family: 'Playfair Display', serif;">{{ $line['name'] }}</p>
                                @if ($line['addons'] !== [])
                                    <ul class="text-xs text-[#3d2020]/40 mt-1 space-y-0.5">
                                        @foreach ($line['addons'] as $addon)
                                            <li>+ {{ $addon['name'] }} <span class="text-[#3d2020]/30">(${{ number_format((float) $addon['price'], 2) }})</span></li>
                                        @endforeach
                                    </ul>
                                @endif
                                <p class="text-sm text-[#3d2020]/40 mt-1">${{ number_format((float) $line['unit_price'], 2) }} each</p>
                            </div>

                            <form method="POST" action="{{ route('cart.update', $line['id']) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $line['quantity'] }}" min="1" max="10"
                                       class="w-16 rounded-lg border border-[#e8a0a0]/60 focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-center text-[#3d2020] bg-[#fdf8f4]">
                                <button type="submit" class="text-xs font-medium text-[#c0565a] hover:text-[#a84b4f] transition-colors border border-[#e8a0a0] hover:border-[#c0565a] px-3 py-1.5 rounded-full">
                                    Update
                                </button>
                            </form>

                            <span class="font-semibold text-[#3d2020] w-24 text-right">${{ number_format((float) $line['line_total'], 2) }}</span>

                            <form method="POST" action="{{ route('cart.remove', $line['id']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-[#3d2020]/30 hover:text-[#c0565a] transition-colors font-medium">
                                    Remove
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <div class="border-t border-[#e8a0a0]/20" style="background-color: #fdf0f0;">
                    <div class="px-5 py-4 flex items-center justify-between">
                        <span class="font-medium text-[#3d2020]">Subtotal <span class="text-[#3d2020]/50 font-normal">({{ $count }} item{{ $count === 1 ? '' : 's' }})</span></span>
                        <span class="text-2xl font-semibold text-[#c0565a]">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('home') }}" class="text-sm font-medium text-[#c0565a] hover:text-[#a84b4f] transition-colors">&larr; Continue shopping</a>
                @auth
                    <a href="{{ route('checkout.index') }}" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-8 py-3 rounded-full font-medium text-sm transition-colors">
                        Proceed to checkout &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-8 py-3 rounded-full font-medium text-sm transition-colors">
                        Log in to checkout
                    </a>
                @endauth
            </div>
        @endif
    </div>
</x-layouts.shop>
