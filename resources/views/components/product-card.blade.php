@props(['product'])

<a href="{{ route('products.show', $product) }}"
   class="group bg-white rounded-2xl overflow-hidden border border-[#e8a0a0]/40 shadow-sm hover:-translate-y-1 hover:shadow-lg transition-all duration-200 flex flex-col">

    {{-- Image area --}}
    <div class="relative overflow-hidden" style="aspect-ratio: 3/4;">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #fdf0f0 0%, #f9d5d5 50%, #fdf8f4 100%);">
                <svg class="h-20 w-20 text-[#e8a0a0]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                    <circle cx="12" cy="12" r="2.5"/>
                    <path stroke-linecap="round" d="M12 3c1.2 2 1.2 4.5 0 6.5M12 21c-1.2-2-1.2-4.5 0-6.5M3 12c2-1.2 4.5-1.2 6.5 0M21 12c-2 1.2-4.5 1.2-6.5 0"/>
                    <path stroke-linecap="round" d="M6.3 6.3c2-.3 3.9.6 5 2.4M17.7 17.7c-2 .3-3.9-.6-5-2.4M6.3 17.7c-.3-2 .6-3.9 2.4-5M17.7 6.3c.3 2-.6 3.9-2.4 5"/>
                </svg>
            </div>
        @endif

        {{-- Gradient overlay --}}
        <div class="absolute inset-x-0 bottom-0 h-16 pointer-events-none" style="background: linear-gradient(to top, rgba(61,32,32,0.15), transparent);"></div>

        {{-- Category badge --}}
        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-[#c0565a] text-[10px] uppercase tracking-widest font-medium px-2.5 py-1 rounded-full">
            {{ $product->category->name }}
        </span>

        {{-- Stock badge --}}
        @if ($product->stock_quantity > 0)
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-emerald-700 text-[10px] font-medium px-2.5 py-1 rounded-full">
                In stock
            </span>
        @else
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-[#3d2020]/40 text-[10px] font-medium px-2.5 py-1 rounded-full">
                Out of stock
            </span>
        @endif
    </div>

    {{-- Card body --}}
    <div class="p-4 flex flex-col gap-2 flex-1">
        <h3 class="font-normal text-[#3d2020] leading-snug" style="font-family: 'Playfair Display', serif; font-size: 1rem;">{{ $product->name }}</h3>

        <p class="text-sm text-[#3d2020]/50 line-clamp-2 leading-relaxed">{{ $product->description }}</p>

        @if ($product->occasions->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 pt-1">
                @foreach ($product->occasions as $occasion)
                    <span class="text-[10px] text-[#c0565a] bg-[#fdf0f0] rounded-full px-2.5 py-0.5 font-medium">{{ $occasion->name }}</span>
                @endforeach
            </div>
        @endif

        <div class="mt-auto pt-3 flex items-center justify-between gap-2">
            <span class="text-lg font-semibold text-[#c0565a]">${{ $product->price }}</span>
            <span class="text-xs border border-[#e8a0a0] text-[#3d2020] group-hover:bg-[#c0565a] group-hover:border-[#c0565a] group-hover:text-white px-4 py-1.5 rounded-full font-medium transition-colors">
                View &amp; Order
            </span>
        </div>
    </div>
</a>
