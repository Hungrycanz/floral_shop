<x-layouts.shop :title="'Checkout — Bloom & Petal'">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('cart.index') }}" class="text-sm text-gray-500 hover:text-rose-600">&larr; Back to cart</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-6">Checkout</h1>

        <form method="POST" action="{{ route('checkout.place') }}" class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            @csrf
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="font-semibold text-gray-900">Recipient details</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700">Recipient name</label>
                            <input id="recipient_name" name="recipient_name" type="text" required maxlength="100"
                                   value="{{ old('recipient_name', auth()->user()->name) }}"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                        </div>
                        <div>
                            <label for="recipient_phone" class="block text-sm font-medium text-gray-700">Recipient phone</label>
                            <input id="recipient_phone" name="recipient_phone" type="tel" required maxlength="20"
                                   value="{{ old('recipient_phone', auth()->user()->phone) }}"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="delivery_address" class="block text-sm font-medium text-gray-700">Delivery address</label>
                        <textarea id="delivery_address" name="delivery_address" required maxlength="255" rows="2"
                                  class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery date</label>
                            <input id="delivery_date" name="delivery_date" type="date" required min="{{ now()->format('Y-m-d') }}" value="{{ old('delivery_date') }}"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                        </div>
                        <div>
                            <label for="delivery_zone_id" class="block text-sm font-medium text-gray-700">Delivery zone</label>
                            <select id="delivery_zone_id" name="delivery_zone_id" class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                                <option value="">No zone (no delivery fee)</option>
                                @foreach ($deliveryZones as $zone)
                                    <option value="{{ $zone->id }}" data-price="{{ $zone->price }}" @selected(old('delivery_zone_id') == $zone->id)>
                                        {{ $zone->name }} &mdash; ${{ $zone->price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment method</label>
                        <select id="payment_method" name="payment_method" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                            <option value="cash" @selected(old('payment_method') === 'cash')>Cash on delivery</option>
                            <option value="mobile_money" @selected(old('payment_method') === 'mobile_money')>Mobile money</option>
                            <option value="card" @selected(old('payment_method') === 'card')>Card</option>
                        </select>
                    </div>

                    <div>
                        <label for="card_message" class="block text-sm font-medium text-gray-700">Gift card message <span class="text-gray-400">(optional)</span></label>
                        <textarea id="card_message" name="card_message" rows="2" maxlength="255"
                                  class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">{{ old('card_message') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
                    <h2 class="font-semibold text-gray-900 mb-4">Order summary</h2>
                    <ul class="space-y-2 text-sm">
                        @foreach ($lines as $line)
                            <li class="flex justify-between gap-2">
                                <span class="text-gray-700">
                                    {{ $line['name'] }}
                                    <span class="text-gray-400">&times;{{ $line['quantity'] }}</span>
                                    @if ($line['addons'] !== [])
                                        <span class="block text-xs text-gray-400">+ {{ collect($line['addons'])->pluck('name')->join(', ') }}</span>
                                    @endif
                                </span>
                                <span class="font-medium text-gray-900">${{ number_format((float) $line['line_total'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="border-t border-gray-100 mt-4 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Items subtotal</span>
                            <span class="font-medium text-gray-900">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Delivery fee</span>
                            <span class="font-medium text-gray-900" id="delivery-fee">$0.00</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 mt-3 pt-4 flex justify-between items-center">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-gray-900" id="checkout-total">${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="mt-5 w-full bg-rose-600 hover:bg-rose-500 text-white px-6 py-3 rounded-lg font-medium text-sm">
                        Place order
                    </button>
                </div>
            </div>
        </form>

        <script>
            (() => {
                const itemsTotal = {{ $total }};
                const feeEl = document.getElementById('delivery-fee');
                const totalEl = document.getElementById('checkout-total');
                const zoneSelect = document.getElementById('delivery_zone_id');

                const updateTotals = () => {
                    const opt = zoneSelect.selectedOptions[0];
                    const fee = opt && opt.dataset.price ? Number(opt.dataset.price) : 0;
                    feeEl.textContent = '$' + fee.toFixed(2);
                    totalEl.textContent = '$' + (itemsTotal + fee).toFixed(2);
                };

                zoneSelect.addEventListener('change', updateTotals);
            })();
        </script>
    </div>
</x-layouts.shop>