<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Class
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('school-admin.classes.store') }}">
                    @csrf

                    <!-- Class Name -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Class Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Grade 5"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                            required
                        >

                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sections -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sections
                        </label>

                        @if ($sections->isEmpty())

                            <p class="text-gray-500 text-sm">
                                No sections found.
                                <a href="{{ route('school-admin.sections.create') }}"
                                   class="text-blue-600 hover:underline">
                                    Add Section
                                </a>
                            </p>

                        @else

                            <div class="relative">

                                <!-- Dropdown Button -->
                                <button
                                    type="button"
                                    id="sectionButton"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white flex justify-between items-center text-left focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    <span id="selectedText">Select Sections</span>

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div
                                    id="sectionDropdown"
                                    class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-50 max-h-60 overflow-y-auto"
                                >

                                    @foreach($sections as $section)

                                        <label class="flex items-center px-4 py-3 hover:bg-gray-100 cursor-pointer">

                                            <input
                                                type="checkbox"
                                                name="section_ids[]"
                                                value="{{ $section->id }}"
                                                class="rounded border-gray-300 text-indigo-600 mr-3"
                                                {{ collect(old('section_ids'))->contains($section->id) ? 'checked' : '' }}
                                            >

                                            {{ $section->name }}

                                        </label>

                                    @endforeach

                                </div>

                            </div>

                            @error('section_ids')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror

                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-3">

                        <button
                            type="submit"
                            class="bg-gray-900 text-white px-5 py-2 rounded-lg hover:bg-gray-700"
                        >
                            Save Class
                        </button>

                        <a
                            href="{{ route('school-admin.classes.index') }}"
                            class="text-gray-600 hover:underline"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const button = document.getElementById("sectionButton");
            const dropdown = document.getElementById("sectionDropdown");
            const text = document.getElementById("selectedText");
            const checkboxes = document.querySelectorAll('input[name="section_ids[]"]');

            // Toggle dropdown
            button.addEventListener("click", function () {
                dropdown.classList.toggle("hidden");
            });

            // Update selected text
            function updateSelectedText() {

                let selected = [];

                checkboxes.forEach(function (checkbox) {

                    if (checkbox.checked) {
                        selected.push(
                            checkbox.parentElement.textContent.trim()
                        );
                    }

                });

                if (selected.length > 0) {
                    text.innerText = selected.join(", ");
                } else {
                    text.innerText = "Select Sections";
                }

            }

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener("change", updateSelectedText);
            });

            updateSelectedText();

            // Close dropdown when clicking outside
            document.addEventListener("click", function (e) {

                if (!button.parentElement.contains(e.target)) {
                    dropdown.classList.add("hidden");
                }

            });

        });
    </script>

</x-app-layout>