<x-app-layout>
<div class="py-8">
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-2 mb-6">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-8a4 4 0 110 8 4 4 0 010-8zm6 4a4 4 0 10-4-4" />
                </svg>
            </div>
            <div>
                <h2 class="font-semibold text-xl text-slate-800 leading-tight">Class change</h2>
                <p class="text-xs text-slate-400">All class change information</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 flex items-center gap-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 shadow-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Filter form (GET) --}}
            <form method="GET" action="{{ route('school-admin.class-change.index') }}" class="px-6 py-6 border-b border-slate-100 bg-gradient-to-b from-slate-50 to-white">
                <label class="inline-flex items-center gap-2 mb-5 cursor-pointer select-none">
                    <span class="relative inline-flex items-center">
                        <input type="checkbox" id="is_individual" name="is_individual" value="1"
                               {{ $isIndividual ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               class="sr-only peer">
                        <span class="w-9 h-5 bg-slate-200 rounded-full peer-checked:bg-blue-600 transition-colors"></span>
                        <span class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-4"></span>
                    </span>
                    <span class="text-sm text-slate-600 font-medium">Individual student mode</span>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Class from <span class="text-red-500">*</span></label>
                        <select name="class_from" required onchange="this.form.submit()"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 bg-white shadow-sm">
                            <option value="">Select class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_from') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if ($isIndividual)
                        <div class="md:col-span-2 relative">
                            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Student name <span class="text-red-500">*</span></label>

                            <input type="hidden" name="student_id" id="student_id_hidden" value="{{ request('student_id') }}">

                            <div class="relative">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                                </svg>
                                <input type="text" id="student_search_input" autocomplete="off"
                                       placeholder="Type student name or ID..."
                                       value="{{ $selectedStudent->full_name ?? '' }}"
                                       class="w-full pl-9 pr-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 bg-white shadow-sm">
                            </div>

                            <div id="student_search_dropdown"
                                 class="hidden absolute z-20 mt-1 w-full max-h-56 overflow-y-auto bg-white border border-slate-200 rounded-lg shadow-lg">
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Section</label>
                            <select name="section_id" onchange="this.form.submit()"
                                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 bg-white shadow-sm">
                                <option value="">All sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Order by</label>
                            <select name="order_by" onchange="this.form.submit()"
                                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 bg-white shadow-sm">
                                <option value="first_name" {{ request('order_by', 'first_name') == 'first_name' ? 'selected' : '' }}>Name</option>
                                <option value="roll_number" {{ request('order_by') == 'roll_number' ? 'selected' : '' }}>Roll no</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Move to class <span class="text-red-500">*</span></label>
                            <select id="class_to_picker"
                                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 bg-white shadow-sm">
                                <option value="">Select class</option>
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
                            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Move to academic year</label>
                            <select id="academic_year_to_picker"
                                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 bg-white shadow-sm">
                                <option value="">Keep same</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </form>

            <div class="px-6 py-6">

                {{-- INDIVIDUAL MODE: single student edit form --}}
                @if ($isIndividual && $selectedStudent)
                    <form method="POST" action="{{ route('school-admin.class-change.update') }}">
                        @csrf
                        <input type="hidden" name="is_individual" value="1">
                        <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">

                        <div class="flex items-center gap-3 mb-6 p-4 rounded-lg bg-blue-50/60 border border-blue-100">
                            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                {{ strtoupper(substr($selectedStudent->full_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $selectedStudent->full_name }}</p>
                                <p class="text-xs text-slate-500">{{ $selectedStudent->student_uid }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">New class <span class="text-red-500">*</span></label>
                                <select name="class_id" required
                                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 shadow-sm">
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ $selectedStudent->class_id == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Academic year</label>
                                <select name="academic_year_id"
                                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 shadow-sm">
                                    <option value="">Select year</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ $selectedStudent->academic_year_id == $year->id ? 'selected' : '' }}>
                                            {{ $year->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">New section</label>
                                <select name="section_id"
                                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 shadow-sm">
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" {{ $selectedStudent->section_id == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">New roll no</label>
                                <input type="text" name="roll_number" value="{{ $selectedStudent->roll_number }}"
                                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Status</label>
                                <select name="status"
                                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 shadow-sm">
                                    <option value="active" {{ $selectedStudent->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $selectedStudent->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="dropped_out" {{ $selectedStudent->status == 'dropped_out' ? 'selected' : '' }}>Dropped out</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-5 border-t border-slate-100">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors shadow-sm hover:shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update
                            </button>

                            <a href="{{ route('school-admin.class-change.index') }}"
                               class="inline-flex items-center gap-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
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
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 rounded-full px-3 py-1">
                                {{ $students->count() }} {{ Str::plural('student', $students->count()) }} found
                            </span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('school-admin.class-change.index') }}"
                                   class="inline-flex items-center gap-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm hover:shadow">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Update
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-slate-200 rounded-xl">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 border-b border-slate-200">
                                        <th class="py-3 px-4 font-semibold uppercase text-xs tracking-wide w-10">
                                            <input type="checkbox" id="check_all" onclick="toggleAll(this)"
                                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/30">
                                        </th>
                                        <th class="py-3 px-4 font-semibold uppercase text-xs tracking-wide">Student ID</th>
                                        <th class="py-3 px-4 font-semibold uppercase text-xs tracking-wide">Student name</th>
                                        <th class="py-3 px-4 font-semibold uppercase text-xs tracking-wide">Academic year</th>
                                        <th class="py-3 px-4 font-semibold uppercase text-xs tracking-wide">Roll no</th>
                                        <th class="py-3 px-4 font-semibold uppercase text-xs tracking-wide">Section</th>
                                        <th class="py-3 px-4 font-semibold uppercase text-xs tracking-wide">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($students as $student)
                                        <tr class="hover:bg-blue-50/40 transition-colors">
                                            <td class="py-2.5 px-4">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                       class="student-check rounded border-slate-300 text-blue-600 focus:ring-blue-500/30" checked>
                                            </td>
                                            <td class="py-2.5 px-4 text-slate-500">{{ $student->student_uid }}</td>
                                            <td class="py-2.5 px-4">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-semibold shrink-0">
                                                        {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                                    </div>
                                                    <span class="text-slate-700 font-medium">{{ $student->full_name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-2.5 px-4 text-slate-500">{{ $student->academicYear->year ?? '-' }}</td>
                                            <td class="py-2.5 px-4">
                                                <input type="text" name="roll_number[{{ $student->id }}]" value="{{ $student->roll_number }}"
                                                       class="w-20 border border-slate-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                            </td>
                                            <td class="py-2.5 px-4">
                                                <select name="section_id[{{ $student->id }}]"
                                                        class="w-full border border-slate-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                                    @foreach ($sections as $section)
                                                        <option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>
                                                            {{ $section->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="py-2.5 px-4">
                                                <select name="status[{{ $student->id }}]"
                                                        class="w-full border rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400
                                                        {{ $student->status == 'active' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : ($student->status == 'inactive' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-red-200 bg-red-50 text-red-700') }}">
                                                    <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    <option value="dropped_out" {{ $student->status == 'dropped_out' ? 'selected' : '' }}>Dropped out</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                @elseif (request()->filled('class_from'))
                    <div class="flex flex-col items-center gap-3 py-16">
                        <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="text-slate-500 text-sm">No students found for this selection.</p>
                    </div>
                @else
                    <div class="flex flex-col items-center gap-3 py-16">
                        <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-slate-500 text-sm">Select a class above to get started.</p>
                    </div>
                @endif
            </div>
        </div>
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
                `<div class="px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 cursor-pointer" data-id="${s.id}" data-label="${s.label}">${s.label}</div>`
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