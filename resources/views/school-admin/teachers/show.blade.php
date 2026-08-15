<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <h2 class="font-semibold text-xl text-gray-800">{{ $teacher->full_name }}</h2>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">Teacher profile</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-6 flex items-center gap-4 border-b border-gray-100">
                    @if ($teacher->photo)
                        <img src="{{ asset('storage/' . $teacher->photo) }}"
                             class="w-16 h-16 rounded-full object-cover border border-gray-200" alt="{{ $teacher->full_name }}">
                    @else
                        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-lg shrink-0">
                            {{ strtoupper(substr($teacher->full_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900">{{ $teacher->full_name }}</p>
                        <p class="text-sm text-gray-500">{{ $teacher->designation ?? 'Teacher' }}</p>
                        @if ($teacher->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-5 space-y-3 text-sm">
                    <p><span class="font-medium text-gray-700">Email:</span> {{ $teacher->email }}</p>
                    <p><span class="font-medium text-gray-700">Phone:</span> {{ $teacher->phone ?? '—' }}</p>
                    <p><span class="font-medium text-gray-700">Address:</span> {{ $teacher->address ?? '—' }}</p>
                    <p><span class="font-medium text-gray-700">Designation:</span> {{ $teacher->designation ?? '—' }}</p>
                    <p>
                        <span class="font-medium text-gray-700">Class teacher of:</span>
                        @if ($teacher->classTeacherAssignment)
                            {{ $teacher->classTeacherAssignment->schoolClass->name ?? '' }} - {{ $teacher->classTeacherAssignment->section->name ?? '' }}
                        @else
                            <span class="text-gray-400">Not assigned</span>
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <a href="{{ route('school-admin.teachers.edit', $teacher) }}" class="text-amber-600 hover:underline text-sm font-medium">Edit teacher</a>
                    <a href="{{ route('school-admin.teachers.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to list</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>