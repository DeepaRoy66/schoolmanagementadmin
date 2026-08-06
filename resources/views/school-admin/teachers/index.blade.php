<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Teachers</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage teachers for your school</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                <div class="flex justify-between items-center px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#3b82f6]/10 text-[#3b82f6]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6-6a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                        <p class="text-sm font-medium text-gray-700">{{ $teachers->total() }} {{ Str::plural('Teacher', $teachers->total()) }}</p>
                    </div>
                    <a href="{{ route('school-admin.teachers.create') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-[#3b82f6] text-white text-sm font-medium shadow-md hover:opacity-90 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Teacher
                    </a>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium">Name</th>
                            <th class="text-left px-6 py-3 font-medium">Contact</th>
                            <th class="text-left px-6 py-3 font-medium">Designation</th>
                            <th class="text-left px-6 py-3 font-medium">Class Teacher Of</th>
                            <th class="text-left px-6 py-3 font-medium">Status</th>
                            <th class="text-right px-6 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($teachers as $teacher)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#3b82f6]/10 text-[#3b82f6] flex items-center justify-center font-semibold text-xs shrink-0">
                                            {{ strtoupper(substr($teacher->full_name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $teacher->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->phone }} · {{ $teacher->email }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->designation ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($teacher->classTeacherAssignment)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#3b82f6]/10 text-[#3b82f6]">
                                            {{ $teacher->classTeacherAssignment->schoolClass->name ?? '' }} - {{ $teacher->classTeacherAssignment->section->name ?? '' }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($teacher->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('school-admin.teachers.edit', $teacher) }}"
                                           class="inline-flex items-center gap-1 bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-yellow-600 transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6-6a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-gray-500 text-sm">No teacher added yet.</p>
                                        <a href="{{ route('school-admin.teachers.create') }}" class="text-[#3b82f6] text-sm font-medium hover:underline">Add your first teacher</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($teachers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $teachers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>