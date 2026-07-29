<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fee Groups</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 text-sm">Total fee groups: {{ $feeGroups->count() }}</p>
                    <a href="{{ route('school-admin.fee-groups.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Fee Group
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Name</th>
                            <th class="py-2">Code</th>
                            <th class="py-2">Status</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feeGroups as $group)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $group->name }}</td>
                                <td class="py-3 text-gray-600">{{ $group->code }}</td>
                                <td class="py-3">
                                    @if ($group->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs font-semibold">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right space-x-2">
                                    <a href="{{ route('school-admin.fee-groups.edit', $group) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('school-admin.fee-groups.destroy', $group) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo fee group delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500">No fee groups found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>