@php
    $tabs = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
        ['route' => 'admin.products.index', 'label' => 'Products'],
        ['route' => 'admin.customers.index', 'label' => 'Customers'],
        ['route' => 'admin.orders.index', 'label' => 'Orders'],
        ['route' => 'admin.zones.index', 'label' => 'Delivery zones'],
    ];
@endphp

<div class="border-b border-rose-100 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex gap-6 overflow-x-auto py-2">
        @foreach ($tabs as $tab)
            <a href="{{ route($tab['route']) }}"
               class="whitespace-nowrap text-sm font-medium py-2 border-b-2 {{ request()->routeIs($tab['route']) ? 'border-rose-600 text-rose-600' : 'border-transparent text-gray-600 hover:text-rose-600' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</div>