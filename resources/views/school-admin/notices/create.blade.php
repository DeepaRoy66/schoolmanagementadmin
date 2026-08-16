<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Post Notice
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('school-admin.notices.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('title')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" rows="5"
                                  class="w-full border-gray-300 rounded-lg" required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Send To</label>
                        <select name="target_type" id="target_type"
                                class="w-full border-gray-300 rounded-lg" required onchange="toggleClassSelect()">
                            <option value="all" {{ old('target_type') == 'all' ? 'selected' : '' }}>Everyone (Teachers & Students)</option>
                            <option value="teacher" {{ old('target_type') == 'teacher' ? 'selected' : '' }}>Teachers Only</option>
                            <option value="student" {{ old('target_type') == 'student' ? 'selected' : '' }}>Students Only (All Classes)</option>
                            <option value="class_specific" {{ old('target_type') == 'class_specific' ? 'selected' : '' }}>Specific Class / Section</option>
                        </select>
                        @error('target_type')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6" id="class_select_wrapper" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Class(es) / Section(s)</label>

                        <div id="target_rows">
                            <div class="target-row flex gap-2 mb-2">
                                <select name="targets[0][class_id]" class="w-1/2 border-gray-300 rounded-lg">
                                    <option value="">-- Class --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>

                                <select name="targets[0][section_id]" class="w-1/2 border-gray-300 rounded-lg">
                                    <option value="">-- All Sections --</option>
                                    @foreach($classes as $class)
                                        @foreach($class->sections as $section)
                                            <option value="{{ $section->id }}" data-class="{{ $class->id }}">{{ $class->name }} - {{ $section->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="button" onclick="addTargetRow()"
                                class="text-sm text-blue-600 hover:underline mt-1">+ Add another class</button>

                        <p class="text-xs text-gray-500 mt-2">Section select nagareko khandama tyo class ko sabai section ma notice janxa.</p>

                        @error('targets')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Post Notice
                        </button>
                        <a href="{{ route('school-admin.notices.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        let rowIndex = 1;

        function toggleClassSelect() {
            const targetType = document.getElementById('target_type').value;
            const wrapper = document.getElementById('class_select_wrapper');
            const isSpecific = targetType === 'class_specific';

            wrapper.style.display = isSpecific ? 'block' : 'none';

            // Hidden bhayeko bela ka select fields lai disable garne,
            // natra empty value pani submit huncha ra validation fail huncha
            wrapper.querySelectorAll('select').forEach(select => {
                select.disabled = !isSpecific;
            });
        }

        function addTargetRow() {
            const container = document.getElementById('target_rows');
            const firstRow = container.querySelector('.target-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('select').forEach(select => {
                const name = select.getAttribute('name').replace(/\[\d+\]/, `[${rowIndex}]`);
                select.setAttribute('name', name);
                select.selectedIndex = 0;
                select.disabled = false; // wrapper already visible hune bela thapincha, so enable garne
            });

            container.appendChild(newRow);
            rowIndex++;
        }

        document.addEventListener('DOMContentLoaded', toggleClassSelect);
    </script>
</x-app-layout>