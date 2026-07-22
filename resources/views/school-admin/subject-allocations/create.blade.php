<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Subject Assignment
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <p class="text-sm text-gray-500 mb-4">
                    On this page, you can assign a subject to a teacher for a specific class and section. Select the subject, then choose the corresponding section and the teacher who will be responsible for teaching that subject. After filling in the details, click "Save Assignment" to complete the process.
                </p>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg text-sm">
                        <p class="font-medium mb-1">Form submission failed. Please correct the following errors:</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('school-admin.subject-allocations.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject (Class)</label>
                        <select name="subject_id" id="subject_id" class="w-full border-gray-300 rounded-lg" required>
                            <option value="">-- Select Subject --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->schoolClass->name }} — {{ $subject->subject_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                        <select name="section_id" id="section_id" class="w-full border-gray-300 rounded-lg" required>
                            <option value="">-- Select Section --</option>
                        </select>
                        @error('section_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                        <select name="teacher_id" class="w-full border-gray-300 rounded-lg" required>
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

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Save Assignment
                        </button>
                        <a href="{{ route('school-admin.subject-allocations.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Subject -> uski class ko sections ko mapping (PHP bata JS lai pass gareko)
        const subjectSections = @json(
            $subjects->mapWithKeys(fn ($s) => [
                $s->id => $s->schoolClass->sections->map(fn ($sec) => ['id' => $sec->id, 'name' => $sec->name])
            ])
        );

        const subjectSelect = document.getElementById('subject_id');
        const sectionSelect = document.getElementById('section_id');
        const oldSectionId = '{{ old('section_id') }}';

        function populateSections() {
            const subjectId = subjectSelect.value;
            sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';

            if (!subjectId || !subjectSections[subjectId]) return;

            subjectSections[subjectId].forEach(sec => {
                const opt = document.createElement('option');
                opt.value = sec.id;
                opt.textContent = sec.name;
                if (oldSectionId == sec.id) opt.selected = true;
                sectionSelect.appendChild(opt);
            });
        }

        subjectSelect.addEventListener('change', populateSections);

        // Old value bhaye page load huda nai sections populate garne (validation error pachi)
        if (subjectSelect.value) populateSections();
    </script>
</x-app-layout>