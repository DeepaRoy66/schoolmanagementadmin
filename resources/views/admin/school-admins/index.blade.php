<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            School Admins
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="flex items-center gap-2 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-xl text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">School Admins</p>
                            <p class="text-xs text-gray-400">{{ $schoolAdmins->total() }} {{ Str::plural('admin', $schoolAdmins->total()) }} registered</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.school-admins.create') }}"
                       class="inline-flex items-center justify-center gap-1.5 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800 active:scale-[0.98] transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add School Admin
                    </a>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50/60 text-gray-500 text-xs uppercase tracking-wide">
                                <th class="py-3 px-6 font-medium">Name</th>
                                <th class="py-3 px-6 font-medium">Email</th>
                                <th class="py-3 px-6 font-medium">School</th>
                                <th class="py-3 px-6 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($schoolAdmins as $admin)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-xs font-semibold shrink-0">
                                                {{ Str::substr($admin->name, 0, 1) }}
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $admin->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ $admin->email }}</td>
                                    <td class="py-4 px-6">
                                        @if ($admin->school)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-gray-50 text-gray-600 rounded-full text-xs font-medium">
                                                {{ $admin->school->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('admin.school-admins.edit', $admin) }}"
                                               class="p-2 rounded-lg text-gray-400 hover:text-teal-600 hover:bg-teal-50 transition"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.school-admins.destroy', $admin) }}" method="POST"
                                                  onsubmit="return confirm('Yo School Admin delete garne?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                                        title="Delete">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16">
                                        <div class="flex flex-col items-center gap-2 text-center">
                                            <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">Kunai school admin thapiyeko chaina</p>
                                            <p class="text-xs text-gray-400">"Add School Admin" click garera suru garnus.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($schoolAdmins->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $schoolAdmins->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>