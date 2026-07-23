<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Record New Payment
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-700 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($studentFees->isEmpty())
                    <p class="text-sm text-gray-500">
                        No unpaid/partial fees found. All fees may already be fully paid, or no fees have been assigned yet.
                    </p>
                @else
                    <form action="{{ route('school-admin.fee-payments.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Student Fee</label>
                            <select name="student_fee_id" required
                                    class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                                <option value="">-- Select Fee Record --</option>
                                @foreach($studentFees as $fee)
                                    <option value="{{ $fee->id }}" @selected(old('student_fee_id') == $fee->id)>
                                        {{ $fee->student->first_name }} {{ $fee->student->last_name }}
                                        — {{ $fee->feeCategory->name ?? 'Uncategorized' }}
                                        (Due: {{ number_format($fee->amount - $fee->paid_amount, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                            <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required
                                   class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
                                   class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method (optional)</label>
                            <input type="text" name="payment_method" value="{{ old('payment_method') }}"
                                   placeholder="e.g. Cash, Bank Transfer, eSewa"
                                   class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reference No. (optional)</label>
                            <input type="text" name="reference_no" value="{{ old('reference_no') }}"
                                   class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                            <textarea name="notes" rows="3"
                                      class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-[#2dd4bf] text-white text-sm font-medium rounded-xl hover:bg-teal-500 transition-colors">
                                Record Payment
                            </button>
                            <a href="{{ route('school-admin.fee-payments.index') }}"
                               class="text-sm text-gray-600 hover:underline">Cancel</a>
                        </div>

                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>