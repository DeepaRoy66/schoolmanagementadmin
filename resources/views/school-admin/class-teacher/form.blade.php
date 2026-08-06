<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign Class Teacher
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="border border-green-200 bg-green-50 text-green-800 rounded-md px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-md">

                <div class="px-6 py-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500">
                        After assigning a class teacher, the teacher will have access to the attendance and other features for that class and section. If a class already has a class teacher, assigning a new one will replace the previous teacher.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mx-6 mt-5 border border-red-200 bg-red-50 rounded-md px-4 py-3">
                        <p class="text-sm font-medium text-red-800 mb-1">Form submit hudaina, yi error haru fix garnus:</p>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('school-admin.class-teacher.store') }}" id="assignForm">
                    @csrf

                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Assignment Details</h3>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Class <span class="text-red-500">*</span></label>
                                <select name="class_id" id="class_id"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                    <option value="">-- Select Class --</option>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section <span class="text-red-500">*</span></label>
                                <select name="section_id" id="section_id"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                    <option value="">-- Select Section --</option>
                                </select>
                                @error('section_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p id="conflictWarning" class="text-amber-600 text-xs mt-1 hidden"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teacher <span class="text-red-500">*</span></label>
                                <select name="teacher_id"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                    <option value="">-- Select Teacher --</option>
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

                    <div class="flex items-center gap-3 px-6 py-5 mt-2 border-t border-gray-200 bg-gray-50 rounded-b-md">
                        <button type="submit"
                                class="bg-[#3b82f6] text-white px-5 py-2 rounded text-sm font-medium hover:bg-[#2563eb] transition-colors">
                            Assign Class Teacher
                        </button>
                    </div>
                </form>
            </div>

            {{-- Current assignments --}}
            <div class="bg-white border border-gray-200 rounded-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Current Class Teachers</h3>
                </div>

                <div class="px-6 py-4">
                    @if ($assignments->isEmpty())
                        <p class="text-sm text-gray-500">No class teachers assigned yet.</p>
                    @else
                        <table class="w-full border-collapse text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th class="border border-gray-200 p-2 text-gray-600 font-medium">Class</th>
                                    <th class="border border-gray-200 p-2 text-gray-600 font-medium">Section</th>
                                    <th class="border border-gray-200 p-2 text-gray-600 font-medium">Teacher</th>
                                    <th class="border border-gray-200 p-2 text-gray-600 font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignments as $assignment)
                                    <tr>
                                        <td class="border border-gray-200 p-2">{{ $assignment->schoolClass->name }}</td>
                                        <td class="border border-gray-200 p-2">{{ $assignment->section->name }}</td>
                                        <td class="border border-gray-200 p-2">{{ $assignment->teacher->full_name }}</td>
                                        <td class="border border-gray-200 p-2">
                                            <form method="POST"
                                                  action="{{ route('school-admin.class-teacher.remove', $assignment->id) }}"
                                                  onsubmit="return confirm('{{ $assignment->section->name }} bata class teacher hataune? Attendance access pani jancha.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="bg-red-50 text-red-700 px-3 py-1 rounded text-xs font-medium hover:bg-red-100 transition-colors">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        // Class -> uski sections ko mapping
        const classSections = @json(
            $classes->mapWithKeys(fn ($c) => [
                $c->id => $c->sections->map(fn ($sec) => ['id' => $sec->id, 'name' => $sec->name])
            ])
        );

        // Section -> already assigned teacher naam (conflict check ko lagi).
        // Key "class_id-section_id" ho, section_id matra hoina - kina bhane euta
        // section (jastai "Section A") multiple classes ma share hunsakcha, ra
        // tiniharu sabai faraak-faraak assignment hun sakchan.
        const sectionAssignments = @json(
            $assignments->mapWithKeys(fn ($a) => [$a->class_id . '-' . $a->section_id => $a->teacher->full_name])
        );

        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        const conflictWarning = document.getElementById('conflictWarning');
        const oldSectionId = '{{ old('section_id') }}';

        function populateSections() {
            const classId = classSelect.value;
            sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
            conflictWarning.classList.add('hidden');

            if (!classId || !classSections[classId]) return;

            classSections[classId].forEach(sec => {
                const opt = document.createElement('option');
                opt.value = sec.id;
                opt.textContent = sec.name;
                if (oldSectionId == sec.id) opt.selected = true;
                sectionSelect.appendChild(opt);
            });

            checkConflict();
        }

        function conflictKey() {
            return classSelect.value + '-' + sectionSelect.value;
        }

        function checkConflict() {
            const key = conflictKey();
            if (sectionSelect.value && sectionAssignments[key]) {
                conflictWarning.textContent = 'This section already has a class teacher assigned: ' + sectionAssignments[key] + '. Saving will replace the current teacher.';
                conflictWarning.classList.remove('hidden');
            } else {
                conflictWarning.classList.add('hidden');
            }
        }

        classSelect.addEventListener('change', populateSections);
        sectionSelect.addEventListener('change', checkConflict);

        if (classSelect.value) populateSections();

        // Final confirm before submit if conflict exists
        document.getElementById('assignForm').addEventListener('submit', function (e) {
            const key = conflictKey();
            if (sectionSelect.value && sectionAssignments[key]) {
                const ok = confirm('Replace the current class teacher? "' + sectionAssignments[key] + '" ko attendance access hatera naya teacher lai dine.');
                if (!ok) e.preventDefault();
            }
        });
    </script>
</x-app-layout>