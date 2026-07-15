<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fees
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 text-sm">Total records: {{ $fees->total() }}</p>
                    <a href="{{ route('school-admin.fees.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Fee Record
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Student</th>
                            <th class="py-2">Title</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Paid</th>
                            <th class="py-2">Status</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fees as $fee)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $fee->student->name ?? '—' }}</td>
                                <td class="py-3">{{ $fee->title }}</td>
                                <td class="py-3">Rs. {{ number_format($fee->amount, 2) }}</td>
                                <td class="py-3">Rs. {{ number_format($fee->paid_amount, 2) }}</td>
                                <td class="py-3">
                                    @if ($fee->status === 'paid')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Paid</span>
                                    @elseif ($fee->status === 'partial')
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-semibold">Partial</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">Unpaid</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right space-x-2">
                                    @if ($fee->status !== 'paid')
                                        <button type="button" onclick="document.getElementById('pay-modal-{{ $fee->id }}').classList.remove('hidden')" class="text-blue-600 hover:underline">Record Payment</button>
                                    @endif
                                    <form action="{{ route('school-admin.fees.destroy', $fee) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo fee record delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            @if ($fee->status !== 'paid')
                                <div id="pay-modal-{{ $fee->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
                                        <h3 class="font-semibold text-gray-800 mb-4">Record Payment: {{ $fee->student->name ?? '' }}</h3>
                                        <p class="text-sm text-gray-500 mb-4">Total: Rs. {{ number_format($fee->amount, 2) }} | Already paid: Rs. {{ number_format($fee->paid_amount, 2) }}</p>

                                        <form action="{{ route('school-admin.fees.payment', $fee) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Paid Amount (so far)</label>
                                                <input type="number" step="0.01" name="paid_amount" value="{{ $fee->paid_amount }}"
                                                       max="{{ $fee->amount }}" class="w-full border-gray-300 rounded-lg" required>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                                                    Update Payment
                                                </button>
                                                <button type="button" onclick="document.getElementById('pay-modal-{{ $fee->id }}').classList.add('hidden')" class="text-gray-600 text-sm hover:underline">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-500">
                                    No fee records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $fees->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>