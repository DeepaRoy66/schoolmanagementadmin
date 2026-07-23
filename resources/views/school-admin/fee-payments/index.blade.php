<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fee Payments
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">All Payments</h3>
                    <a href="{{ route('school-admin.fee-payments.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#2dd4bf] text-white text-sm font-medium rounded-xl hover:bg-teal-500 transition-colors">
                        + Record New Payment
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Payment Date</th>
                                <th class="px-4 py-3">Method</th>
                                <th class="px-4 py-3">Reference No</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($payments as $payment)
                                <tr class="text-sm text-gray-700">
                                    <td class="px-4 py-3">
                                        {{ $payment->studentFee->student->first_name }}
                                        {{ $payment->studentFee->student->last_name }}
                                    </td>
                                    <td class="px-4 py-3">{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ $payment->payment_method ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $payment->reference_no ?? '-' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <form action="{{ route('school-admin.fee-payments.destroy', $payment->id) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Delete this payment record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-400 text-sm">
                                        No payment records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $payments->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>