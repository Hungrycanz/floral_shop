<x-layouts.shop :title="'Edit zone — Admin'">
    <x-admin-nav />

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('admin.zones.index') }}" class="text-sm text-gray-500 hover:text-rose-600">&larr; Back to zones</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-6">Edit zone</h1>

        <form method="POST" action="{{ route('admin.zones.update', $zone) }}" class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Zone name</label>
                <input id="name" name="name" type="text" required maxlength="100" value="{{ old('name', $zone->name) }}"
                       class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Delivery fee</label>
                <input id="price" name="price" type="number" min="0" step="0.01" required value="{{ old('price', $zone->price) }}"
                       class="mt-1 block w-full rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $zone->is_active))
                       class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                Active (offered at checkout)
            </label>

            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white px-6 py-3 rounded-lg font-medium text-sm">
                Save changes
            </button>
        </form>
    </div>
</x-layouts.shop>