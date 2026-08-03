<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fee Assign</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-500 text-sm mb-6">Set fee of student(s), based on predefined Fee Rates for the selected class.</p>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    {{-- Step 1: Class / Section / Billing Period / Is Individual (GET, auto-reload) --}}
                    <form method="GET" action="{{ route('school-admin.fee-assign.create') }}" class="contents">
                        <div class="lg:col-start-1 lg:col-span-4 lg:row-start-1 space-y-4">

                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="is_individual" value="1" onchange="this.form.submit()"
                                       {{ request()->boolean('is_individual') ? 'checked' : '' }}
                                       id="is_individual_filter" class="rounded border-gray-300">
                                <label for="is_individual_filter" class="text-sm font-medium text-gray-700">
                                    Is Individual
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Class <span class="text-red-500">*</span>
                                </label>
                                <select name="class_id" onchange="this.form.submit()" class="w-full border-gray-300 rounded-lg">
                                    <option value="">-- Select --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                                @if (request('class_id'))
                                    <select name="section_id" onchange="this.form.submit()" class="w-full border-gray-300 rounded-lg">
                                        <option value="">-- All Sections --</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select disabled class="w-full border-gray-200 rounded-lg bg-gray-50 text-gray-400">
                                        <option>Select a class first</option>
                                    </select>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Billing Period <span class="text-red-500">*</span>
                                </label>
                                <select name="billing_period_id" onchange="this.form.submit()" class="w-full border-gray-300 rounded-lg">
                                    <option value="">-- Select --</option>
                                    @foreach ($billingPeriods as $period)
                                        <option value="{{ $period->id }}" @selected(request('billing_period_id') == $period->id)>
                                            {{ $period->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    {{-- Step 2: Full assignment form (POST) — left bottom fields + right fee table --}}
                    @if (request('class_id') && request('billing_period_id'))
                        @if ($feeRates->isEmpty())
                            <div class="lg:col-start-1 lg:col-span-12">
                                <div class="border rounded-lg p-4 bg-red-50 border-red-200">
                                    <p class="text-sm text-red-600">
                                        No Fee Rates found for this Class + Billing Period. Please add Fee Rates first before assigning.
                                    </p>
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('school-admin.fee-assign.store') }}" id="assignFeeForm" class="contents">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                                <input type="hidden" name="section_id" value="{{ request('section_id') }}">
                                <input type="hidden" name="billing_period_id" value="{{ request('billing_period_id') }}">
                                <input type="hidden" name="is_individual" value="{{ request()->boolean('is_individual') ? 1 : 0 }}">

                                {{-- Left bottom: student (if individual), dates, narration, buttons --}}
                                <div class="lg:col-start-1 lg:col-span-4 lg:row-start-2 space-y-4">

                                    @if (request()->boolean('is_individual'))
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Student Name <span class="text-red-500">*</span>
                                            </label>
                                            <select name="student_id" id="studentSelect" onchange="document.getElementById('studentPreview').textContent = this.options[this.selectedIndex].text || '';" class="w-full border-gray-300 rounded-lg" required>
                                                <option value="">-- Select Student --</option>
                                                @foreach ($students as $student)
                                                    <option value="{{ $student->id }}">
                                                        {{ trim($student->first_name . ' ' . $student->last_name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('student_id')
                                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                            <p class="text-sm text-amber-600 bg-amber-50 rounded-lg p-3 mt-2">
                                                Assigning fee to: <strong id="studentPreview">-- none selected --</strong>
                                            </p>
                                        </div>
                                    @else
                                        <div class="text-sm text-amber-600 bg-amber-50 rounded-lg p-3">
                                            <p class="mb-2">
                                                This will assign fees to <strong>all students</strong> in the selected class{{ request('section_id') ? ' & section' : '' }}
                                                ({{ $students->count() }} student{{ $students->count() === 1 ? '' : 's' }}).
                                            </p>
                                            @if ($students->isNotEmpty())
                                                <ul class="max-h-40 overflow-y-auto list-disc list-inside space-y-0.5 text-amber-800">
                                                    @foreach ($students as $student)
                                                        <li>{{ trim($student->first_name . ' ' . $student->last_name) }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endif

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Billing Date <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="billing_date" class="w-full border-gray-300 rounded-lg" required>
                                        @error('billing_date')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Due Date <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="due_date" class="w-full border-gray-300 rounded-lg" required>
                                        @error('due_date')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Narration</label>
                                        <input type="text" name="narration" class="w-full border-gray-300 rounded-lg">
                                    </div>

                                    <div class="flex items-center gap-3 pt-2">
                                        <button type="submit"
                                                class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                                            Assign Fee
                                        </button>
                                        <a href="{{ route('school-admin.fee-assign.create') }}"
                                           class="bg-amber-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">
                                            Cancel
                                        </a>
                                    </div>
                                </div>

                                {{-- Right: fee table --}}
                                <div class="lg:col-start-5 lg:col-span-8 lg:row-start-1 lg:row-span-2">
                                    <div class="border rounded-lg overflow-hidden">
                                        <table class="w-full text-sm">
                                            <thead class="bg-gray-50 text-gray-600 text-left">
                                                <tr>
                                                    <th class="px-3 py-2 w-10">
                                                        <input type="checkbox" id="selectAllFees" class="rounded border-gray-300" checked>
                                                    </th>
                                                    <th class="px-3 py-2">Fee Name</th>
                                                    <th class="px-3 py-2 text-right">Rate</th>
                                                    <th class="px-3 py-2 text-right">Quantity</th>
                                                    <th class="px-3 py-2 text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($feeRates as $rate)
                                                    <tr>
                                                        <td class="px-3 py-2">
                                                            <input type="checkbox"
                                                                   name="fee_rate_ids[]"
                                                                   value="{{ $rate->id }}"
                                                                   class="fee-row-checkbox rounded border-gray-300"
                                                                   data-rate="{{ $rate->amount }}"
                                                                   checked>
                                                        </td>
                                                        <td class="px-3 py-2 text-gray-800">
                                                            {{ $rate->feeName->name ?? 'Fee #' . $rate->fee_name_id }}
                                                        </td>
                                                        <td class="px-3 py-2 text-right">
                                                            <input type="text" value="{{ number_format($rate->amount, 2) }}" readonly
                                                                   class="w-24 text-right border-gray-200 rounded-lg bg-gray-50 text-gray-600">
                                                        </td>
                                                        <td class="px-3 py-2 text-right">
                                                            <input type="number" min="1" step="1" value="1"
                                                                   name="quantities[{{ $rate->id }}]"
                                                                   class="fee-row-qty w-20 text-right border-gray-300 rounded-lg">
                                                        </td>
                                                        <td class="px-3 py-2 text-right">
                                                            <input type="text" readonly
                                                                   class="fee-row-amount w-24 text-right border-gray-200 rounded-lg bg-gray-50 text-gray-800 font-medium"
                                                                   value="{{ number_format($rate->amount, 2) }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @error('fee_rate_ids')
                                        <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-2">
                                        Note: fees already assigned to a student for this billing period will be skipped automatically.
                                    </p>
                                </div>
                            </form>

                            <script>
                                (function () {
                                    const selectAll = document.getElementById('selectAllFees');
                                    const rows = document.querySelectorAll('#assignFeeForm tbody tr');

                                    function recalcRow(row) {
                                        const cb = row.querySelector('.fee-row-checkbox');
                                        const qty = row.querySelector('.fee-row-qty');
                                        const amountEl = row.querySelector('.fee-row-amount');
                                        const rate = parseFloat(cb.dataset.rate || 0);
                                        const q = parseFloat(qty.value || 0);
                                        amountEl.value = (rate * q).toFixed(2);
                                        qty.disabled = !cb.checked;
                                    }

                                    rows.forEach(row => {
                                        const cb = row.querySelector('.fee-row-checkbox');
                                        const qty = row.querySelector('.fee-row-qty');
                                        cb.addEventListener('change', () => recalcRow(row));
                                        qty.addEventListener('input', () => recalcRow(row));
                                        recalcRow(row);
                                    });

                                    selectAll.addEventListener('change', function () {
                                        rows.forEach(row => {
                                            row.querySelector('.fee-row-checkbox').checked = selectAll.checked;
                                            recalcRow(row);
                                        });
                                    });
                                })();
                            </script>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>