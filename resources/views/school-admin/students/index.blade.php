<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Students
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 border border-green-200 bg-green-50 text-green-800 rounded-md px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-md">

                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500">Total students: <span class="font-medium text-gray-700">{{ $students->total() }}</span></p>
                    <a href="{{ route('school-admin.students.create') }}"
                       class="bg-[#3b82f6] text-white px-4 py-2 rounded text-sm font-medium hover:bg-[#2563eb] transition-colors">
                        + Add Student
                    </a>
                </div>

                <div class="px-6 py-4">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left">
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">ID</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Name</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Email</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Class</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Section</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Roll No.</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Status</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-200 p-2 text-gray-500">{{ $student->student_uid ?? '—' }}</td>
                                    <td class="border border-gray-200 p-2 font-medium text-gray-900">{{ $student->full_name }}</td>
                                    <td class="border border-gray-200 p-2 text-gray-600">{{ $student->email }}</td>
                                    <td class="border border-gray-200 p-2 text-gray-600">{{ $student->schoolClass->name ?? '—' }}</td>
                                    <td class="border border-gray-200 p-2 text-gray-600">{{ $student->section->name ?? '—' }}</td>
                                    <td class="border border-gray-200 p-2 text-gray-600">{{ $student->roll_number ?? '—' }}</td>
                                    <td class="border border-gray-200 p-2">
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
                                    <td class="border border-gray-200 p-2">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('school-admin.students.edit', $student) }}"
                                               class="inline-flex items-center gap-1 bg-yellow-500 text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-yellow-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('school-admin.students.destroy', $student) }}" method="POST"
                                                  onsubmit="return confirm('Yo Student delete garne?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 bg-red-500 text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-red-600 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="border border-gray-200 p-6 text-center text-gray-500">
                                        No students added.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $students->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>