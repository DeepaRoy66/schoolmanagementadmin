<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <h2 class="font-semibold text-xl text-slate-700">Teachers</h2>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">List all teachers</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <a href="{{ route('school-admin.teachers.create') }}"
                   class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add teacher
                </a>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                <div class="flex flex-wrap items-center gap-3 px-6 py-5 border-b border-slate-100">
                    <label class="text-sm text-slate-600 font-medium">Search:</label>
                    <form action="{{ route('school-admin.teachers.index') }}" method="GET" class="flex items-center gap-2 flex-1 min-w-[260px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, phone or email..."
                               class="flex-1 max-w-xs px-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('school-admin.teachers.index') }}"
                               class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
                        @endif
                    </form>

                    <span class="text-xs text-slate-500 whitespace-nowrap ml-auto">{{ $teachers->total() }} {{ Str::plural('teacher', $teachers->total()) }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 border-b border-slate-200">
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Name</th>
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Contact</th>
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Designation</th>
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Class teacher of</th>
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Status</th>
                                <th class="py-3 px-6 font-semibold w-32">Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teachers as $teacher)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-6 border-r border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-xs shrink-0">
                                                {{ strtoupper(substr($teacher->full_name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-blue-700">{{ $teacher->full_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-slate-600 border-r border-slate-100">{{ $teacher->phone }} · {{ $teacher->email }}</td>
                                    <td class="py-3 px-6 text-slate-600 border-r border-slate-100">{{ $teacher->designation ?? '—' }}</td>
                                    <td class="py-3 px-6 border-r border-slate-100">
                                        @if ($teacher->classTeacherAssignment)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $teacher->classTeacherAssignment->schoolClass->name ?? '' }} - {{ $teacher->classTeacherAssignment->section->name ?? '' }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 border-r border-slate-100">
                                        @if ($teacher->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        <a href="{{ route('school-admin.teachers.edit', $teacher) }}"
                                           class="inline-flex items-center gap-1.5 bg-amber-500 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-amber-600 transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6-6a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            @if (request('search'))
                                                <p class="text-slate-500 text-sm">No teachers match "{{ request('search') }}".</p>
                                                <a href="{{ route('school-admin.teachers.index') }}" class="text-blue-600 text-sm font-medium hover:underline">Clear search</a>
                                            @else
                                                <p class="text-slate-500 text-sm">No teachers added yet.</p>
                                                <a href="{{ route('school-admin.teachers.create') }}" class="text-blue-600 text-sm font-medium hover:underline">Add your first teacher</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($teachers->hasPages())
                    <div class="flex justify-center px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $teachers->appends(['search' => request('search')])->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>