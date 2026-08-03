<x-app-layout>
    <div class="p-6 max-w-6xl mx-auto">
        <div class="mb-6 flex items-baseline gap-2">
            <h1 class="text-2xl font-medium text-sky-600">Student Fee Payment</h1>
            <span class="text-gray-400">&raquo;</span>
            <p class="text-gray-400 text-sm">Pay student's fees.</p>
        </div>

        <div class="mb-4">
            <a href="{{ route('school-admin.fee-payments.create') }}" class="text-sm text-sky-600 hover:underline">&larr; Back to student list</a>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center justify-between gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </span>
                @if (session('payment_group'))
                    <a href="{{ route('school-admin.fee-payments.receipt', session('payment_group')) }}" target="_blank"
                       class="inline-flex items-center gap-1 rounded-md bg-green-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-700 transition-colors">
                        Print Receipt
                    </a>
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT: Fee ledger + Student Info --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                                <tr>
                                    <th class="p-3 text-left w-12">SN</th>
                                    <th class="p-3 text-left">Fee Category</th>
                                    <th class="p-3 text-right">Amount</th>
                                    <th class="p-3 text-right">Paid</th>
                                    <th class="p-3 text-right">Balance</th>
                                    <th class="p-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($studentFees as $i => $fee)
                                    @php $balance = $fee->amount - $fee->paid_amount; @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 text-gray-500">{{ $i + 1 }}</td>
                                        <td class="p-3 font-medium text-gray-800">{{ $fee->feeName->name ?? 'Fee' }}</td>
                                        <td class="p-3 text-right text-gray-600">{{ number_format($fee->amount, 2) }}</td>
                                        <td class="p-3 text-right text-gray-600">{{ number_format($fee->paid_amount, 2) }}</td>
                                        <td class="p-3 text-right font-medium {{ $balance > 0 ? 'text-red-600' : 'text-gray-500' }}">{{ number_format($balance, 2) }}</td>
                                        <td class="p-3 text-center">
                                            <span class="inline-block rounded-full text-xs px-2 py-0.5
                                                {{ $fee->status === 'paid' ? 'bg-green-100 text-green-700' : ($fee->status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                                {{ ucfirst($fee->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-6 text-center text-gray-400">No fees assigned to this student yet.</td></tr>
                                @endforelse
                            </tbody>
                            @if ($studentFees->isNotEmpty())
                                <tfoot>
                                    <tr class="border-t border-gray-200 bg-gray-50">
                                        <td colspan="4" class="p-3 text-right text-sm font-medium text-gray-600">Net Payable</td>
                                        <td class="p-3 text-right font-semibold text-gray-900">{{ number_format($netPayable, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-sky-50 border-b border-sky-100">
                        <span class="text-sm font-semibold text-sky-700">Student Info</span>
                    </div>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 p-5 text-sm">
                        <div class="flex justify-between sm:block">
                            <dt class="text-gray-500">Student ID</dt>
                            <dd class="font-medium text-gray-800">{{ $student->student_uid }}</dd>
                        </div>
                        <div class="flex justify-between sm:block">
                            <dt class="text-gray-500">Full Name</dt>
                            <dd class="font-medium text-gray-800">{{ trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name) }}</dd>
                        </div>
                        <div class="flex justify-between sm:block">
                            <dt class="text-gray-500">Class</dt>
                            <dd class="font-medium text-gray-800">{{ $student->schoolClass->name ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between sm:block">
                            <dt class="text-gray-500">Section</dt>
                            <dd class="font-medium text-gray-800">{{ $student->section->name ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between sm:block">
                            <dt class="text-gray-500">Roll Number</dt>
                            <dd class="font-medium text-gray-800">{{ $student->roll_number ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between sm:block">
                            <dt class="text-gray-500">Status</dt>
                            <dd class="font-medium text-gray-800">{{ ucfirst($student->status ?? '-') }}</dd>
                        </div>
                        <div class="flex justify-between sm:block">
                            <dt class="text-gray-500">Phone</dt>
                            <dd class="font-medium text-gray-800">{{ $student->phone ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between sm:block">
                            <dt class="text-gray-500">Email</dt>
                            <dd class="font-medium text-gray-800">{{ $student->email ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- RIGHT: Payment form --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-5 h-fit">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Record Payment</h2>

                @if ($netPayable <= 0)
                    <p class="text-sm text-gray-500">This student has no outstanding balance.</p>
                @else
                    <form method="POST" action="{{ route('school-admin.fee-payments.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Net Payable Amount</label>
                            <input type="text" value="{{ number_format($netPayable, 2) }}" disabled
                                   class="w-full rounded-md border-gray-200 bg-gray-50 text-gray-600 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Payment Amount <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" max="{{ $netPayable }}" name="amount"
                                   value="{{ old('amount', $netPayable) }}" required
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Payment Date <span class="text-red-500">*</span></label>
                            <input type="date" name="payment_date" required value="{{ old('payment_date', date('Y-m-d')) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Payment Method</label>
                            <select name="payment_method" class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Reference No.</label>
                            <input type="text" name="reference_no" value="{{ old('reference_no') }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Narration</label>
                            <textarea name="notes" rows="3"
                                      class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-sky-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-sky-700 transition-colors">
                            Save Payment
                        </button>
                        <p class="text-xs text-gray-400">
                            If the amount is less than the net payable, it's applied to the oldest pending fee(s) first.
                        </p>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Auto-open receipt in a new tab right after a successful payment save --}}
    @if (session('success') && session('payment_group'))
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                window.open("{{ route('school-admin.fee-payments.receipt', session('payment_group')) }}", '_blank');
            });
        </script>
    @endif
</x-app-layout>