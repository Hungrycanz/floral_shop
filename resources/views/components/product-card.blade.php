@props(['product'])

<a href="{{ route('products.show', $product) }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg overflow-hidden transition-shadow border border-rose-100 flex flex-col">
    <div class="aspect-square overflow-hidden bg-gradient-to-br from-rose-100 via-pink-100 to-amber-50 flex items-center justify-center">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
        @else
            <svg class="h-20 w-20 text-rose-300 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <circle cx="12" cy="12" r="3"/>
                <path stroke-linecap="round" d="M12 2.5c1.5 2.5 1.5 5.5 0 8M12 21.5c-1.5-2.5-1.5-5.5 0-8M2.5 12c2.5-1.5 5.5-1.5 8 0M21.5 12c-2.5 1.5-5.5 1.5-8 0"/>
                <path stroke-linecap="round" d="M6.7 6.7c2.5-.4 4.9.8 6.2 3M17.3 17.3c-2.5.4-4.9-.8-6.2-3M6.7 17.3c-.4-2.5.8-4.9 3-6.2M17.3 6.7c.4 2.5-.8 4.9-3 6.2"/>
            </svg>
        @endif
    </div>

    <div class="p-5 flex flex-col gap-2 flex-1">
        <div class="flex items-center justify-between gap-2">
            <span class="text-xs font-semibold uppercase tracking-wide text-rose-500">{{ $product->category->name }}</span>
            @if ($product->stock_quantity > 0)
                <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">In stock</span>
            @else
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">Out of stock</span>
            @endif
        </div>

        <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>

        <p class="text-sm text-gray-500 line-clamp-2">{{ $product->description }}</p>

        <div class="flex flex-wrap gap-1.5 pt-1">
            @foreach ($product->occasions as $occasion)
                <span class="text-xs text-rose-600 bg-rose-50 rounded-full px-2 py-0.5">{{ $occasion->name }}</span>
            @endforeach
        </div>

        <div class="mt-auto pt-3 flex items-center justify-between">
            <span class="text-lg font-bold text-gray-900">${{ $product->price }}</span>
            <span class="text-sm bg-rose-600 group-hover:bg-rose-500 text-white px-4 py-2 rounded-lg font-medium">
                View &amp; Order
            </span>
        </div>
    </div>
</a>