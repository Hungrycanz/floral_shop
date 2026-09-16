<x-layouts.shop :title="'Customers — Admin'">
    <x-admin-nav />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Customers</h1>

        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="rounded-lg border border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-sm flex-1 max-w-sm">
            <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-sm">Search</button>
        </form>

        <div class="bg-white border border-rose-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Joined</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($customers as $customer)
                        <tr>
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $customer->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $customer->email }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $customer->phone ?? '—' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $customer->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-3">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="text-rose-600 hover:text-rose-500 font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </div>
</x-layouts.shop>