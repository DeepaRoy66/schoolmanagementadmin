<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Billing Periods</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 text-sm">Total periods: {{ $periods->count() }}</p>
                    <a href="{{ route('school-admin.billing-periods.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Billing Period
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Period Name</th>
                            <th class="py-2">Code</th>
                            <th class="py-2">Hierarchy</th>
                            <th class="py-2">Quantity</th>
                            <th class="py-2">Status</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periods as $period)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $period->name }}</td>
                                <td class="py-3 text-gray-600">{{ $period->code }}</td>
                                <td class="py-3 text-gray-600">{{ $period->hierarchy }}</td>
                                <td class="py-3 text-gray-600">{{ number_format($period->quantity, 2) }}</td>
                                <td class="py-3">
                                    @if ($period->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs font-semibold">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right space-x-2">
                                    <a href="{{ route('school-admin.billing-periods.edit', $period) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('school-admin.billing-periods.destroy', $period) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo billing period delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-500">None of the billing periods found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>