<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Classes</h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-3">
                <span>Dashboard</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-700 font-medium">Classes</span>
            </div>

            {{-- Page header --}}
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h1 class="text-2xl font-semibold text-gray-900 min-w-0 truncate">Classes</h1>
                    <a href="{{ route('school-admin.classes.create') }}"
                       class="inline-flex items-center gap-1.5 bg-sky-500 text-white px-3.5 py-2 rounded-md
                              text-sm font-medium whitespace-nowrap hover:bg-sky-600 transition-colors shadow-sm shadow-sky-500/20 shrink-0 self-start sm:self-auto">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Class
                    </a>
                </div>
                <p class="text-sm text-gray-500 mt-1">Manage classes and their sections.</p>
            </div>

            {{-- Summary stats --}}
            @php
                $totalSections = $classes->sum(fn($c) => $c->sections->count());
                $withoutSections = $classes->filter(fn($c) => $c->sections->count() === 0)->count();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Classes</p>
                    <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $classes->count() }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Sections</p>
                    <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $totalSections }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Without Sections</p>
                    <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $withoutSections }}</p>
                </div>
            </div>

            {{-- Data table card --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

                {{-- Toolbar --}}
                <div class="px-4 py-3 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="relative w-full sm:max-w-xs">
                        <svg class="w-4 h-4 text-emerald-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="class_search" placeholder="Search classes"
                               class="w-full pl-10 pr-3 py-2 text-sm rounded-full border border-gray-200 bg-gray-50
                                      focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100
                                      transition-colors placeholder:text-gray-400">
                    </div>
                    <span class="text-xs text-gray-500 whitespace-nowrap">{{ $classes->count() }} total</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="classes_table">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">Class Name</th>
                                <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">Sections</th>
                                <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($classes as $class)
                                <tr class="class-row hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-semibold text-xs shrink-0">
                                                {{ strtoupper(substr($class->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-900 class-name">{{ $class->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse ($class->sections as $section)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                    {{ $section->name }}
                                                </span>
                                            @empty
                                                <span class="text-gray-400 text-xs italic">No sections</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('school-admin.classes.edit', $class) }}"
                                               class="inline-flex items-center gap-1 bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-yellow-600 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('school-admin.classes.destroy', $class) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Yo class delete garne?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-700 transition-colors shadow-sm">
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
                                    <td colspan="3" class="py-12 text-center text-gray-400 text-sm">
                                        No class added yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <p id="no_results" class="hidden py-10 text-center text-gray-400 text-sm">No classes match your search.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('class_search');
            const rows = document.querySelectorAll('.class-row');
            const noResults = document.getElementById('no_results');
            if (!searchInput) return;

            searchInput.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const nameEl = row.querySelector('.class-name');
                    const name = nameEl ? nameEl.textContent.toLowerCase() : '';
                    const isMatch = name.includes(query);
                    row.style.display = isMatch ? '' : 'none';
                    if (isMatch) visibleCount++;
                });

                noResults.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
            });
        });
    </script>
</x-app-layout>