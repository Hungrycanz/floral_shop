<x-layouts.shop :title="'Checkout — Bloom & Petal'">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('cart.index') }}" class="text-sm text-[#3d2020]/50 hover:text-[#c0565a] transition-colors">&larr; Back to cart</a>
        <p class="text-xs uppercase tracking-widest text-[#c0565a] font-medium mt-6 mb-1">Almost there</p>
        <h1 class="font-normal text-[#3d2020] mb-8" style="font-family: 'Playfair Display', serif; font-size: 2rem;">Checkout</h1>

        <form method="POST" action="{{ route('checkout.place') }}" class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            @csrf

            {{-- Left: form --}}
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl p-6 shadow-sm space-y-5">
                    <h2 class="font-normal text-[#3d2020]" style="font-family: 'Playfair Display', serif; font-size: 1.15rem;">Recipient details</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="recipient_name" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Recipient name</label>
                            <input id="recipient_name" name="recipient_name" type="text" required maxlength="100"
                                   value="{{ old('recipient_name', auth()->user()->name) }}"
                                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                        </div>
                        <div>
                            <label for="recipient_phone" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Recipient phone</label>
                            <input id="recipient_phone" name="recipient_phone" type="tel" required maxlength="20"
                                   value="{{ old('recipient_phone', auth()->user()->phone) }}"
                                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                        </div>
                    </div>

                    <div>
                        <label for="delivery_address" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Delivery address</label>
                        <textarea id="delivery_address" name="delivery_address" required maxlength="255" rows="2"
                                  class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <p class="text-xs text-[#c0565a] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="delivery_date" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Delivery date</label>
                            <input id="delivery_date" name="delivery_date" type="date" required min="{{ now()->format('Y-m-d') }}" value="{{ old('delivery_date') }}"
                                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                        </div>
                        <div>
                            <label for="delivery_zone_id" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Delivery zone</label>
                            <select id="delivery_zone_id" name="delivery_zone_id"
                                    class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
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
                        <label for="payment_method" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Payment method</label>
                        <select id="payment_method" name="payment_method" required
                                class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                            <option value="cash" @selected(old('payment_method') === 'cash')>Cash on delivery</option>
                            <option value="mobile_money" @selected(old('payment_method') === 'mobile_money')>Mobile money</option>
                            <option value="card" @selected(old('payment_method') === 'card')>Card</option>
                        </select>
                    </div>

                    <div>
                        <label for="card_message" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">
                            Gift card message <span class="text-[#3d2020]/30 normal-case tracking-normal">(optional)</span>
                        </label>
                        <textarea id="card_message" name="card_message" rows="2" maxlength="255"
                                  class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">{{ old('card_message') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Right: order summary --}}
            <div class="lg:col-span-2">
                <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl p-6 shadow-sm sticky top-20">
                    <h2 class="font-normal text-[#3d2020] mb-4" style="font-family: 'Playfair Display', serif; font-size: 1.15rem;">Order summary</h2>
                    <ul class="space-y-2.5 text-sm">
                        @foreach ($lines as $line)
                            <li class="flex justify-between gap-2">
                                <span class="text-[#3d2020]/70">
                                    {{ $line['name'] }}
                                    <span class="text-[#3d2020]/40">&times;{{ $line['quantity'] }}</span>
                                    @if ($line['addons'] !== [])
                                        <span class="block text-xs text-[#3d2020]/30">+ {{ collect($line['addons'])->pluck('name')->join(', ') }}</span>
                                    @endif
                                </span>
                                <span class="font-medium text-[#3d2020]">${{ number_format((float) $line['line_total'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="border-t border-[#e8a0a0]/20 mt-4 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-[#3d2020]/60">
                            <span>Items subtotal</span>
                            <span class="font-medium text-[#3d2020]">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-[#3d2020]/60">
                            <span>Delivery fee</span>
                            <span class="font-medium text-[#3d2020]" id="delivery-fee">$0.00</span>
                        </div>
                    </div>

                    <div class="border-t border-[#e8a0a0]/20 mt-3 pt-4 flex justify-between items-center">
                        <span class="font-medium text-[#3d2020]">Total</span>
                        <span class="text-2xl font-semibold text-[#c0565a]" id="checkout-total">${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="mt-5 w-full bg-[#c0565a] hover:bg-[#a84b4f] text-white px-6 py-3 rounded-full font-medium text-sm transition-colors">
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
