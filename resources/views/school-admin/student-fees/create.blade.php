<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center shadow-md shadow-teal-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assign New Fee</h2>
                <p class="text-xs text-gray-400">Create a fee record for a student</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 text-sm flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('duplicate_warning'))
                <div class="mb-5 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                    <p class="font-medium mb-2 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Possible duplicate
                    </p>
                    <p class="mb-3">{{ session('duplicate_warning')['message'] }}</p>
                    <label class="flex items-center gap-2 text-amber-900 cursor-pointer">
                        <input type="checkbox" name="confirm_duplicate" value="1" form="studentFeeForm"
                               class="rounded text-amber-600 focus:ring-amber-500 w-4 h-4">
                        Yes, add this as an additional fee anyway
                    </label>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- Main form --}}
                <form id="studentFeeForm" action="{{ route('school-admin.student-fees.store') }}" method="POST"
                      class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    @csrf

                    {{-- Step 1: Who & What --}}
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-2.5 mb-5">
                            <span class="w-7 h-7 rounded-full bg-teal-50 text-[#2dd4bf] text-xs font-bold flex items-center justify-center">1</span>
                            <h3 class="text-sm font-semibold text-gray-800">Who is this fee for?</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Class</label>
                                <select id="classSelect"
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:bg-white focus:border-[#2dd4bf] focus:ring-[#2dd4bf] transition-colors">
                                    <option value="">-- All Classes --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Section</label>
                                <select id="sectionSelect"
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:bg-white focus:border-[#2dd4bf] focus:ring-[#2dd4bf] transition-colors">
                                    <option value="">-- All Sections --</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" data-classes="{{ $section->classes->pluck('id')->implode(',') }}"
                                                @selected(old('section_id') == $section->id)>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Student</label>
                                <select name="student_id" id="studentSelect" required
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:bg-white focus:border-[#2dd4bf] focus:ring-[#2dd4bf] transition-colors">
                                    <option value="">-- Select Student --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}"
                                                data-class="{{ $student->class_id }}"
                                                data-section="{{ $student->section_id }}"
                                                @selected(old('student_id') == $student->id)>
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p id="noStudentsHint" class="mt-1.5 text-xs text-gray-400 hidden">No students found for the selected class/section.</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Fee Category</label>
                                <select name="fee_category_id" required
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:bg-white focus:border-[#2dd4bf] focus:ring-[#2dd4bf] transition-colors">
                                    <option value="">-- Select Category --</option>
                                    @foreach($feeCategories as $category)
                                        <option value="{{ $category->id }}" @selected(old('fee_category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Amount & Due Date --}}
                    <div class="p-6 sm:p-8 border-t border-gray-100 bg-gray-50/50">
                        <div class="flex items-center gap-2.5 mb-5">
                            <span class="w-7 h-7 rounded-full bg-teal-50 text-[#2dd4bf] text-xs font-bold flex items-center justify-center">2</span>
                            <h3 class="text-sm font-semibold text-gray-800">Amount &amp; schedule</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Amount</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rs.</span>
                                    <input type="number" step="0.01" min="0.01" name="amount" id="amountInput" value="{{ old('amount') }}" required
                                           placeholder="0.00"
                                           class="w-full rounded-xl border-gray-200 bg-white pl-11 text-sm font-medium focus:border-[#2dd4bf] focus:ring-[#2dd4bf] transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Due Date</label>
                                <input type="date" name="due_date" id="dueDateInput" value="{{ old('due_date') }}" required
                                       class="w-full rounded-xl border-gray-200 bg-white text-sm focus:border-[#2dd4bf] focus:ring-[#2dd4bf] transition-colors">
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="p-6 sm:p-8 border-t border-gray-100">
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Notes <span class="text-gray-300 font-normal">(optional)</span></label>
                        <textarea name="notes" rows="3"
                                  placeholder="Any additional remarks..."
                                  class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:bg-white focus:border-[#2dd4bf] focus:ring-[#2dd4bf] transition-colors">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 p-6 sm:p-8 border-t border-gray-100">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#2dd4bf] text-white text-sm font-medium rounded-xl hover:bg-teal-500 active:bg-teal-600 transition-colors shadow-sm shadow-teal-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Assign Fee
                        </button>
                        <a href="{{ route('school-admin.student-fees.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 hover:underline">Cancel</a>
                    </div>
                </form>

                {{-- Summary sidebar --}}
                <div class="lg:col-span-1 lg:sticky lg:top-6 space-y-4">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#2dd4bf]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            Summary
                        </h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Student</dt>
                                <dd id="summaryStudent" class="text-gray-700 font-medium">—</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Category</dt>
                                <dd id="summaryCategory" class="text-gray-700 font-medium">—</dd>
                            </div>
                            <div class="flex justify-between pt-3 border-t border-gray-100">
                                <dt class="text-gray-400">Amount</dt>
                                <dd id="summaryAmount" class="text-gray-900 font-semibold">Rs. 0.00</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Due</dt>
                                <dd id="summaryDue" class="text-gray-700 font-medium">—</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-teal-50/60 rounded-3xl border border-teal-100 p-5 text-xs text-teal-800 leading-relaxed">
                        Tip: pick a class and section first to narrow the student list down quickly.
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        (function () {
            const classSelect = document.getElementById('classSelect');
            const sectionSelect = document.getElementById('sectionSelect');
            const studentSelect = document.getElementById('studentSelect');
            const noStudentsHint = document.getElementById('noStudentsHint');
            const categorySelect = document.querySelector('select[name="fee_category_id"]');
            const amountInput = document.getElementById('amountInput');
            const dueDateInput = document.getElementById('dueDateInput');

            const summaryStudent = document.getElementById('summaryStudent');
            const summaryCategory = document.getElementById('summaryCategory');
            const summaryAmount = document.getElementById('summaryAmount');
            const summaryDue = document.getElementById('summaryDue');

            const sectionOptions = Array.from(sectionSelect.options).slice(1);
            const studentOptions = Array.from(studentSelect.options).slice(1);

            function filterSections() {
                const classId = classSelect.value;
                sectionOptions.forEach(function (opt) {
                    const classIds = (opt.dataset.classes || '').split(',').filter(Boolean);
                    const matches = !classId || classIds.includes(classId);
                    opt.hidden = !matches;
                    if (!matches && sectionSelect.value === opt.value) {
                        sectionSelect.value = '';
                    }
                });
            }

            function filterStudents() {
                const classId = classSelect.value;
                const sectionId = sectionSelect.value;
                let visibleCount = 0;

                studentOptions.forEach(function (opt) {
                    const classMatch = !classId || opt.dataset.class === classId;
                    const sectionMatch = !sectionId || opt.dataset.section === sectionId;
                    const matches = classMatch && sectionMatch;
                    opt.hidden = !matches;
                    if (matches) visibleCount++;
                    if (!matches && studentSelect.value === opt.value) {
                        studentSelect.value = '';
                    }
                });

                noStudentsHint.classList.toggle('hidden', visibleCount > 0);
            }

            function updateSummary() {
                summaryStudent.textContent = studentSelect.value
                    ? studentSelect.options[studentSelect.selectedIndex].textContent.trim()
                    : '—';
                summaryCategory.textContent = categorySelect.value
                    ? categorySelect.options[categorySelect.selectedIndex].textContent.trim()
                    : '—';
                const amt = parseFloat(amountInput.value);
                summaryAmount.textContent = 'Rs. ' + (isNaN(amt) ? '0.00' : amt.toFixed(2));
                summaryDue.textContent = dueDateInput.value || '—';
            }

            classSelect.addEventListener('change', function () {
                filterSections();
                filterStudents();
                updateSummary();
            });

            sectionSelect.addEventListener('change', function () {
                filterStudents();
                updateSummary();
            });

            studentSelect.addEventListener('change', updateSummary);
            categorySelect.addEventListener('change', updateSummary);
            amountInput.addEventListener('input', updateSummary);
            dueDateInput.addEventListener('change', updateSummary);

            // Run once on load (handles old() repopulation after validation errors)
            filterSections();
            filterStudents();
            updateSummary();
        })();
    </script>
</x-app-layout>