<x-layouts.shop :title="$product->name.' — Bloom & Petal'">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('home') }}" class="text-sm text-[#3d2020]/50 hover:text-[#c0565a] transition-colors inline-flex items-center gap-1">
            &larr; Back to shop
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-8">

            {{-- Product image --}}
            <div class="rounded-2xl overflow-hidden border border-[#e8a0a0]/40 shadow-sm" style="aspect-ratio: 3/4; background: linear-gradient(135deg, #fdf0f0 0%, #f9d5d5 60%, #fdf8f4 100%);">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="h-40 w-40 text-[#e8a0a0]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1">
                            <circle cx="12" cy="12" r="2.5"/>
                            <path stroke-linecap="round" d="M12 3c1.2 2 1.2 4.5 0 6.5M12 21c-1.2-2-1.2-4.5 0-6.5M3 12c2-1.2 4.5-1.2 6.5 0M21 12c-2 1.2-4.5 1.2-6.5 0"/>
                            <path stroke-linecap="round" d="M6.3 6.3c2-.3 3.9.6 5 2.4M17.7 17.7c-2 .3-3.9-.6-5-2.4M6.3 17.7c-.3-2 .6-3.9 2.4-5M17.7 6.3c.3 2-.6 3.9-2.4 5"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Product info --}}
            <div class="flex flex-col">
                <p class="text-xs uppercase tracking-widest text-[#c0565a] font-medium">{{ $product->category->name }}</p>
                <h1 class="font-normal text-[#3d2020] mt-2 leading-tight" style="font-family: 'Playfair Display', serif; font-size: 2.25rem;">{{ $product->name }}</h1>
                <p class="text-[#3d2020]/60 mt-4 leading-relaxed">{{ $product->description }}</p>

                @if ($product->occasions->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-5">
                        @foreach ($product->occasions as $occasion)
                            <span class="text-xs text-[#c0565a] bg-[#fdf0f0] rounded-full px-3 py-1 font-medium">{{ $occasion->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6 flex items-center gap-4 flex-wrap">
                    <span class="text-3xl font-semibold text-[#c0565a]">${{ $product->price }}</span>
                    @if ($reviewCount > 0)
                        <span class="text-sm text-amber-600 flex items-center gap-1">
                            <span class="text-amber-500">&#9733;</span> {{ number_format((float) $avgRating, 1) }}
                            <span class="text-[#3d2020]/40">({{ $reviewCount }} review{{ $reviewCount === 1 ? '' : 's' }})</span>
                        </span>
                    @endif
                    @if ($product->stock_quantity > 0)
                        <span class="text-xs text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full font-medium">{{ $product->stock_quantity }} left in stock</span>
                    @else
                        <span class="text-xs text-[#3d2020]/40 bg-[#fdf8f4] border border-[#e8a0a0]/40 px-3 py-1 rounded-full">Out of stock</span>
                    @endif
                </div>

                @guest
                    <div class="mt-8 bg-white border border-[#e8a0a0]/40 rounded-2xl p-6 shadow-sm">
                        <h2 class="font-normal text-[#3d2020] text-lg" style="font-family: 'Playfair Display', serif;">Want to order this bouquet?</h2>
                        <p class="text-sm text-[#3d2020]/50 mt-2 mb-5 leading-relaxed">
                            Create a free account or log in to add it to your cart and arrange delivery.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-6 py-2.5 rounded-full font-medium text-sm transition-colors">
                                Sign up
                            </a>
                            <a href="{{ route('login') }}" class="border border-[#e8a0a0] text-[#3d2020] hover:bg-[#fdf0f0] px-6 py-2.5 rounded-full font-medium text-sm transition-colors">
                                Log in
                            </a>
                        </div>
                    </div>
                @endguest

                @auth
                    @if ($product->stock_quantity > 0)
                        <form method="POST" action="{{ route('cart.add') }}" class="mt-8 bg-[#fdf8f4] border border-[#e8a0a0]/40 rounded-2xl p-6 shadow-sm space-y-5">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <h2 class="font-normal text-[#3d2020] text-lg" style="font-family: 'Playfair Display', serif;">Customize your bouquet</h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="quantity" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Quantity</label>
                                    <input id="quantity" name="quantity" type="number" min="1" max="{{ $product->stock_quantity }}" value="1" required
                                           class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-white focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                                </div>
                            </div>

                            @if ($bouquetOptions->isNotEmpty())
                                <div>
                                    <span class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-2">Add-ons <span class="text-[#3d2020]/30 normal-case tracking-normal">(optional)</span></span>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach ($bouquetOptions as $option)
                                            <label class="flex items-center gap-2 text-sm border border-[#e8a0a0]/60 bg-white rounded-xl px-3 py-2.5 cursor-pointer hover:border-[#c0565a]/50 transition-colors">
                                                <input type="checkbox" name="addon_ids[]" value="{{ $option->id }}" data-addon-price="{{ $option->price }}"
                                                       class="rounded border-[#e8a0a0] text-[#c0565a] focus:ring-[#c0565a] addon-checkbox">
                                                <span class="flex-1 text-[#3d2020]">{{ $option->name }}</span>
                                                <span class="text-[#c0565a] font-medium">+${{ $option->price }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-1">
                                <p class="text-sm text-[#3d2020]/50">Total: <span class="font-semibold text-[#c0565a]" id="estimated-total">${{ $product->price }}</span></p>
                                <button type="submit" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-7 py-2.5 rounded-full font-medium text-sm transition-colors">
                                    Add to cart
                                </button>
                            </div>
                        </form>

                        <script>
                            (() => {
                                const qtyInput = document.getElementById('quantity');
                                const totalEl = document.getElementById('estimated-total');
                                const basePrice = {{ $product->price }};

                                const updateTotal = () => {
                                    const qty = Math.max(1, Number(qtyInput.value) || 1);
                                    const addons = document.querySelectorAll('.addon-checkbox:checked');
                                    let addonTotal = 0;
                                    addons.forEach(el => addonTotal += Number(el.dataset.addonPrice) || 0);
                                    totalEl.textContent = '$' + (qty * (basePrice + addonTotal)).toFixed(2);
                                };

                                qtyInput.addEventListener('input', updateTotal);
                                document.querySelectorAll('.addon-checkbox').forEach(el => el.addEventListener('change', updateTotal));
                            })();
                        </script>
                    @else
                        <div class="mt-8 bg-white border border-[#e8a0a0]/40 rounded-2xl p-6 shadow-sm">
                            <p class="text-[#3d2020]/50">This item is currently out of stock.</p>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Reviews section --}}
        <div class="mt-16 max-w-3xl">
            <p class="text-xs uppercase tracking-widest text-[#c0565a] font-medium mb-1">Feedback</p>
            <h2 class="font-normal text-[#3d2020] mb-8" style="font-family: 'Playfair Display', serif; font-size: 1.5rem;">Customer Reviews</h2>

            @auth
                <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl p-6 shadow-sm mb-6">
                    <h3 class="font-normal text-[#3d2020] mb-4" style="font-family: 'Playfair Display', serif;">{{ $userReview ? 'Update your review' : 'Leave a review' }}</h3>
                    <form method="POST" action="{{ route('reviews.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div>
                            <label for="rating" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Rating</label>
                            <select id="rating" name="rating" required
                                    class="block w-full sm:w-48 rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" @selected($userReview?->rating === $i)>{{ $i }} star{{ $i === 1 ? '' : 's' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="comment" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Comment</label>
                            <textarea id="comment" name="comment" rows="3" maxlength="1000"
                                      class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">{{ $userReview?->comment }}</textarea>
                        </div>
                        <button type="submit" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-6 py-2.5 rounded-full font-medium text-sm transition-colors">
                            {{ $userReview ? 'Update review' : 'Submit review' }}
                        </button>
                    </form>
                </div>
            @else
                <p class="text-sm text-[#3d2020]/50 mb-6">
                    <a href="{{ route('login') }}" class="text-[#c0565a] hover:text-[#a84b4f] transition-colors">Log in</a> to leave a review.
                </p>
            @endauth

            @if ($reviews->isEmpty())
                <p class="text-[#3d2020]/40 text-sm">No reviews yet. Be the first to review this arrangement.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($reviews as $review)
                        <li class="bg-white border border-[#e8a0a0]/40 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#c0565a] flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-sm font-medium">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <span class="font-medium text-[#3d2020] text-sm">{{ $review->user->name }}</span>
                                        <span class="text-xs text-[#3d2020]/40">{{ $review->created_at->format('M j, Y') }}</span>
                                    </div>
                                    <div class="mt-0.5 text-amber-400 text-sm tracking-tight">
                                        {{ str_repeat('★', $review->rating) }}<span class="text-amber-200">{{ str_repeat('★', 5 - $review->rating) }}</span>
                                    </div>
                                    @if ($review->comment)
                                        <p class="text-[#3d2020]/60 text-sm mt-2 leading-relaxed">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.shop>
