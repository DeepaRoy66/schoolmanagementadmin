<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('school-admin.subjects.index') }}" class="font-semibold text-xl text-slate-700 hover:text-slate-500">Subjects</a>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">Add subject</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-md text-sm text-red-700">
                    <p class="font-medium mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm">

                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">New subject</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Fill in the subject details and assign it to one or more classes.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('school-admin.subjects.store') }}">
                    @csrf

                    <div class="px-6 py-5 grid sm:grid-cols-2 gap-x-5 gap-y-5 border-b border-slate-100">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 uppercase tracking-wide mb-1.5">Subject name</label>
                            <input type="text" name="subject_name" value="{{ old('subject_name') }}"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800/10 focus:border-slate-400 @error('subject_name') border-red-300 @enderror"
                                   placeholder="Mathematics" required>
                            @error('subject_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 uppercase tracking-wide mb-1.5">Subject code</label>
                            <input type="text" name="subject_code" value="{{ old('subject_code') }}"
                                   class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800/10 focus:border-slate-400 @error('subject_code') border-red-300 @enderror"
                                   placeholder="MATH101" required>
                            @error('subject_code')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-5">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-xs font-medium text-slate-600 uppercase tracking-wide">Assign to classes</label>
                            <div class="flex items-center gap-3 text-xs">
                                <button type="button" id="selectAllBtn" class="text-slate-600 font-medium hover:text-slate-900">Select all</button>
                                <span class="text-slate-300">/</span>
                                <button type="button" id="clearAllBtn" class="text-slate-400 font-medium hover:text-slate-600">Clear</button>
                            </div>
                        </div>

                        <div class="relative mb-2">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            <input type="text" id="classFilter" placeholder="Filter classes..."
                                   class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 rounded-md text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800/10 focus:border-slate-400">
                        </div>

                        <div class="border border-slate-200 rounded-md @error('class_ids') border-red-300 @enderror">
                            <div class="grid grid-cols-2 sm:grid-cols-3 divide-x divide-y divide-slate-100 max-h-72 overflow-y-auto">
                                @forelse ($classes as $class)
                                    <label class="class-row flex items-center justify-between gap-2 px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors" data-name="{{ strtolower($class->name) }}">
                                        <span class="text-sm text-slate-700 truncate">{{ $class->name }}</span>
                                        <input type="checkbox" name="class_ids[]" value="{{ $class->id }}"
                                               class="class-checkbox w-4 h-4 flex-shrink-0 rounded border-slate-300 text-slate-800 focus:ring-slate-800/20 focus:ring-offset-0"
                                               {{ in_array($class->id, old('class_ids', [])) ? 'checked' : '' }}>
                                    </label>
                                @empty
                                    <div class="col-span-full px-4 py-6 text-center text-sm text-slate-400">
                                        No classes found.
                                        <a href="{{ route('school-admin.classes.create') }}" class="text-slate-700 font-medium hover:underline">Add a class</a>
                                    </div>
                                @endforelse
                            </div>
                            <p id="noMatchMsg" class="hidden px-4 py-6 text-center text-sm text-slate-400">No classes match your search.</p>
                        </div>

                        <div class="flex items-center justify-between mt-2">
                            @error('class_ids')
                                <p class="text-red-600 text-xs">{{ $message }}</p>
                            @else
                                <p class="text-xs text-slate-400">The subject will be added separately to each selected class.</p>
                            @enderror
                            <span id="selectedCount" class="text-xs text-slate-500 font-medium whitespace-nowrap">0 selected</span>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-lg flex items-center justify-end gap-2">
                        <a href="{{ route('school-admin.subjects.index') }}"
                           class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800">Cancel</a>
                        <button type="submit"
                                class="px-5 py-2 bg-slate-900 text-white text-sm font-medium rounded-md hover:bg-slate-800 transition-colors">
                            Save subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('.class-checkbox');
        const countEl = document.getElementById('selectedCount');
        const filterInput = document.getElementById('classFilter');
        const rows = document.querySelectorAll('.class-row');
        const noMatchMsg = document.getElementById('noMatchMsg');

        function updateCount() {
            const checked = document.querySelectorAll('.class-checkbox:checked').length;
            countEl.textContent = checked + ' selected';
        }

        document.getElementById('selectAllBtn').addEventListener('click', () => {
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    row.querySelector('.class-checkbox').checked = true;
                }
            });
            updateCount();
        });

        document.getElementById('clearAllBtn').addEventListener('click', () => {
            checkboxes.forEach(cb => cb.checked = false);
            updateCount();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', updateCount));

        filterInput.addEventListener('input', () => {
            const term = filterInput.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach(row => {
                const matches = row.dataset.name.includes(term);
                row.style.display = matches ? '' : 'none';
                if (matches) visibleCount++;
            });

            noMatchMsg.classList.toggle('hidden', visibleCount !== 0);
        });

        updateCount();
    </script>
</x-app-layout>