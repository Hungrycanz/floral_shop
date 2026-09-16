<x-layouts.shop :title="'Delivery zones — Admin'">
    <x-admin-nav />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Delivery zones</h1>

        <form method="POST" action="{{ route('admin.zones.store') }}" class="bg-white border border-rose-100 rounded-2xl p-6 shadow-sm mb-8">
            @csrf
            <h2 class="font-semibold text-gray-900 mb-4">Add zone</h2>
            <div class="flex gap-3">
                <input type="text" name="name" placeholder="Zone name" required maxlength="100" value="{{ old('name') }}"
                       class="flex-1 rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                <input type="number" name="price" placeholder="Delivery fee" min="0" step="0.01" required value="{{ old('price') }}"
                       class="w-40 rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm">
                <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white px-5 py-2 rounded-lg font-medium text-sm">Add</button>
            </div>
            @error('name')
                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </form>

        <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Delivery fee</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($zones as $zone)
                        <tr>
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $zone->name }}</td>
                            <td class="px-6 py-3">${{ $zone->price }}</td>
                            <td class="px-6 py-3">
                                @if ($zone->is_active)
                                    <span class="text-xs font-medium bg-emerald-50 text-emerald-700 rounded-full px-2.5 py-1">Active</span>
                                @else
                                    <span class="text-xs font-medium bg-gray-100 text-gray-500 rounded-full px-2.5 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 flex gap-3">
                                <a href="{{ route('admin.zones.edit', $zone) }}" class="text-rose-600 hover:text-rose-500 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.zones.destroy', $zone) }}" onsubmit="return confirm('Delete {{ addslashes($zone->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No delivery zones defined.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.shop>