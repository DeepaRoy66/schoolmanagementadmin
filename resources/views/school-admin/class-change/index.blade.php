<x-app-layout>
<div class="px-6 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-700">
            Class Change
            <span class="text-slate-400 font-normal text-base">&raquo; All Class Change Information</span>
        </h1>
    </div>

    @if (session('success'))
        <div class="mb-4 flex items-center gap-2 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm px-6 py-6">

        {{-- Filter form (GET) --}}
        <form method="GET" action="{{ route('school-admin.class-change.index') }}" class="mb-6">
            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" id="is_individual" name="is_individual" value="1"
                       {{ $isIndividual ? 'checked' : '' }}
                       onchange="this.form.submit()"
                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="is_individual" class="text-sm text-slate-600">Is Individual</label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Class From <span class="text-red-500">*</span></label>
                    <select name="class_from" required onchange="this.form.submit()"
                            class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                        <option value="">-- Select --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_from') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if ($isIndividual)
                    <div class="md:col-span-2 relative">
                        <label class="block text-sm font-medium text-slate-600 mb-1">Student Name <span class="text-red-500">*</span></label>

                        <input type="hidden" name="student_id" id="student_id_hidden" value="{{ request('student_id') }}">

                        <input type="text" id="student_search_input" autocomplete="off"
                               placeholder="Type student name or ID..."
                               value="{{ $selectedStudent->full_name ?? '' }}"
                               class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">

                        <div id="student_search_dropdown"
                             class="hidden absolute z-20 mt-1 w-full max-h-56 overflow-y-auto bg-white border border-slate-200 rounded-md shadow-lg">
                        </div>
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Section</label>
                        <select name="section_id" onchange="this.form.submit()"
                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <option value="">-- All --</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Order By</label>
                        <select name="order_by" onchange="this.form.submit()"
                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <option value="first_name" {{ request('order_by', 'first_name') == 'first_name' ? 'selected' : '' }}>Name</option>
                            <option value="roll_number" {{ request('order_by') == 'roll_number' ? 'selected' : '' }}>Roll No</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Move To Class <span class="text-red-500">*</span></label>
                        <select id="class_to_picker"
                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <option value="">-- Select --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            @if (!$isIndividual)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Move To Academic Year</label>
                        <select id="academic_year_to_picker"
                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <option value="">-- Keep Same --</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </form>

        {{-- INDIVIDUAL MODE: single student edit form --}}
        @if ($isIndividual && $selectedStudent)
            <form method="POST" action="{{ route('school-admin.class-change.update') }}">
                @csrf
                <input type="hidden" name="is_individual" value="1">
                <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">New Class <span class="text-red-500">*</span></label>
                        <select name="class_id" required
                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ $selectedStudent->class_id == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Academic Year</label>
                        <select name="academic_year_id"
                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <option value="">-- Select --</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $selectedStudent->academic_year_id == $year->id ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">New Section</label>
                        <select name="section_id"
                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ $selectedStudent->section_id == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">New Roll No</label>
                        <input type="text" name="roll_number" value="{{ $selectedStudent->roll_number }}"
                               class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                        <select name="status"
                                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <option value="active" {{ $selectedStudent->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $selectedStudent->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="dropped_out" {{ $selectedStudent->status == 'dropped_out' ? 'selected' : '' }}>Dropped Out</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-md transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update
                    </button>

                    <a href="{{ route('school-admin.class-change.index') }}"
                       class="inline-flex items-center gap-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium px-5 py-2.5 rounded-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>

        {{-- BULK MODE: full student list --}}
        @elseif (!$isIndividual && $students->count() > 0)
            <form method="POST" action="{{ route('school-admin.class-change.update') }}">
                @csrf
                <input type="hidden" name="class_to" id="class_to_hidden" value="">
                <input type="hidden" name="academic_year_to" id="academic_year_to_hidden" value="">

                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm text-slate-500">{{ $students->count() }} student(s) found</span>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('school-admin.class-change.index') }}"
                           class="inline-flex items-center gap-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium px-5 py-2.5 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-md transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-md">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-left text-slate-600">
                                <th class="py-3 px-4 font-semibold">
                                    <input type="checkbox" id="check_all" onclick="toggleAll(this)"
                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="py-3 px-4 font-semibold">Student ID</th>
                                <th class="py-3 px-4 font-semibold">Student Name</th>
                                <th class="py-3 px-4 font-semibold">Current Academic Year</th>
                                <th class="py-3 px-4 font-semibold">Roll No</th>
                                <th class="py-3 px-4 font-semibold">Section</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-4">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                               class="student-check rounded border-slate-300 text-blue-600 focus:ring-blue-500" checked>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700">{{ $student->student_uid }}</td>
                                    <td class="py-3 px-4 text-slate-700">{{ $student->full_name }}</td>
                                    <td class="py-3 px-4 text-slate-700">{{ $student->academicYear->year ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <input type="text" name="roll_number[{{ $student->id }}]" value="{{ $student->roll_number }}"
                                               class="w-24 border border-slate-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                    </td>
                                    <td class="py-3 px-4">
                                        <select name="section_id[{{ $student->id }}]"
                                                class="w-full border border-slate-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>
                                                    {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 px-4">
                                        <select name="status[{{ $student->id }}]"
                                                class="w-full border border-slate-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                            <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="dropped_out" {{ $student->status == 'dropped_out' ? 'selected' : '' }}>Dropped Out</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        @elseif (request()->filled('class_from'))
            <p class="text-center text-slate-400 py-6">No students found.</p>
        @endif
    </div>
</div>

<script>
function toggleAll(source) {
    document.querySelectorAll('.student-check').forEach(cb => cb.checked = source.checked);
}

const classToPicker = document.getElementById('class_to_picker');
const classToHidden = document.getElementById('class_to_hidden');
if (classToPicker && classToHidden) {
    classToPicker.addEventListener('change', function () {
        classToHidden.value = this.value;
    });
}

const academicYearToPicker = document.getElementById('academic_year_to_picker');
const academicYearToHidden = document.getElementById('academic_year_to_hidden');
if (academicYearToPicker && academicYearToHidden) {
    academicYearToPicker.addEventListener('change', function () {
        academicYearToHidden.value = this.value;
    });
}

// Searchable student dropdown (Individual mode)
const studentList = @json($students->map(fn($s) => ['id' => $s->id, 'label' => $s->full_name . ' (' . $s->student_uid . ')']));

const searchInput = document.getElementById('student_search_input');
const searchDropdown = document.getElementById('student_search_dropdown');
const hiddenStudentId = document.getElementById('student_id_hidden');

if (searchInput && searchDropdown) {
    function renderDropdown(filter = '') {
        const filtered = studentList.filter(s =>
            s.label.toLowerCase().includes(filter.toLowerCase())
        );

        if (filtered.length === 0) {
            searchDropdown.innerHTML = '<div class="px-3 py-2 text-sm text-slate-400">No students found</div>';
        } else {
            searchDropdown.innerHTML = filtered.map(s =>
                `<div class="px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 cursor-pointer" data-id="${s.id}" data-label="${s.label}">${s.label}</div>`
            ).join('');
        }

        searchDropdown.classList.remove('hidden');
    }

    searchInput.addEventListener('focus', function () {
        renderDropdown(this.value);
    });

    searchInput.addEventListener('input', function () {
        hiddenStudentId.value = '';
        renderDropdown(this.value);
    });

    searchDropdown.addEventListener('click', function (e) {
        const item = e.target.closest('[data-id]');
        if (!item) return;

        searchInput.value = item.dataset.label;
        hiddenStudentId.value = item.dataset.id;
        searchDropdown.classList.add('hidden');

        searchInput.closest('form').submit();
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.add('hidden');
        }
    });
}
</script>
</x-app-layout>