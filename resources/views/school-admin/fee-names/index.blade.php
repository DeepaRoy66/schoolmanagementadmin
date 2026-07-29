<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fee Names</h2>
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
                    <p class="text-gray-600 text-sm">Total fee names: {{ $feeNames->count() }}</p>
                    <a href="{{ route('school-admin.fee-names.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Fee Name
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Name</th>
                            <th class="py-2">Code</th>
                            <th class="py-2">Fee Group</th>
                            <th class="py-2">Type</th>
                            <th class="py-2">Status</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feeNames as $fee)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $fee->name }}</td>
                                <td class="py-3 text-gray-600">{{ $fee->code }}</td>
                                <td class="py-3 text-gray-600">{{ $fee->feeGroup->name ?? '—' }}</td>
                                <td class="py-3">
                                    @php
    $typeLabels = [
        'compulsory_regular' => 'CRF - Compulsory/Regular',
        'extra_miscellaneous' => 'EMF - Extra/Misc',
        'optional' => 'OF - Optional',
    ];
@endphp
{{ $typeLabels[$fee->fee_type] ?? $fee->fee_type }}
                                </td>
                                <td class="py-3">
                                    @if ($fee->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs font-semibold">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right space-x-2">
                                    <a href="{{ route('school-admin.fee-names.edit', $fee) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('school-admin.fee-names.destroy', $fee) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo fee name delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-500">
                                    No fee names found. <a href="{{ route('school-admin.fee-names.create') }}" class="text-blue-600 hover:underline">Add a new fee name</a>.
                                    @if (\App\Models\FeeGroup::count() === 0)
                                        <br><span class="text-xs">Make sure to create fee groups first.</span>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>