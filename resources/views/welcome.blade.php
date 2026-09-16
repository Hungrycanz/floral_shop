<x-layouts.shop :title="'Bloom & Petal — Fresh Flowers'">

    <section class="bg-gradient-to-br from-rose-600 via-rose-500 to-pink-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">
            <svg class="h-16 w-16 mx-auto text-rose-100 mb-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <circle cx="12" cy="12" r="3"/>
                <path stroke-linecap="round" d="M12 2.5c1.5 2.5 1.5 5.5 0 8M12 21.5c-1.5-2.5-1.5-5.5 0-8M2.5 12c2.5-1.5 5.5-1.5 8 0M21.5 12c-2.5 1.5-5.5 1.5-8 0"/>
                <path stroke-linecap="round" d="M6.7 6.7c2.5-.4 4.9.8 6.2 3M17.3 17.3c-2.5.4-4.9-.8-6.2-3M6.7 17.3c-.4-2.5.8-4.9 3-6.2M17.3 6.7c.4 2.5-.8 4.9-3 6.2"/>
            </svg>
            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">
                Fresh blooms, delivered to your door
            </h1>
            <p class="mt-4 text-lg text-rose-100 max-w-2xl mx-auto">
                Hand-tied bouquets, elegant arrangements and potted plants for every occasion.
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Our catalog</h2>
                <p class="text-gray-500 text-sm mt-1">Order online, pick your delivery date, and we'll do the rest.</p>
            </div>
            @guest
                <a href="{{ route('register') }}" class="text-sm font-medium text-rose-600 hover:text-rose-500">
                    Create an account to order &rarr;
                </a>
            @endguest
        </div>

        <form method="GET" action="{{ route('home') }}" class="bg-white border border-rose-100 rounded-2xl p-5 shadow-sm mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                    <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Roses, lilies..."
                           class="block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                </div>
                <div>
                    <label for="category" class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                    <select id="category" name="category" class="block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="occasion" class="block text-xs font-medium text-gray-500 mb-1">Occasion</label>
                    <select id="occasion" name="occasion" class="block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                        <option value="">All occasions</option>
                        @foreach ($occasions as $occasion)
                            <option value="{{ $occasion->id }}" @selected(request('occasion') == $occasion->id)>{{ $occasion->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="min_price" class="block text-xs font-medium text-gray-500 mb-1">Min price</label>
                        <input id="min_price" type="number" name="min_price" value="{{ request('min_price') }}" min="0" step="0.01" placeholder="$"
                               class="block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                    </div>
                    <div>
                        <label for="max_price" class="block text-xs font-medium text-gray-500 mb-1">Max price</label>
                        <input id="max_price" type="number" name="max_price" value="{{ request('max_price') }}" min="0" step="0.01" placeholder="$"
                               class="block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg font-medium text-sm">
                        Filter
                    </button>
                    @if (request()->anyFilled(['search', 'category', 'occasion', 'min_price', 'max_price']))
                        <a href="{{ route('home') }}" class="w-full text-sm border border-rose-200 text-rose-700 hover:bg-rose-50 px-4 py-2 rounded-lg font-medium text-center">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>

        @if ($products->isEmpty())
            <div class="bg-white border border-rose-100 rounded-2xl p-12 text-center">
                <p class="text-gray-500">No products match your filters. Check back soon.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @each('components.product-card', $products, 'product')
            </div>
        @endif
    </section>
</x-layouts.shop>