<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Teacher Subject Allocation</h2>
    </x-slot>

    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-slate-800">
                Teacher Subject Allocation
            </h1>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Tabs --}}
        <div class="border-b border-slate-200 mb-6">
            <nav class="-mb-px flex gap-6 text-sm font-medium">
                <a href="{{ route('school-admin.subject-allocations.index') }}"
                   class="py-3 border-b-2 border-[#1e4ed8] text-[#1e4ed8]">
                    Teacher Subject Allocation
                </a>
                <a href="{{ route('school-admin.class-teacher.form') }}"
                   class="py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700">
                    Class Teacher
                </a>
            </nav>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">

            {{-- Class + Section filter --}}
            <form method="GET" class="flex items-end gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Class</label>
                    <select name="class_id" onchange="this.form.submit()"
                            class="text-sm border border-slate-300 rounded-md px-3 py-2 w-56 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
                        <option value="">-- Select --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected($selectedClassId == $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Section</label>
                    <select name="section_id"
                            class="text-sm border border-slate-300 rounded-md px-3 py-2 w-56 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
                            @if ($sections->isEmpty()) disabled @endif>
                        <option value="">-- Select --</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}" @selected($selectedSectionId == $section->id)>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-md bg-[#1e4ed8] hover:bg-[#1e3a8a] text-white text-sm font-medium px-4 py-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
            </form>

            @if ($selectedClassId && $selectedSectionId)

                {{-- Class Teacher display --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Class Teacher</label>
                    <div class="text-sm border border-slate-200 rounded-md px-3 py-2 bg-slate-50 text-slate-700 w-56">
                        @if ($classTeacher)
                            {{ $classTeacher->teacher->full_name }}
                        @else
                            <span class="text-slate-400">Not assigned</span>
                            <a href="{{ route('school-admin.class-teacher.form') }}" class="text-[#1e4ed8] hover:underline ml-1">
                                Assign
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Subjects table --}}
                @if (count($rows) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-500 border-b border-slate-100">
                                    <th class="px-5 py-3 font-medium">SN</th>
                                    <th class="px-5 py-3 font-medium">Subject Name</th>
                                    <th class="px-5 py-3 font-medium">Subject Code</th>
                                    <th class="px-5 py-3 font-medium">Teacher</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($rows as $i => $row)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-3 text-slate-500">{{ $i + 1 }}</td>
                                        <td class="px-5 py-3 text-slate-700">{{ $row['subject_name'] }}</td>
                                        <td class="px-5 py-3 text-slate-500">{{ $row['subject_code'] }}</td>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2">
                                                <form method="POST" action="{{ route('school-admin.subject-allocations.store') }}"
                                                      class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                                                    <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                                                    <input type="hidden" name="subject_id" value="{{ $row['subject_id'] }}">

                                                    <select name="teacher_id" onchange="this.form.submit()"
                                                            class="text-sm border border-slate-300 rounded-md px-3 py-1.5 w-56 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
                                                        <option value="">-- Select Teacher --</option>
                                                        @foreach ($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" @selected($row['teacher_id'] == $teacher->id)>
                                                                {{ $teacher->full_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>

                                                @if ($row['allocation_id'])
                                                    <form method="POST"
                                                          action="{{ route('school-admin.subject-allocations.destroy', $row['allocation_id']) }}?class_id={{ $selectedClassId }}&section_id={{ $selectedSectionId }}"
                                                          onsubmit="return confirm('Unassign this teacher?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 rounded-md bg-red-100 hover:bg-red-200 text-red-700">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-slate-400 text-center py-10">No subjects found for this class.</p>
                @endif

            @else
                <p class="text-slate-400 text-center py-10">Select a class and section, then click Search.</p>
            @endif

        </div>
    </div>
</x-app-layout>