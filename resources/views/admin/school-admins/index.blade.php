<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            School Admins
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 text-sm">Total school admins: {{ $schoolAdmins->total() }}</p>
                    <a href="{{ route('admin.school-admins.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add School Admin
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Name</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">School</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schoolAdmins as $admin)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $admin->name }}</td>
                                <td class="py-3 text-gray-600">{{ $admin->email }}</td>
                                <td class="py-3 text-gray-600">{{ $admin->school->name ?? '—' }}</td>
                                <td class="py-3 text-right space-x-2">
                                    <a href="{{ route('admin.school-admins.edit', $admin) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.school-admins.destroy', $admin) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo School Admin delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500">
                                    Kunai school admin thapiyeko chaina.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $schoolAdmins->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>