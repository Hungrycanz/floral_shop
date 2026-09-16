<x-layouts.shop :title="'Products — Admin'">
    <x-admin-nav />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Products</h1>
            <a href="{{ route('admin.products.create') }}" class="bg-rose-600 hover:bg-rose-500 text-white px-5 py-2.5 rounded-lg font-medium text-sm">
                + New product
            </a>
        </div>

        <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm flex-1 max-w-sm">
            <select name="status" class="rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                <option value="">All statuses</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
            <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-sm">Filter</button>
        </form>

        <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3">Product</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3">Stock</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $product->category?->name }}</td>
                            <td class="px-6 py-3">${{ $product->price }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $product->stock_quantity }}</td>
                            <td class="px-6 py-3">
                                @if ($product->is_active)
                                    <span class="text-xs font-medium bg-emerald-50 text-emerald-700 rounded-full px-2.5 py-1">Active</span>
                                @else
                                    <span class="text-xs font-medium bg-gray-100 text-gray-500 rounded-full px-2.5 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 flex gap-3">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-rose-600 hover:text-rose-500 font-medium">Edit</a>
                                @if ($product->is_active)
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Deactivate {{ addslashes($product->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 font-medium">Deactivate</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-layouts.shop>