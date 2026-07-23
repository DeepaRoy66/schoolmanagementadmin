<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fee Reports
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Fees</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalFees, 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Collected</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">{{ number_format($totalCollected, 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pending</p>
                    <p class="text-2xl font-bold text-red-500 mt-2">{{ number_format($totalPending, 2) }}</p>
                </div>
            </div>

            {{-- Status Breakdown --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">No. of Records</th>
                                <th class="px-4 py-3">Total Amount</th>
                                <th class="px-4 py-3">Total Paid</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($statusCounts as $row)
                                <tr class="text-sm text-gray-700">
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColors = [
                                                'paid' => 'bg-green-100 text-green-700',
                                                'partial' => 'bg-amber-100 text-amber-700',
                                                'unpaid' => 'bg-gray-100 text-gray-700',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$row->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $row->total }}</td>
                                    <td class="px-4 py-3">{{ number_format($row->total_amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ number_format($row->total_paid, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-400 text-sm">
                                        No fee records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Fees --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Fee Records</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Paid</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Due Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentFees as $fee)
                                <tr class="text-sm text-gray-700">
                                    <td class="px-4 py-3">
                                        {{ $fee->student->first_name ?? '-' }} {{ $fee->student->last_name ?? '' }}
                                    </td>
                                    <td class="px-4 py-3">{{ $fee->title }}</td>
                                    <td class="px-4 py-3">{{ number_format($fee->amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ number_format($fee->paid_amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($fee->status) }}</td>
                                    <td class="px-4 py-3">
                                        {{ $fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('Y-m-d') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-400 text-sm">
                                        No records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>