<x-app-layout>
    <div class="p-6">
        <h1 class="text-xl font-semibold mb-1">Discount</h1>
        <p class="text-gray-500 text-sm mb-6">Set Discount(s) to Student(s) on different Fee(s).</p>

        @if (session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif

        {{-- FILTER FORM (GET) --}}
        <form method="GET" action="{{ route('school-admin.fee-discounts.create') }}" class="flex flex-wrap gap-4 items-end mb-8 bg-gray-50 p-4 rounded-2xl">
            <div>
                <label class="block text-sm mb-1">Class</label>
                <select name="class_id" onchange="this.form.submit()" class="border rounded p-2">
                    <option value="">-- Select --</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if (request('class_id'))
                <div>
                    <label class="block text-sm mb-1">Section</label>
                    <select name="section_id" onchange="this.form.submit()" class="border rounded p-2">
                        <option value="">-- Select --</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if (request('class_id') && request('section_id'))
                <div>
                    <label class="block text-sm mb-1">Student</label>
                    <select name="student_id" onchange="this.form.submit()" class="border rounded p-2 min-w-[200px]">
                        <option value="">-- Select --</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                {{ trim($student->first_name . ' ' . $student->last_name) }}
                                @if($student->roll_number) - {{ $student->roll_number }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1">Billing Period</label>
                    <select name="billing_period_id" onchange="this.form.submit()" class="border rounded p-2">
                        <option value="">-- Select --</option>
                        @foreach ($billingPeriods as $period)
                            <option value="{{ $period->id }}" @selected(request('billing_period_id') == $period->id)>
                                {{ $period->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </form>

        {{-- FEE DISCOUNT TABLE (POST) --}}
        @if ($selectedStudent && $feeRows->isNotEmpty())
            <form method="POST" action="{{ route('school-admin.fee-discounts.store') }}">
                @csrf
                <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                <input type="hidden" name="billing_period_id" value="{{ request('billing_period_id') }}">

                <p class="mb-3 text-sm text-gray-600">
                    Student: <strong>{{ trim($selectedStudent->first_name . ' ' . $selectedStudent->last_name) }}</strong>
                </p>

                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 text-left">Fee Name</th>
                            <th class="p-2 text-right">Amount (Before Discount)</th>
                            <th class="p-2 text-center">Discount (%)</th>
                            <th class="p-2 text-center">Discount (Amount)</th>
                            <th class="p-2 text-left">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feeRows as $i => $row)
                            <tr class="border-t">
                                <td class="p-2">{{ $row->fee_name }}</td>
                                <td class="p-2 text-right">{{ number_format($row->amount_before, 2) }}</td>
                                <td class="p-2 text-center">
                                    <input type="hidden" name="discounts[{{ $i }}][fee_name_id]" value="{{ $row->fee_name_id }}">
                                    <input type="number" step="0.01" min="0" max="100"
                                           name="discounts[{{ $i }}][discount_percent]"
                                           value="{{ $row->discount_percent }}"
                                           class="w-24 border rounded p-1 text-right">
                                </td>
                                <td class="p-2 text-center">
                                    <input type="number" step="0.01" min="0"
                                           name="discounts[{{ $i }}][discount_amount]"
                                           value="{{ $row->discount_amount }}"
                                           class="w-24 border rounded p-1 text-right">
                                </td>
                                <td class="p-2">
                                    <input type="text" name="discounts[{{ $i }}][remarks]"
                                           value="{{ $row->remarks }}"
                                           class="w-full border rounded p-1">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary mt-4">Save</button>
            </form>
        @elseif (request('student_id'))
            <p class="text-gray-500">Your selected student does not have any active fee rates for the selected billing period.</p>
        @endif
    </div>
</x-app-layout>