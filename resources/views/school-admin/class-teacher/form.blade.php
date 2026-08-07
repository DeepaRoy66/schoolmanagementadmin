<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Assign class teacher
            </span>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">Class and section teacher assignments</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="flex items-center gap-2 border border-emerald-200 bg-emerald-50 text-emerald-700 rounded-md px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div id="assignFormCard" class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/40">
                    <p class="text-sm text-slate-500">
                        After assigning a class teacher, the teacher will have access to the attendance and other features for that class and section. If a class already has a class teacher, assigning a new one will replace the previous teacher.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mx-6 mt-5 border border-red-200 bg-red-50 rounded-md px-4 py-3">
                        <p class="text-sm font-medium text-red-700 mb-1">Form submit hudaina, yi error haru fix garnus:</p>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('school-admin.class-teacher.store') }}" id="assignForm">
                    @csrf

                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Assignment details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Class <span class="text-red-500">*</span></label>
                                <select name="class_id" id="class_id"
                                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                    <option value="">Select class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Section <span class="text-red-500">*</span></label>
                                <select name="section_id" id="section_id"
                                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                    <option value="">Select section</option>
                                </select>
                                @error('section_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p id="conflictWarning" class="text-amber-600 text-xs mt-1 hidden"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Teacher <span class="text-red-500">*</span></label>
                                <select name="teacher_id" id="teacher_id"
                                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                    <option value="">Select teacher</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-6 py-5 mt-2 border-t border-slate-100 bg-slate-50/50">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Assign class teacher
                        </button>
                        <button type="button" id="cancelEditBtn"
                                class="hidden inline-flex items-center gap-1.5 border border-slate-300 text-slate-600 px-4 py-2.5 rounded-md text-sm font-medium hover:bg-slate-100 transition-colors">
                            Cancel edit
                        </button>
                    </div>
                </form>
            </div>

            {{-- Current assignments --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                <div class="flex flex-wrap items-center gap-3 px-6 py-5 border-b border-slate-100">
                    <label class="text-sm text-slate-600 font-medium">Search:</label>
                    <form action="{{ route('school-admin.class-teacher.form') }}" method="GET" class="flex items-center gap-2 flex-1 min-w-[260px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by class, section or teacher..."
                               class="flex-1 max-w-sm px-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-400">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('school-admin.class-teacher.form') }}"
                               class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
                        @endif
                    </form>

                    <span class="inline-flex items-center bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full ml-auto whitespace-nowrap">
                        {{ count($assignments) }} {{ Str::plural('assignment', count($assignments)) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse border border-slate-200">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600">
                                <th class="py-3 px-6 font-semibold border border-slate-200">Class</th>
                                <th class="py-3 px-6 font-semibold border border-slate-200">Section</th>
                                <th class="py-3 px-6 font-semibold border border-slate-200">Teacher</th>
                                <th class="py-3 px-6 font-semibold border border-slate-200 w-40">Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assignments as $assignment)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-6 border border-slate-200 text-slate-700">{{ $assignment->schoolClass->name }}</td>
                                    <td class="py-3 px-6 border border-slate-200 text-slate-700">{{ $assignment->section->name }}</td>
                                    <td class="py-3 px-6 border border-slate-200">
                                        <span class="font-medium text-blue-700">{{ $assignment->teacher->full_name }}</span>
                                    </td>
                                    <td class="py-3 px-6 border border-slate-200">
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                    class="edit-assignment-btn inline-flex items-center gap-1.5 bg-amber-500 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-amber-600 transition-colors shadow-sm"
                                                    data-class-id="{{ $assignment->class_id }}"
                                                    data-section-id="{{ $assignment->section_id }}"
                                                    data-teacher-id="{{ $assignment->teacher_id }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828H9V13z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14" />
                                                </svg>
                                                Edit
                                            </button>

                                            <form method="POST"
                                                  action="{{ route('school-admin.class-teacher.remove', $assignment->id) }}"
                                                  onsubmit="return confirm('{{ $assignment->section->name }} bata class teacher hataune? Attendance access pani jancha.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-red-700 transition-colors shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16 text-center border border-slate-200">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6-6a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            @if (request('search'))
                                                <p class="text-slate-500 text-sm">No assignments match "{{ request('search') }}".</p>
                                                <a href="{{ route('school-admin.class-teacher.form') }}" class="text-blue-600 text-sm font-medium hover:underline">Clear search</a>
                                            @else
                                                <p class="text-slate-500 text-sm">No class teachers assigned yet.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const classSections = @json(
            $classes->mapWithKeys(fn ($c) => [
                $c->id => $c->sections->map(fn ($sec) => ['id' => $sec->id, 'name' => $sec->name])
            ])
        );

        const sectionAssignments = @json(
            $assignments->mapWithKeys(fn ($a) => [$a->class_id . '-' . $a->section_id => $a->teacher->full_name])
        );

        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        const teacherSelect = document.getElementById('teacher_id');
        const conflictWarning = document.getElementById('conflictWarning');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const oldSectionId = '{{ old('section_id') }}';
        let editingAssignmentKey = null;

        function populateSections(preselectSectionId) {
            const classId = classSelect.value;
            sectionSelect.innerHTML = '<option value="">Select section</option>';
            conflictWarning.classList.add('hidden');

            if (!classId || !classSections[classId]) return;

            classSections[classId].forEach(sec => {
                const opt = document.createElement('option');
                opt.value = sec.id;
                opt.textContent = sec.name;
                if (preselectSectionId ? (preselectSectionId == sec.id) : (oldSectionId == sec.id)) {
                    opt.selected = true;
                }
                sectionSelect.appendChild(opt);
            });

            checkConflict();
        }

        function conflictKey() {
            return classSelect.value + '-' + sectionSelect.value;
        }

        function checkConflict() {
            const key = conflictKey();
            // While editing an assignment, don't warn about its own existing row.
            if (editingAssignmentKey === key) {
                conflictWarning.classList.add('hidden');
                return;
            }
            if (sectionSelect.value && sectionAssignments[key]) {
                conflictWarning.textContent = 'This section already has a class teacher assigned: ' + sectionAssignments[key] + '. Saving will replace the current teacher.';
                conflictWarning.classList.remove('hidden');
            } else {
                conflictWarning.classList.add('hidden');
            }
        }

        classSelect.addEventListener('change', function () {
            editingAssignmentKey = null;
            populateSections();
        });
        sectionSelect.addEventListener('change', checkConflict);

        if (classSelect.value) populateSections();

        document.getElementById('assignForm').addEventListener('submit', function (e) {
            const key = conflictKey();
            if (editingAssignmentKey === key) return;
            if (sectionSelect.value && sectionAssignments[key]) {
                const ok = confirm('Replace the current class teacher? "' + sectionAssignments[key] + '" ko attendance access hatera naya teacher lai dine.');
                if (!ok) e.preventDefault();
            }
        });

        // Edit (amber) button: prefill the form with this assignment's values.
        document.querySelectorAll('.edit-assignment-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const classId = this.dataset.classId;
                const sectionId = this.dataset.sectionId;
                const teacherId = this.dataset.teacherId;

                classSelect.value = classId;
                populateSections(sectionId);
                teacherSelect.value = teacherId;

                editingAssignmentKey = classId + '-' + sectionId;
                conflictWarning.classList.add('hidden');
                cancelEditBtn.classList.remove('hidden');

                document.getElementById('assignFormCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        cancelEditBtn.addEventListener('click', function () {
            document.getElementById('assignForm').reset();
            sectionSelect.innerHTML = '<option value="">Select section</option>';
            editingAssignmentKey = null;
            conflictWarning.classList.add('hidden');
            cancelEditBtn.classList.add('hidden');
        });
    </script>
</x-app-layout>