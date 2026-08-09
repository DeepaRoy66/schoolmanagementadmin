<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            School Admins
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Breadcrumb heading, same style as Schools index --}}
            <h1 class="text-2xl font-bold text-gray-800">
                School Admins <span class="text-gray-400 font-normal">&raquo; List of all School Admins</span>
            </h1>

            @if (session('status'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden">

                {{-- Search + New button --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-200">
                    <form action="{{ route('admin.school-admins.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by name or email"
                               class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-700 placeholder-gray-400 outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 w-64">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700 transition">
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('admin.school-admins.index') }}"
                               class="text-sm text-gray-500 hover:text-gray-700 transition">
                                Clear
                            </a>
                        @endif
                    </form>

                    <a href="{{ route('admin.school-admins.create') }}"
                       class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        New
                    </a>
                </div>

                <p class="px-6 pt-4 text-sm text-gray-500">
                    {{ $schoolAdmins->total() }} {{ Str::plural('admin', $schoolAdmins->total()) }} found
                </p>


                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-600">
                                <th class="py-3 px-6 font-semibold">S.No.</th>
                                <th class="py-3 px-6 font-semibold">Name</th>
                                <th class="py-3 px-6 font-semibold">Email</th>
                                <th class="py-3 px-6 font-semibold">School</th>
                                <th class="py-3 px-6 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($schoolAdmins as $admin)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 text-gray-500">
                                        {{ $loop->iteration + ($schoolAdmins->currentPage() - 1) * $schoolAdmins->perPage() }}
                                    </td>
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        {{ $admin->name }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ $admin->email }}</td>
                                    <td class="py-4 px-6">
                                        @if ($admin->school)
                                            <span class="text-gray-700">{{ $admin->school->name }}</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.school-admins.edit', $admin) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-semibold
                                                      bg-yellow-500 text-white hover:bg-yellow-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.school-admins.destroy', $admin) }}" method="POST"
                                                  onsubmit="return confirm('Yo School Admin delete garne?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-semibold
                                                               bg-red-600 text-white hover:bg-red-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center text-gray-500 text-sm">
                                        @if (request('search'))
                                            No school admins found for "{{ request('search') }}".
                                        @else
                                            No school admins found.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($schoolAdmins->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $schoolAdmins->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>