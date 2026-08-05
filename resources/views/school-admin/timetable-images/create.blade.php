<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload Timetable Image
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('school-admin.timetable-images.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                            <select id="class_select" name="class_id" class="w-full border-gray-300 rounded-lg" required>
                                <option value="">-- Select Class --</option>
                                @foreach ($classes as $class)
                                    <option
                                        value="{{ $class->id }}"
                                        data-sections='@json($class->sections->map(fn($s) => ["id" => $s->id, "name" => $s->name]))'
                                        {{ old('class_id') == $class->id ? 'selected' : '' }}
                                    >
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                            <select id="section_select" name="section_id" class="w-full border-gray-300 rounded-lg" required>
                                <option value="">-- Select Section --</option>
                            </select>
                            @error('section_id')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Timetable Image</label>
                        <input type="file" name="image" accept="image/*,.pdf"
                               class="w-full border-gray-300 rounded-lg" required>
                        <p class="text-gray-400 text-xs mt-1">JPG, PNG, or PDF. Max 10MB.</p>
                        @error('image')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Upload
                        </button>
                        <a href="{{ route('school-admin.timetable-images.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const classSelect = document.getElementById('class_select');
            const sectionSelect = document.getElementById('section_select');

            function fillSections(sections) {
                sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
                sections.forEach(function (s) {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.name;
                    sectionSelect.appendChild(opt);
                });
            }

            classSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const sections = selected && selected.value ? JSON.parse(selected.dataset.sections || '[]') : [];
                fillSections(sections);
            });
        });
    </script>
</x-app-layout>