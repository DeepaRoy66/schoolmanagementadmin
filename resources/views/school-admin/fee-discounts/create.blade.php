<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fee Discounts</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if (session('status'))
                <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-3.5 text-sm text-emerald-800 shadow-sm">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100">
                        <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            @endif

            {{-- MAIN CARD --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 sm:p-8">
                    {{-- Title Section --}}
                    <div class="flex items-start gap-4 mb-8">
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">Fee Discounts</h1>
                            <p class="mt-0.5 text-sm text-gray-500">Apply percentage or fixed discounts to a student’s fees for a specific billing period</p>
                        </div>
                    </div>

                    {{-- FILTER FORM --}}
                    <form method="GET" action="{{ route('school-admin.fee-discounts.create') }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                            {{-- Class --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                                    Class <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="class_id" onchange="this.form.submit()"
                                            class="appearance-none w-full rounded-xl border border-gray-200 bg-white pl-4 pr-10 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                        <option value="">Select class</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Section --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Section</label>
                                <div class="relative">
                                    @if (request('class_id'))
                                        <select name="section_id" onchange="this.form.submit()"
                                                class="appearance-none w-full rounded-xl border border-gray-200 bg-white pl-4 pr-10 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                            <option value="">Select section</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>
                                                    {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-full rounded-xl border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-gray-400">
                                            Select a class first
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Billing Period --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Billing Period</label>
                                <div class="relative">
                                    @if (request('class_id') && request('section_id'))
                                        <select name="billing_period_id"
                                                class="appearance-none w-full rounded-xl border border-gray-200 bg-white pl-4 pr-10 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                            <option value="">Select period</option>
                                            @foreach ($billingPeriods as $period)
                                                <option value="{{ $period->id }}" @selected(request('billing_period_id') == $period->id)>
                                                    {{ $period->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-full rounded-xl border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-gray-400">
                                            Select class & section
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Student --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                                    Student <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    @if (request('class_id') && request('section_id'))
                                        <select name="student_id"
                                                class="appearance-none w-full rounded-xl border border-gray-200 bg-white pl-4 pr-10 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                            <option value="">Select student</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                                    {{ trim($student->first_name . ' ' . $student->last_name) }}
                                                    @if($student->roll_number) — Roll {{ $student->roll_number }} @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-full rounded-xl border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-gray-400">
                                            Select class & section
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-1.35z" />
                                </svg>
                                Search Fees
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- FEE DISCOUNT TABLE (same as before) --}}
            @if ($selectedStudent && $feeRows->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Student Header --}}
                    <div class="px-6 sm:px-8 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-semibold text-sm">
                            {{ strtoupper(substr($selectedStudent->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Applying discount for</p>
                            <p class="text-[15px] font-semibold text-gray-900">
                                {{ trim($selectedStudent->first_name . ' ' . $selectedStudent->last_name) }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('school-admin.fee-discounts.store') }}" class="p-6 sm:p-8">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                        <input type="hidden" name="billing_period_id" value="{{ request('billing_period_id') }}">

                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table id="feeDiscountTable" class="w-full text-sm text-left">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                                        <th class="py-3.5 px-5 font-semibold text-xs uppercase tracking-wider">Fee Name</th>
                                        <th class="py-3.5 px-5 font-semibold text-xs uppercase tracking-wider text-right">Amount</th>
                                        <th class="py-3.5 px-5 font-semibold text-xs uppercase tracking-wider text-center">Discount %</th>
                                        <th class="py-3.5 px-5 font-semibold text-xs uppercase tracking-wider text-center">Discount Amt</th>
                                        <th class="py-3.5 px-5 font-semibold text-xs uppercase tracking-wider text-right">Net Amount</th>
                                        <th class="py-3.5 px-5 font-semibold text-xs uppercase tracking-wider">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($feeRows as $i => $row)
                                        <tr class="hover:bg-blue-50/40 transition-colors" data-amount="{{ $row->amount_before }}">
                                            <td class="py-3.5 px-5 font-medium text-gray-800">{{ $row->fee_name }}</td>
                                            <td class="py-3.5 px-5 text-right text-gray-500 tabular-nums js-amount">
                                                Rs. {{ number_format($row->amount_before, 2) }}
                                            </td>
                                            <td class="py-3.5 px-5">
                                                <input type="hidden" name="discounts[{{ $i }}][fee_name_id]" value="{{ $row->fee_name_id }}">
                                                <input type="number" step="0.01" min="0" max="100"
                                                       name="discounts[{{ $i }}][discount_percent]"
                                                       value="{{ $row->discount_percent }}"
                                                       class="js-percent w-20 mx-auto block rounded-lg border border-gray-200 bg-white py-1.5 text-center text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            </td>
                                            <td class="py-3.5 px-5">
                                                <input type="number" step="0.01" min="0"
                                                       name="discounts[{{ $i }}][discount_amount]"
                                                       value="{{ $row->discount_amount }}"
                                                       class="js-discount w-24 mx-auto block rounded-lg border border-gray-200 bg-white py-1.5 text-center text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            </td>
                                            <td class="py-3.5 px-5 text-right">
                                                <span class="js-net inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 text-xs font-semibold tabular-nums">
                                                    Rs. {{ number_format($row->net_amount, 2) }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-5">
                                                <input type="text" name="discounts[{{ $i }}][remarks]"
                                                       value="{{ $row->remarks }}"
                                                       placeholder="Optional note..."
                                                       class="w-full rounded-lg border border-gray-200 bg-white py-1.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 border-t border-gray-200 font-semibold text-gray-900">
                                        <td class="py-4 px-5">Total</td>
                                        <td id="totalAmount" class="py-4 px-5 text-right tabular-nums">Rs. 0.00</td>
                                        <td class="py-4 px-5"></td>
                                        <td id="totalDiscount" class="py-4 px-5 text-center tabular-nums text-rose-600">Rs. 0.00</td>
                                        <td id="totalNet" class="py-4 px-5 text-right tabular-nums text-emerald-700">Rs. 0.00</td>
                                        <td class="py-4 px-5"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <script>
                            (function () {
                                const table = document.getElementById('feeDiscountTable');
                                if (!table) return;

                                function fmt(n) {
                                    return 'Rs. ' + n.toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }

                                function recalcRow(row) {
                                    const amount = parseFloat(row.dataset.amount) || 0;
                                    const percentInput = row.querySelector('.js-percent');
                                    const discountInput = row.querySelector('.js-discount');
                                    let discount = parseFloat(discountInput.value) || 0;

                                    if (document.activeElement === percentInput) {
                                        const percent = parseFloat(percentInput.value) || 0;
                                        discount = amount * (percent / 100);
                                        discountInput.value = discount.toFixed(2);
                                    }

                                    const net = Math.max(amount - discount, 0);
                                    row.querySelector('.js-net').textContent = fmt(net);
                                    row.dataset.net = net;
                                    row.dataset.discount = discount;
                                }

                                function recalcTotals() {
                                    let totalAmount = 0, totalDiscount = 0, totalNet = 0;
                                    table.querySelectorAll('tbody tr').forEach(row => {
                                        recalcRow(row);
                                        totalAmount += parseFloat(row.dataset.amount) || 0;
                                        totalDiscount += parseFloat(row.dataset.discount) || 0;
                                        totalNet += parseFloat(row.dataset.net) || 0;
                                    });
                                    document.getElementById('totalAmount').textContent = fmt(totalAmount);
                                    document.getElementById('totalDiscount').textContent = fmt(totalDiscount);
                                    document.getElementById('totalNet').textContent = fmt(totalNet);
                                }

                                table.addEventListener('input', function (e) {
                                    if (e.target.classList.contains('js-percent') || e.target.classList.contains('js-discount')) {
                                        recalcTotals();
                                    }
                                });

                                recalcTotals();
                            })();
                        </script>

                        <div class="mt-7 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-gray-800 transition-all">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Save Discounts
                            </button>
                        </div>
                    </form>
                </div>

            @elseif (request('student_id'))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 mb-4">
                        <svg class="h-7 w-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">No active fees found</h3>
                    <p class="mt-1.5 text-sm text-gray-500">This student has no active fee rates for the selected billing period.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>