<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Discount</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <p class="text-blue-500 text-lg font-medium mb-1">Discount</p>
                <p class="text-gray-500 text-sm mb-6">&raquo; Set Discount(s) to Student(s) on different Fee(s).</p>

                {{-- FILTER FORM (GET) --}}
                <form method="GET" action="{{ route('school-admin.fee-discounts.create') }}">
                    <div class="divide-y divide-gray-100">

                        {{-- Class --}}
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center py-3">
                            <label class="text-sm font-medium text-gray-700 sm:col-span-1">
                                Class <span class="text-red-500">*</span>
                            </label>
                            <div class="sm:col-span-3">
                                <select name="class_id" onchange="this.form.submit()" class="w-full sm:w-1/2 border-gray-300 rounded-lg">
                                    <option value="">-- Select Class --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Section --}}
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center py-3">
                            <label class="text-sm font-medium text-gray-700 sm:col-span-1">Section</label>
                            <div class="sm:col-span-3">
                                @if (request('class_id'))
                                    <select name="section_id" onchange="this.form.submit()" class="w-full sm:w-1/2 border-gray-300 rounded-lg">
                                        <option value="">-- Select Section --</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select disabled class="w-full sm:w-1/2 border-gray-200 rounded-lg bg-gray-50 text-gray-400">
                                        <option>Select a class first</option>
                                    </select>
                                @endif
                            </div>
                        </div>

                        {{-- Billing Period --}}
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center py-3">
                            <label class="text-sm font-medium text-gray-700 sm:col-span-1">Billing Period</label>
                            <div class="sm:col-span-3">
                                @if (request('class_id') && request('section_id'))
                                    <select name="billing_period_id" class="w-full sm:w-1/2 border-gray-300 rounded-lg">
                                        <option value="">-- Select Period --</option>
                                        @foreach ($billingPeriods as $period)
                                            <option value="{{ $period->id }}" @selected(request('billing_period_id') == $period->id)>
                                                {{ $period->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select disabled class="w-full sm:w-1/2 border-gray-200 rounded-lg bg-gray-50 text-gray-400">
                                        <option>Select class & section first</option>
                                    </select>
                                @endif
                            </div>
                        </div>

                        {{-- Student --}}
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center py-3">
                            <label class="text-sm font-medium text-gray-700 sm:col-span-1">
                                Student <span class="text-red-500">*</span>
                            </label>
                            <div class="sm:col-span-3">
                                @if (request('class_id') && request('section_id'))
                                    <select name="student_id" class="w-full sm:w-1/2 border-gray-300 rounded-lg">
                                        <option value="">-- Select Student --</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                                {{ trim($student->first_name . ' ' . $student->last_name) }}
                                                @if($student->roll_number) - {{ $student->roll_number }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select disabled class="w-full sm:w-1/2 border-gray-200 rounded-lg bg-gray-50 text-gray-400">
                                        <option>Select class & section first</option>
                                    </select>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="mt-6">
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-1.35z" />
                            </svg>
                            Search
                        </button>
                    </div>
                </form>
            </div>

            {{-- FEE DISCOUNT TABLE (POST) --}}
            @if ($selectedStudent && $feeRows->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="mb-4 text-sm text-gray-600">
                        Student: <strong class="text-gray-900">{{ trim($selectedStudent->first_name . ' ' . $selectedStudent->last_name) }}</strong>
                    </p>

                    <form method="POST" action="{{ route('school-admin.fee-discounts.store') }}">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                        <input type="hidden" name="billing_period_id" value="{{ request('billing_period_id') }}">

                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b text-gray-500">
                                    <th class="py-2">Fee Name</th>
                                    <th class="py-2 text-right">Amount (Before Discount)</th>
                                    <th class="py-2 text-center">Discount (%)</th>
                                    <th class="py-2 text-center">Discount (Amount)</th>
                                    <th class="py-2">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($feeRows as $i => $row)
                                    <tr class="border-b">
                                        <td class="py-3 font-medium">{{ $row->fee_name }}</td>
                                        <td class="py-3 text-right text-gray-600">Rs. {{ number_format($row->amount_before, 2) }}</td>
                                        <td class="py-3 text-center">
                                            <input type="hidden" name="discounts[{{ $i }}][fee_name_id]" value="{{ $row->fee_name_id }}">
                                            <input type="number" step="0.01" min="0" max="100"
                                                   name="discounts[{{ $i }}][discount_percent]"
                                                   value="{{ $row->discount_percent }}"
                                                   class="w-24 border-gray-300 rounded-lg text-right">
                                        </td>
                                        <td class="py-3 text-center">
                                            <input type="number" step="0.01" min="0"
                                                   name="discounts[{{ $i }}][discount_amount]"
                                                   value="{{ $row->discount_amount }}"
                                                   class="w-24 border-gray-300 rounded-lg text-right">
                                        </td>
                                        <td class="py-3">
                                            <input type="text" name="discounts[{{ $i }}][remarks]"
                                                   value="{{ $row->remarks }}"
                                                   class="w-full border-gray-300 rounded-lg">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit"
                                class="mt-6 bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Save Discounts
                        </button>
                    </form>
                </div>
            @elseif (request('student_id'))
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center text-gray-500 text-sm">
                    Selected student does not have any active fee rates for the selected billing period.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>