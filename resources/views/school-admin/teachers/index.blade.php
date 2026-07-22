<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Teachers
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">All Teachers</h1>
                <a href="{{ route('school-admin.teachers.create') }}"
                   class="px-4 py-2 rounded-2xl bg-[#2dd4bf] text-white text-sm font-medium shadow-md hover:opacity-90">
                    + Add Teacher
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-2xl bg-emerald-50 text-emerald-700 text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="text-left px-6 py-3">Name</th>
                            <th class="text-left px-6 py-3">Contact</th>
                            <th class="text-left px-6 py-3">Designation</th>
                            <th class="text-left px-6 py-3">Class Teacher Of</th>
                            <th class="text-right px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($teachers as $teacher)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $teacher->full_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->phone }} · {{ $teacher->email }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->designation ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($teacher->classTeacherAssignment)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#2dd4bf]/10 text-[#2dd4bf]">
                                            {{ $teacher->classTeacherAssignment->schoolClass->name ?? '' }} - {{ $teacher->classTeacherAssignment->section->name ?? '' }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('school-admin.teachers.edit', $teacher) }}" class="text-gray-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">No teacher added yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $teachers->links() }}</div>

        </div>
    </div>
</x-app-layout>