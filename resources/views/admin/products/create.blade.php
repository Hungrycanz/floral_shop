<x-layouts.shop :title="'New product — Admin'">
    <x-admin-nav />

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-rose-600">&larr; Back to products</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-6">New product</h1>

        <form method="POST" action="{{ route('admin.products.store') }}" class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input id="name" name="name" type="text" required maxlength="255" value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input id="price" name="price" type="number" min="0" step="0.01" required value="{{ old('price') }}"
                           class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                </div>
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock</label>
                    <input id="stock_quantity" name="stock_quantity" type="number" min="0" required value="{{ old('stock_quantity', 0) }}"
                           class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                </div>
                <div>
                    <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>
                    <input id="image_url" name="image_url" type="url" maxlength="255" value="{{ old('image_url') }}"
                           class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                </div>
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-700 mb-2">Occasions</span>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach ($occasions as $occasion)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="occasion_ids[]" value="{{ $occasion->id }}" @checked(in_array($occasion->id, old('occasion_ids', []), true))
                                   class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                            {{ $occasion->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" @checked(true) class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                Active (visible in shop)
            </label>

            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white px-6 py-3 rounded-lg font-medium text-sm">
                Create product
            </button>
        </form>
    </div>
</x-layouts.shop>