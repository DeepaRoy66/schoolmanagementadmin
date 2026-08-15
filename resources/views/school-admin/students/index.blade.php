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

    <div class="py-8 overflow-x-hidden">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6 min-w-0">

            @if (session('status'))
                <div class="flex items-center gap-2 border border-emerald-200 bg-emerald-50 text-emerald-700 rounded-md px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Add Student button --}}
            <div class="flex justify-end">
                <a href="{{ route('school-admin.students.create') }}"
                   class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Student
                </a>
            </div>

            {{-- Filter bar --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm px-6 py-5">
                <form action="{{ route('school-admin.students.index') }}" method="GET" class="space-y-4">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Academic Year</label>
                            <select name="academic_year_id" onchange="this.form.submit()"
                                    class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                <option value="">-- Select --</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Class</label>
                            <select name="class_id" onchange="this.form.submit()"
                                    class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                <option value="">-- Select --</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Section</label>
                            <select name="section_id" onchange="this.form.submit()"
                                    class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                <option value="">-- Select --</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Status</label>
                            <select name="status" onchange="this.form.submit()"
                                    class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                <option value="">-- All --</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="dropped_out" {{ request('status') == 'dropped_out' ? 'selected' : '' }}>Dropped Out</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-slate-100">
                        <label class="text-sm text-slate-600 font-medium">Search:</label>
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

                        @if (request()->anyFilled(['search', 'academic_year_id', 'class_id', 'section_id', 'status']))
                            <a href="{{ route('school-admin.students.index') }}"
                               class="text-sm text-slate-400 hover:text-slate-600">Clear all</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden min-w-0">

                <div class="flex items-center justify-between px-6 py-3 bg-slate-50/40 border-b border-slate-100">
                    <p class="text-sm text-slate-500">Total students: <span class="font-medium text-slate-700">{{ $students->total() }}</span></p>
                    @if (request()->anyFilled(['search', 'academic_year_id', 'class_id', 'section_id', 'status']))
                        <span class="inline-flex items-center bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full">
                            Filters applied
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto w-full max-w-full">
                    <table class="w-full min-w-[980px] text-sm text-left border-collapse border border-slate-200">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600">
                                <th class="py-3 px-4 font-semibold border border-slate-200">Photo</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">ID</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Name</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Email</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Class</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Section</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Roll No.</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Emergency Contact</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Status</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-4 border border-slate-200">
                                        <img src="{{ $student->photo ? asset('storage/' . $student->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name) . '&background=e2e8f0&color=64748b&size=64' }}"
                                             class="w-10 h-10 rounded-full object-cover border border-slate-200 bg-slate-100"
                                             alt="{{ $student->full_name }}">
                                    </td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-500">{{ $student->student_uid ?? '—' }}</td>
                                    <td class="py-3 px-4 border border-slate-200 font-medium text-slate-900">{{ $student->full_name }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $student->email }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $student->schoolClass->name ?? '—' }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $student->section->name ?? '—' }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $student->roll_number ?? '—' }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">
                                        @php
                                            // Priority: Parent > Father > Mother > Local Guardian
                                            $emergency = null;
                                            if ($student->parent_phone) {
                                                $emergency = ['name' => $student->parent_name, 'phone' => $student->parent_phone, 'relation' => 'Parent'];
                                            } elseif ($student->father_phone) {
                                                $emergency = ['name' => $student->father_name, 'phone' => $student->father_phone, 'relation' => 'Father'];
                                            } elseif ($student->mother_phone) {
                                                $emergency = ['name' => $student->mother_name, 'phone' => $student->mother_phone, 'relation' => 'Mother'];
                                            } elseif ($student->local_guardian_phone) {
                                                $emergency = ['name' => $student->local_guardian_name, 'phone' => $student->local_guardian_phone, 'relation' => 'Local Guardian'];
                                            }
                                        @endphp
                                        @if ($emergency)
                                            <div class="font-medium text-slate-800">{{ $emergency['name'] ?? '—' }}</div>
                                            <div class="text-xs text-slate-500">
                                                {{ $emergency['phone'] }}
                                                <span class="text-slate-400">({{ $emergency['relation'] }})</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-xs">Not added</span>
                                        @endif
                                    </td>
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
                                            <a href="{{ route('school-admin.students.show', $student) }}"
                                               class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 border border-slate-300 px-3 py-1.5 rounded-md text-xs font-medium hover:bg-slate-200 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>
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
                                    <td colspan="10" class="py-16 text-center border border-slate-200">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6-6a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            @if (request()->anyFilled(['search', 'academic_year_id', 'class_id', 'section_id', 'status']))
                                                <p class="text-slate-500 text-sm">No students match your filters.</p>
                                                <a href="{{ route('school-admin.students.index') }}" class="text-blue-600 text-sm font-medium hover:underline">Clear filters</a>
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