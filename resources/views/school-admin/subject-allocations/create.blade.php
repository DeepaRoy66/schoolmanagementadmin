<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Subject Assignment
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-md">

                <div class="px-6 py-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500">
                        Assign a subject to a teacher for a specific class and section. Select the subject, then choose the corresponding section and the teacher who will be responsible for teaching that subject.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mx-6 mt-5 border border-red-200 bg-red-50 rounded-md px-4 py-3">
                        <p class="text-sm font-medium text-red-800 mb-1">Form submission failed. Please correct the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('school-admin.subject-allocations.store') }}">
                    @csrf

                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Assignment Details</h3>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject (Class) <span class="text-red-500">*</span></label>
                                <select name="subject_id" id="subject_id"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
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

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section <span class="text-red-500">*</span></label>
                                <select name="section_id" id="section_id"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                    <option value="">-- Select Section --</option>
                                </select>
                                @error('section_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
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
                            Save Assignment
                        </button>
                        <a href="{{ route('school-admin.subject-allocations.index') }}" class="text-gray-600 text-sm font-medium hover:underline">Cancel</a>
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