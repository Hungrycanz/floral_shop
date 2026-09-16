<x-layouts.shop :title="'Cart — Bloom & Petal'">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Your cart</h1>

        @if ($lines === [])
            <div class="bg-white border border-rose-100 rounded-2xl p-12 text-center shadow-sm">
                <p class="text-gray-500 mb-4">Your cart is empty.</p>
                <a href="{{ route('home') }}" class="bg-rose-600 hover:bg-rose-500 text-white px-6 py-2.5 rounded-lg font-medium text-sm">
                    Browse flowers
                </a>
            </div>
        @else
            <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
                <ul class="divide-y divide-gray-100">
                    @foreach ($lines as $line)
                        <li class="p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $line['name'] }}</p>
                                @if ($line['addons'] !== [])
                                    <ul class="text-xs text-gray-500 mt-1 space-y-0.5">
                                        @foreach ($line['addons'] as $addon)
                                            <li>+ {{ $addon['name'] }} <span class="text-gray-400">(${{ number_format((float) $addon['price'], 2) }})</span></li>
                                        @endforeach
                                    </ul>
                                @endif
                                <p class="text-sm text-gray-500 mt-1">${{ number_format((float) $line['unit_price'], 2) }} each</p>
                            </div>

                            <form method="POST" action="{{ route('cart.update', $line['id']) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $line['quantity'] }}" min="1" max="10"
                                       class="w-16 rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm text-center">
                                <button type="submit" class="text-sm text-rose-600 hover:text-rose-500 font-medium">Update</button>
                            </form>

                            <span class="font-bold text-gray-900 w-24 text-right">${{ number_format((float) $line['line_total'], 2) }}</span>

                            <form method="POST" action="{{ route('cart.remove', $line['id']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-gray-400 hover:text-red-600 font-medium">Remove</button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <div class="border-t border-gray-100 bg-rose-50/50 px-5 py-4 flex items-center justify-between">
                    <span class="font-medium text-gray-700">Subtotal (<span>{{ $count }}</span> item{{ $count === 1 ? '' : 's' }})</span>
                    <span class="text-2xl font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('home') }}" class="text-sm font-medium text-rose-600 hover:text-rose-500">&larr; Continue shopping</a>
                @auth
                    <a href="{{ route('checkout.index') }}" class="bg-rose-600 hover:bg-rose-500 text-white px-6 py-3 rounded-lg font-medium text-sm">
                        Proceed to checkout &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-rose-600 hover:bg-rose-500 text-white px-6 py-3 rounded-lg font-medium text-sm">
                        Log in to checkout
                    </a>
                @endauth
            </div>
        @endif
    </div>
</x-layouts.shop>