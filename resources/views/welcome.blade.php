<x-layouts.shop :title="'Bloom & Petal — Fresh Flowers'">

    {{-- Hero --}}
    <section style="background-color: #fdf0f0;" class="border-b border-[#e8a0a0]/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">
            <p class="text-xs uppercase tracking-widest text-[#c0565a] font-medium mb-4">Handcrafted with Love</p>
            <h1 class="font-playfair text-4xl sm:text-5xl lg:text-6xl font-normal text-[#3d2020] leading-tight" style="font-family: 'Playfair Display', serif;">
                Fresh blooms, <em>delivered</em><br class="hidden sm:block"> to your door
            </h1>
            <p class="mt-5 text-base text-[#3d2020]/60 max-w-xl mx-auto leading-relaxed">
                Hand-tied bouquets, elegant arrangements, and potted plants for every occasion.
            </p>
            @guest
                <div class="mt-8 flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('register') }}" class="bg-[#c0565a] hover:bg-[#a84b4f] text-white px-7 py-3 rounded-full font-medium text-sm transition-colors">
                        Start ordering
                    </a>
                    <a href="{{ route('login') }}" class="border border-[#e8a0a0] text-[#3d2020] hover:bg-[#fdf8f4] px-7 py-3 rounded-full font-medium text-sm transition-colors">
                        Log in
                    </a>
                </div>
            @endguest
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Section header --}}
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-widest text-[#c0565a] font-medium mb-1">Our Collection</p>
                <h2 class="font-playfair text-2xl font-normal text-[#3d2020]" style="font-family: 'Playfair Display', serif;">Fresh Arrangements</h2>
            </div>
            @guest
                <a href="{{ route('register') }}" class="text-sm font-medium text-[#c0565a] hover:text-[#a84b4f] transition-colors hidden sm:inline">
                    Create an account to order &rarr;
                </a>
            @endguest
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('home') }}" class="bg-white border border-[#e8a0a0]/40 rounded-2xl p-5 shadow-sm mb-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="search" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Search</label>
                    <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Roses, lilies…"
                           class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020] placeholder-[#3d2020]/30">
                </div>
                <div>
                    <label for="category" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Category</label>
                    <select id="category" name="category"
                            class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="occasion" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Occasion</label>
                    <select id="occasion" name="occasion"
                            class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                        <option value="">All occasions</option>
                        @foreach ($occasions as $occasion)
                            <option value="{{ $occasion->id }}" @selected(request('occasion') == $occasion->id)>{{ $occasion->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="min_price" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Min $</label>
                        <input id="min_price" type="number" name="min_price" value="{{ request('min_price') }}" min="0" step="0.01" placeholder="0"
                               class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                    </div>
                    <div>
                        <label for="max_price" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Max $</label>
                        <input id="max_price" type="number" name="max_price" value="{{ request('max_price') }}" min="0" step="0.01" placeholder="∞"
                               class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex-1 bg-[#c0565a] hover:bg-[#a84b4f] text-white px-4 py-2.5 rounded-full font-medium text-sm transition-colors text-center">
                        Search
                    </button>
                    @if (request()->anyFilled(['search', 'category', 'occasion', 'min_price', 'max_price']))
                        <a href="{{ route('home') }}" class="text-sm font-medium text-[#c0565a] hover:text-[#a84b4f] transition-colors whitespace-nowrap">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Product grid --}}
        @if ($products->isEmpty())
            <div class="bg-white border border-[#e8a0a0]/40 rounded-2xl p-12 text-center shadow-sm">
                <p class="text-[#3d2020]/50">No products match your filters. Check back soon.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @each('components.product-card', $products, 'product')
            </div>
        @endif
    </section>
</x-layouts.shop>
