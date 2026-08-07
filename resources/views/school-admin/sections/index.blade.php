<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <h2 class="font-semibold text-xl text-slate-700">Sections</h2>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">List all sections</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 flex items-center gap-2 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-md text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <a href="{{ route('school-admin.sections.create') }}"
                   class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New
                </a>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                <div class="flex flex-wrap items-center gap-3 px-6 py-5 border-b border-slate-100">
                    <label class="text-sm text-slate-600 font-medium">Search:</label>
                    <form action="{{ route('school-admin.sections.index') }}" method="GET" class="flex items-center gap-2 flex-1 min-w-[260px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by section name..."
                               class="flex-1 max-w-xs px-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('school-admin.sections.index') }}"
                               class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
                        @endif
                    </form>
                </div>

                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 border-b border-slate-200">
                            <th class="py-3 px-6 font-semibold w-20 border-r border-slate-200">S.No.</th>
                            <th class="py-3 px-6 font-semibold border-r border-slate-200">Section name</th>
                            <th class="py-3 px-6 font-semibold w-56">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sections as $section)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-6 text-slate-500 border-r border-slate-100">
                                    {{ $loop->iteration + ($sections->currentPage() - 1) * $sections->perPage() }}
                                </td>
                                <td class="py-3 px-6 border-r border-slate-100">
                                    <span class="font-medium text-blue-700">{{ $section->name }}</span>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('school-admin.sections.edit', $section) }}"
                                           class="inline-flex items-center gap-1.5 bg-amber-500 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-amber-600 transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('school-admin.sections.destroy', $section) }}" method="POST"
                                              onsubmit="return confirm('Delete this section? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-red-700 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                        </svg>
                                        @if (request('search'))
                                            <p class="text-slate-500 text-sm">No sections match "{{ request('search') }}".</p>
                                            <a href="{{ route('school-admin.sections.index') }}" class="text-blue-600 text-sm font-medium hover:underline">Clear search</a>
                                        @else
                                            <p class="text-slate-500 text-sm">No sections added yet.</p>
                                            <a href="{{ route('school-admin.sections.create') }}" class="text-blue-600 text-sm font-medium hover:underline">Add your first section</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($sections->hasPages())
                    <div class="flex justify-center px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $sections->appends(['search' => request('search')])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>