<x-layouts.shop :title="$product->name.' — Bloom & Petal'">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-rose-600">&larr; Back to shop</a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mt-6">
            <div class="aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-rose-100 via-pink-100 to-amber-50 flex items-center justify-center shadow-sm border border-rose-100">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <svg class="h-40 w-40 text-rose-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1">
                        <circle cx="12" cy="12" r="3"/>
                        <path stroke-linecap="round" d="M12 2.5c1.5 2.5 1.5 5.5 0 8M12 21.5c-1.5-2.5-1.5-5.5 0-8M2.5 12c2.5-1.5 5.5-1.5 8 0M21.5 12c-2.5 1.5-5.5 1.5-8 0"/>
                        <path stroke-linecap="round" d="M6.7 6.7c2.5-.4 4.9.8 6.2 3M17.3 17.3c-2.5.4-4.9-.8-6.2-3M6.7 17.3c-.4-2.5.8-4.9 3-6.2M17.3 6.7c.4 2.5-.8 4.9-3 6.2"/>
                    </svg>
                @endif
            </div>

            <div>
                <span class="text-xs font-semibold uppercase tracking-wide text-rose-500">{{ $product->category->name }}</span>
                <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $product->name }}</h1>
                <p class="text-gray-600 mt-3 leading-relaxed">{{ $product->description }}</p>

                <div class="flex flex-wrap gap-1.5 mt-4">
                    @foreach ($product->occasions as $occasion)
                        <span class="text-xs text-rose-600 bg-rose-50 rounded-full px-2.5 py-1">{{ $occasion->name }}</span>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <span class="text-3xl font-bold text-gray-900">${{ $product->price }}</span>
                    @if ($reviewCount > 0)
                        <span class="text-sm text-amber-600 flex items-center gap-1">
                            <span class="text-amber-500">&#9733;</span> {{ number_format((float) $avgRating, 1) }}
                            <span class="text-gray-400">({{ $reviewCount }} review{{ $reviewCount === 1 ? '' : 's' }})</span>
                        </span>
                    @endif
                    @if ($product->stock_quantity > 0)
                        <span class="text-sm text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">{{ $product->stock_quantity }} left in stock</span>
                    @else
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Out of stock</span>
                    @endif
                </div>

                @guest
                    <div class="mt-8 bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
                        <h2 class="font-semibold text-gray-900">Want to order this bouquet?</h2>
                        <p class="text-sm text-gray-500 mt-2 mb-4">
                            Create a free account or log in to add it to your cart and arrange delivery.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="bg-rose-600 hover:bg-rose-500 text-white px-5 py-2.5 rounded-lg font-medium text-sm">
                                Sign up
                            </a>
                            <a href="{{ route('login') }}" class="border border-rose-200 text-rose-700 hover:bg-rose-50 px-5 py-2.5 rounded-lg font-medium text-sm">
                                Log in
                            </a>
                        </div>
                    </div>
                @endguest

                @auth
                    @if ($product->stock_quantity > 0)
                        <form method="POST" action="{{ route('cart.add') }}" class="mt-8 bg-white border border-rose-100 rounded-2xl p-6 shadow-sm space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <h2 class="font-semibold text-gray-900">Customize your bouquet</h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input id="quantity" name="quantity" type="number" min="1" max="{{ $product->stock_quantity }}" value="1" required
                                           class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                                </div>
                            </div>

                            @if ($bouquetOptions->isNotEmpty())
                                <div>
                                    <span class="block text-sm font-medium text-gray-700 mb-2">Add-ons <span class="text-gray-400">(optional)</span></span>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach ($bouquetOptions as $option)
                                            <label class="flex items-center gap-2 text-sm border border-gray-200 rounded-lg px-3 py-2 cursor-pointer hover:border-rose-300">
                                                <input type="checkbox" name="addon_ids[]" value="{{ $option->id }}" data-addon-price="{{ $option->price }}"
                                                       class="rounded border-gray-300 text-rose-600 focus:ring-rose-500 addon-checkbox">
                                                <span class="flex-1 text-gray-700">{{ $option->name }}</span>
                                                <span class="text-gray-500 font-medium">+${{ $option->price }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-2">
                                <p class="text-sm text-gray-500">Total: <span class="font-bold text-gray-900" id="estimated-total">${{ $product->price }}</span></p>
                                <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white px-6 py-2.5 rounded-lg font-medium text-sm">
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
                        <div class="mt-8 bg-white border border-rose-100 rounded-2xl p-6 shadow-sm">
                            <p class="text-gray-500">This item is currently out of stock.</p>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <div class="mt-14 max-w-3xl">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Reviews</h2>

            @auth
                <div class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">{{ $userReview ? 'Update your review' : 'Leave a review' }}</h3>
                    <form method="POST" action="{{ route('reviews.store') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                            <select id="rating" name="rating" required class="mt-1 block w-full sm:w-48 rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" @selected($userReview?->rating === $i)>{{ $i }} star{{ $i === 1 ? '' : 's' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
                            <textarea id="comment" name="comment" rows="3" maxlength="1000"
                                      class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">{{ $userReview?->comment }}</textarea>
                        </div>
                        <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white px-5 py-2 rounded-lg font-medium text-sm">
                            {{ $userReview ? 'Update review' : 'Submit review' }}
                        </button>
                    </form>
                </div>
            @else
                <p class="text-sm text-gray-500 mb-6">
                    <a href="{{ route('login') }}" class="text-rose-600 hover:text-rose-500">Log in</a> to leave a review.
                </p>
            @endauth

            @if ($reviews->isEmpty())
                <p class="text-gray-500 text-sm">No reviews yet. Be the first to review this bouquet.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($reviews as $review)
                        <li class="bg-white border border-rose-100 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">{{ $review->user->name }}</span>
                                    <span class="text-amber-500 text-sm">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $review->created_at->format('M j, Y') }}</span>
                            </div>
                            @if ($review->comment)
                                <p class="text-gray-600 text-sm mt-2">{{ $review->comment }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.shop>