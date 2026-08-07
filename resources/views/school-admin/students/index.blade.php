<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Students
            </span>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">List all students</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="flex items-center gap-2 border border-emerald-200 bg-emerald-50 text-emerald-700 rounded-md px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                {{-- Search + Add Student --}}
                <div class="flex flex-wrap items-center gap-3 px-6 py-5 border-b border-slate-100">
                    <label class="text-sm text-slate-600 font-medium">Search:</label>
                    <form action="{{ route('school-admin.students.index') }}" method="GET" class="flex items-center gap-2 flex-1 min-w-[260px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, email or roll number..."
                               class="flex-1 max-w-sm px-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-400">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('school-admin.students.index') }}"
                               class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
                        @endif
                    </form>

                    <a href="{{ route('school-admin.students.create') }}"
                       class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Student
                    </a>
                </div>

                <div class="flex items-center justify-between px-6 py-3 bg-slate-50/40 border-b border-slate-100">
                    <p class="text-sm text-slate-500">Total students: <span class="font-medium text-slate-700">{{ $students->total() }}</span></p>
                    @if (request('search'))
                        <span class="inline-flex items-center bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full">
                            Showing results for "{{ request('search') }}"
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse border border-slate-200">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600">
                                <th class="py-3 px-4 font-semibold border border-slate-200">ID</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Name</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Email</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Class</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Section</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Roll No.</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Status</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-4 border border-slate-200 text-slate-500">{{ $student->student_uid ?? '—' }}</td>
                                    <td class="py-3 px-4 border border-slate-200 font-medium text-slate-900">{{ $student->full_name }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $student->email }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $student->schoolClass->name ?? '—' }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $student->section->name ?? '—' }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $student->roll_number ?? '—' }}</td>
                                    <td class="py-3 px-4 border border-slate-200">
                                        @php
                                            $statusColors = [
                                                'active' => 'bg-green-100 text-green-700',
                                                'inactive' => 'bg-gray-100 text-gray-600',
                                                'dropped_out' => 'bg-red-100 text-red-700',
                                            ];
                                            $statusLabels = [
                                                'active' => 'Active',
                                                'inactive' => 'Inactive',
                                                'dropped_out' => 'Dropped Out',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $statusColors[$student->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 border border-slate-200">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('school-admin.students.edit', $student) }}"
                                               class="inline-flex items-center gap-1 bg-amber-500 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-amber-600 transition-colors shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-16 text-center border border-slate-200">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6-6a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            @if (request('search'))
                                                <p class="text-slate-500 text-sm">No students match "{{ request('search') }}".</p>
                                                <a href="{{ route('school-admin.students.index') }}" class="text-blue-600 text-sm font-medium hover:underline">Clear search</a>
                                            @else
                                                <p class="text-slate-500 text-sm">No students added.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4">
                    {{ $students->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>