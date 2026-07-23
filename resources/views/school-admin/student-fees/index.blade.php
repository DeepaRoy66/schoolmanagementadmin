<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Fees
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">All Student Fees</h3>
                    <a href="{{ route('school-admin.student-fees.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#2dd4bf] text-white text-sm font-medium rounded-xl hover:bg-teal-500 transition-colors">
                        + Assign New Fee
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

                {{-- Filters --}}
                <form method="GET" action="{{ route('school-admin.student-fees.index') }}"
                      class="flex flex-wrap gap-3 mb-6">
                    <select name="student_id"
                            class="rounded-xl border-gray-300 text-sm focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                        <option value="">-- All Students --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status"
                            class="rounded-xl border-gray-300 text-sm focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                        <option value="">-- All Status --</option>
                        <option value="unpaid" @selected(request('status') == 'unpaid')>Unpaid</option>
                        <option value="partial" @selected(request('status') == 'partial')>Partial</option>
                        <option value="paid" @selected(request('status') == 'paid')>Paid</option>
                        <option value="overdue" @selected(request('status') == 'overdue')>Overdue</option>
                    </select>

                    <button type="submit"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                        Filter
                    </button>
                </form>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Fee Category</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Paid</th>
                                <th class="px-4 py-3">Due Date</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($studentFees as $fee)
                                <tr class="text-sm text-gray-700">
                                    <td class="px-4 py-3">{{ $fee->student->first_name }} {{ $fee->student->last_name }}</td>
                                    <td class="px-4 py-3">{{ $fee->feeCategory->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ number_format($fee->amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ number_format($fee->paid_amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($fee->due_date)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColors = [
                                                'paid' => 'bg-green-100 text-green-700',
                                                'partial' => 'bg-amber-100 text-amber-700',
                                                'overdue' => 'bg-red-100 text-red-700',
                                                'unpaid' => 'bg-gray-100 text-gray-700',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$fee->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('school-admin.student-fees.edit', $fee->id) }}"
                                           class="text-[#2dd4bf] hover:underline text-sm font-medium mr-3">Edit</a>

                                        <form action="{{ route('school-admin.student-fees.destroy', $fee->id) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Delete this fee record?');">
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
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-400 text-sm">
                                        No fee records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $studentFees->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>