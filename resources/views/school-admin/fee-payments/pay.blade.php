<x-app-layout>
    <div class="p-6 max-w-[1600px] mx-auto">

        {{-- Header --}}
        <div class="mb-4 flex items-baseline gap-2">
            <h1 class="text-2xl font-medium text-sky-600">Student Fee Payment</h1>
            <span class="text-gray-400">&raquo;</span>
            <p class="text-gray-400 text-sm">Pay student's fees.</p>
        </div>

        @if (session('success'))
            <div class="mb-4 flex items-center gap-2 border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Payment could not be saved
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('school-admin.fee-payments.pay-store', $student->id) }}">
            @csrf

        {{-- FILTER BAR (plain, no card shadow) --}}
        <div class="mb-4 pb-4 border-b border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Date From</label>
                    <input type="text" name="date_from" value="{{ $dateFrom ?? '' }}"
                           class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">
                        Payment Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="payment_date" value="{{ old('payment_date', $paymentDate ?? '') }}"
                           class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('payment_date') border-red-400 @enderror">
                    @error('payment_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">
                        Payment Type <span class="text-rose-500">*</span>
                    </label>
                    <select name="payment_type" id="paymentType"
                            class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('payment_type') border-red-400 @enderror">
                        <option value="">-- Select --</option>
                        @foreach ($paymentTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('payment_type') == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('payment_type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">
                        Student's Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="w-full rounded border border-gray-300 bg-gray-50 text-sm px-3 py-1.5 text-gray-700">
                        {{ $student->full_name }} — {{ $student->student_uid }}
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('school-admin.fee-payments.statement', $student->id) }}"
                       class="inline-flex items-center gap-2 rounded bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition-colors whitespace-nowrap">
                        Fee Statement
                    </a>
                    <a href="{{ route('school-admin.fee-payments.fine-waive', $student->id) }}"
                       class="inline-flex items-center gap-2 rounded bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600 transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Fine Waive
                    </a>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">Sponsor Name</label>
                    <select name="sponsor_id" class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">-- Select --</option>
                        @foreach ($sponsors as $sponsor)
                            <option value="{{ $sponsor->id }}" @selected(old('sponsor_id') == $sponsor->id)>{{ $sponsor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">Project</label>
                    <select name="project_id" class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">-- Select --</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm text-gray-700 mb-1">Payment Partner</label>
                    <input type="text" name="payment_partner" value="{{ old('payment_partner') }}"
                           class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                </div>
            </div>

            {{-- Dynamic Payment-Type Extra Fields --}}
            <div id="paymentTypeExtra" class="hidden mt-4 pt-4 border-t border-dashed border-gray-200">
                <div class="text-xs font-semibold uppercase tracking-wide text-sky-700 mb-2">Payment Details</div>
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div data-fields="bank,cheque" class="hidden">
                        <label class="block text-sm text-gray-700 mb-1">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                               class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div data-fields="bank,cheque" class="hidden">
                        <label class="block text-sm text-gray-700 mb-1">Branch</label>
                        <input type="text" name="bank_branch" value="{{ old('bank_branch') }}"
                               class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div data-fields="bank" class="hidden">
                        <label class="block text-sm text-gray-700 mb-1">Account No.</label>
                        <input type="text" name="account_no" value="{{ old('account_no') }}"
                               class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div data-fields="cheque" class="hidden">
                        <label class="block text-sm text-gray-700 mb-1">Cheque No.</label>
                        <input type="text" name="cheque_no" value="{{ old('cheque_no') }}"
                               class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div data-fields="cheque" class="hidden">
                        <label class="block text-sm text-gray-700 mb-1">Cheque Date</label>
                        <input type="text" name="cheque_date" value="{{ old('cheque_date') }}"
                               class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500 datepicker">
                    </div>
                    <div data-fields="online,esewa,khalti" class="hidden">
                        <label class="block text-sm text-gray-700 mb-1">Transaction ID</label>
                        <input type="text" name="transaction_id" value="{{ old('transaction_id') }}"
                               class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- TRANSACTION LEDGER — classic grid-line table --}}
        <div class="mb-6 overflow-x-auto">
            <table class="w-full text-sm border border-gray-300 border-collapse">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="border border-gray-300 p-2 text-left">S.N.</th>
                        <th class="border border-gray-300 p-2 text-left">Date</th>
                        <th class="border border-gray-300 p-2 text-left">Transaction Type</th>
                        <th class="border border-gray-300 p-2 text-left">Transaction No.</th>
                        <th class="border border-gray-300 p-2 text-left">Period</th>
                        <th class="border border-gray-300 p-2 text-left">Remarks</th>
                        <th class="border border-gray-300 p-2 text-right">Dr Amount</th>
                        <th class="border border-gray-300 p-2 text-right">Fine</th>
                        <th class="border border-gray-300 p-2 text-right">Cr Amount</th>
                        <th class="border border-gray-300 p-2 text-right">Fine Waive</th>
                        <th class="border border-gray-300 p-2 text-right">Rebate</th>
                        <th class="border border-gray-300 p-2 text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $i => $txn)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 p-2 text-gray-600">{{ $i + 1 }}</td>
                            <td class="border border-gray-300 p-2 text-gray-700">{{ $txn->date ?? '-' }}</td>
                            <td class="border border-gray-300 p-2 font-medium text-gray-800">{{ $txn->transaction_type }}</td>
                            <td class="border border-gray-300 p-2">
                                @if ($txn->transaction_no)
                                    <a href="{{ $txn->transaction_url ?? '#' }}" class="text-sky-600 hover:underline">{{ $txn->transaction_no }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border border-gray-300 p-2 text-gray-700">{{ $txn->period ?? '-' }}</td>
                            <td class="border border-gray-300 p-2 text-gray-700">{{ $txn->remarks ?? '-' }}</td>
                            <td class="border border-gray-300 p-2 text-right tabular-nums">{{ number_format($txn->dr_amount, 0) }}</td>
                            <td class="border border-gray-300 p-2 text-right tabular-nums">{{ number_format($txn->fine, 0) }}</td>
                            <td class="border border-gray-300 p-2 text-right tabular-nums">{{ number_format($txn->cr_amount, 0) }}</td>
                            <td class="border border-gray-300 p-2 text-right tabular-nums">{{ number_format($txn->fine_waive, 0) }}</td>
                            <td class="border border-gray-300 p-2 text-right tabular-nums">{{ number_format($txn->rebate, 0) }}</td>
                            <td class="border border-gray-300 p-2 text-right font-medium tabular-nums {{ $txn->balance_type === 'Cr' ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ number_format(abs($txn->balance), 0) }} ({{ $txn->balance_type }})
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td colspan="10" class="border border-gray-300 p-2"></td>
                        <td class="border border-gray-300 p-2 text-right font-semibold text-gray-700">Net Payable</td>
                        <td class="border border-gray-300 p-2 text-right font-semibold tabular-nums {{ $netPayableType === 'Cr' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ number_format(abs($netPayable), 0) }} ({{ $netPayableType }})
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- STUDENT INFO + PAYMENT FORM --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Student Info — classic table style (first code) but with ALL fields (second code) --}}
                <div class="lg:col-span-2">
                    <span class="inline-block bg-indigo-500 text-white text-xs font-semibold uppercase tracking-wide px-4 py-1.5 rounded-sm mb-0">
                        Student Info
                    </span>

                    <table class="w-full text-sm border border-gray-300 border-collapse mt-0">
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600 w-40">Student ID:</td>
                                <td class="border border-gray-300 p-2 text-gray-800 font-medium">{{ $student->student_uid ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600 w-40">Full Name:</td>
                                <td class="border border-gray-300 p-2 text-gray-800 font-medium">{{ $student->full_name }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Level:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->level ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Program - Batch:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->schoolClass->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Program Semester:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->semester ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Section:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->section->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">CRN:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->roll_number ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Status:</td>
                                <td class="border border-gray-300 p-2">
                                    <span class="text-green-700 font-medium">{{ ucfirst($student->status ?? '-') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Gender:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ ucfirst($student->gender ?? '-') }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Date of Birth:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ optional($student->dob)->format('Y-m-d') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Student Mobile:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->phone ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Telephone No.:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->telephone_no ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Email:</td>
                                <td class="border border-gray-300 p-2 text-gray-800" colspan="3">{{ $student->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600 align-top">Address:</td>
                                <td class="border border-gray-300 p-2 text-gray-800" colspan="3">{{ $student->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Parent Name:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->parent_name ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Parent Phone:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->parent_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Father Name:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->father_name ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Father Mobile:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->father_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Mother Name:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->mother_name ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Mother Mobile:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->mother_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Local Guardian:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->local_guardian_name ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Guardian Mobile:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->local_guardian_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Emergency Contact:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">
                                    {{ $student->emergency_contact_name ?? '-' }}
                                    @if ($student->emergency_contact_relation)
                                        <span class="text-gray-400">({{ $student->emergency_contact_relation }})</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Emergency Phone:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->emergency_contact_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Student Category:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->category ?? 'General' }}</td>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600">Student Type:</td>
                                <td class="border border-gray-300 p-2 text-gray-800">{{ $student->type ?? 'General' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-50 text-gray-600 align-top">Description:</td>
                                <td colspan="3" class="border border-gray-300 p-2">
                                    <textarea name="description" rows="2"
                                              class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">{{ old('description') }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 flex flex-wrap items-center gap-6">
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-700">SMS To</label>
                            <select name="sms_to" class="rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                                <option value="student">Student</option>
                                <option value="guardian">Guardian</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="send_sms" value="1" class="rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                            Send Sms
                        </label>
                    </div>
                </div>

                {{-- Payment Panel --}}
                <div class="border border-gray-300 p-5 h-fit">
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Net Payable Amount</label>
                        <input type="text" value="{{ number_format(abs($netPayable), 2) }}" disabled
                               class="w-full rounded border-gray-300 bg-gray-50 text-sm text-gray-500">
                    </div>

                    <div class="mb-4 flex items-center gap-3">
                        <label class="text-sm text-gray-700 w-24 shrink-0">Discount</label>
                        <input type="checkbox" name="apply_discount" value="1" id="applyDiscount"
                               class="rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                        <input type="number" step="0.01" name="discount_amount" id="discountAmount" value="0.00"
                               class="flex-1 rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Payment Amount</label>
                        <input type="number" step="0.01" name="payment_amount" id="paymentAmount"
                               value="{{ old('payment_amount', number_format(abs($netPayable), 2, '.', '')) }}"
                               class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500 @error('payment_amount') border-red-400 @enderror">
                        @error('payment_amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm text-gray-700 mb-1">Narration</label>
                        <textarea name="narration" rows="3"
                                  class="w-full rounded border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">{{ old('narration') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-8H7v8M7 3v5h8M5 3h11l4 4v11a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        (function () {
            // Discount toggle
            const applyDiscount = document.getElementById('applyDiscount');
            const discountAmount = document.getElementById('discountAmount');
            if (applyDiscount && discountAmount) {
                discountAmount.disabled = !applyDiscount.checked;
                applyDiscount.addEventListener('change', function () {
                    discountAmount.disabled = !this.checked;
                    if (!this.checked) discountAmount.value = '0.00';
                });
            }

            // Payment type -> dynamic extra fields
            const paymentType = document.getElementById('paymentType');
            const extraWrap = document.getElementById('paymentTypeExtra');
            const fieldGroups = document.querySelectorAll('[data-fields]');

            function toggleExtraFields() {
                const val = (paymentType.value || '').toLowerCase();
                let anyVisible = false;

                fieldGroups.forEach(function (group) {
                    const keys = group.dataset.fields.split(',');
                    const matched = keys.some(function (k) { return val.includes(k); });
                    group.classList.toggle('hidden', !matched);
                    if (matched) anyVisible = true;
                });

                extraWrap.classList.toggle('hidden', !anyVisible);
            }

            if (paymentType && extraWrap) {
                paymentType.addEventListener('change', toggleExtraFields);
                toggleExtraFields();
            }
        })();
    </script>
</x-app-layout>