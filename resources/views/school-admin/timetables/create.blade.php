<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Timetable Entry
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('school-admin.timetables.store') }}">
                    @csrf

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                            <select id="class_select" class="w-full border-gray-300 rounded-lg" required>
                                <option value="">-- Select Class --</option>
                                @foreach ($classes as $class)
                                    <option
                                        value="{{ $class->id }}"
                                        data-name="{{ $class->name }}"
                                        data-sections='@json($class->sections->map(fn($s) => ["id" => $s->id, "name" => $s->name]))'
                                        data-subjects='@json($class->subjects->pluck("subject_name"))'
                                    >
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                            <select id="section_select" class="w-full border-gray-300 rounded-lg" required>
                                <option value="">-- Select Section --</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="class_id" id="class_id_hidden" value="{{ old('class_id') }}">
                    @error('class_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <input type="hidden" name="section_id" id="section_id_hidden" value="{{ old('section_id') }}">
                    @error('section_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Day</label>
                        <select name="day" class="w-full border-gray-300 rounded-lg" required>
                            @foreach (['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                                <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                        @error('day')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                        <input type="text" name="period" value="{{ old('period') }}"
                               class="w-full border-gray-300 rounded-lg" placeholder="e.g. 1" required>
                        @error('period')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select id="subject_select" class="w-full border-gray-300 rounded-lg" required>
                            <option value="">-- Select Class First --</option>
                        </select>
                        <input type="hidden" name="subject" id="subject_hidden" value="{{ old('subject') }}">
                        @error('subject')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                        <select name="teacher_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">-- None --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}"
                                   class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}"
                                   class="w-full border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Save Entry
                        </button>
                        <a href="{{ route('school-admin.timetables.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const classSelect = document.getElementById('class_select');
            const sectionSelect = document.getElementById('section_select');
            const subjectSelect = document.getElementById('subject_select');
            const classIdHidden = document.getElementById('class_id_hidden');
            const sectionIdHidden = document.getElementById('section_id_hidden');
            const subjectHidden = document.getElementById('subject_hidden');

            function fillSectionSelect(sections) {
                sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
                sections.forEach(function (s) {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.name;
                    sectionSelect.appendChild(opt);
                });
            }

            function fillSubjectSelect(items, placeholder) {
                subjectSelect.innerHTML = '<option value="">' + placeholder + '</option>';
                items.forEach(function (item) {
                    const opt = document.createElement('option');
                    opt.value = item;
                    opt.textContent = item;
                    subjectSelect.appendChild(opt);
                });
            }

            classSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];

                classIdHidden.value = selected && selected.value ? selected.value : '';
                sectionIdHidden.value = '';

                if (!selected || !selected.value) {
                    fillSectionSelect([]);
                    fillSubjectSelect([], '-- Select Class First --');
                    subjectHidden.value = '';
                    return;
                }

                const sections = JSON.parse(selected.dataset.sections || '[]');
                const subjects = JSON.parse(selected.dataset.subjects || '[]');

                fillSectionSelect(sections);
                fillSubjectSelect(subjects, subjects.length ? '-- Select Subject --' : '-- No Subjects Found --');

                subjectHidden.value = '';
            });

            sectionSelect.addEventListener('change', function () {
                sectionIdHidden.value = this.value;
            });

            subjectSelect.addEventListener('change', function () {
                subjectHidden.value = this.value;
            });
        });
    </script>
</x-app-layout>