<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#2dd4bf]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Student Fees
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 rounded-2xl bg-green-50 border border-green-100 text-green-700 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">All Student Fees</h3>
                        <p class="text-sm text-gray-400 mt-0.5">{{ $studentFees->total() ?? $studentFees->count() }} record(s) found</p>
                    </div>
                    <a href="{{ route('school-admin.student-fees.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2dd4bf] text-white text-sm font-medium rounded-xl hover:bg-teal-500 active:bg-teal-600 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14"></path>
                        </svg>
                        Assign New Fee
                    </a>
                </div>

                {{-- Filters --}}
                <form method="GET" action="{{ route('school-admin.student-fees.index') }}"
                      class="flex flex-wrap items-center gap-3 mb-6 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Student</label>
                        <select name="student_id"
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                            <option value="">-- All Students --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select name="status"
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                            <option value="">-- All Status --</option>
                            <option value="unpaid" @selected(request('status') == 'unpaid')>Unpaid</option>
                            <option value="partial" @selected(request('status') == 'partial')>Partial</option>
                            <option value="paid" @selected(request('status') == 'paid')>Paid</option>
                            <option value="overdue" @selected(request('status') == 'overdue')>Overdue</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 self-end">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Filter
                        </button>
                        @if(request('student_id') || request('status'))
                            <a href="{{ route('school-admin.student-fees.index') }}"
                               class="text-sm text-gray-500 hover:text-gray-700 hover:underline px-2">Clear</a>
                        @endif
                    </div>
                </form>

                {{-- Table --}}
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Fee Category</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Paid</th>
                                <th class="px-4 py-3">Due Date</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($studentFees as $fee)
                                @php
                                    $due = $fee->amount - $fee->paid_amount;
                                    $statusColors = [
                                        'paid' => 'bg-green-100 text-green-700',
                                        'partial' => 'bg-amber-100 text-amber-700',
                                        'overdue' => 'bg-red-100 text-red-700',
                                        'unpaid' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <tr class="text-sm text-gray-700 hover:bg-gray-50/60 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-800">
                                        {{ $fee->student->first_name }} {{ $fee->student->last_name }}
                                    </td>
                                    <td class="px-4 py-3">{{ $fee->feeCategory->name ?? '-' }}</td>
                                    <td class="px-4 py-3">Rs. {{ number_format($fee->amount, 2) }}</td>
                                    <td class="px-4 py-3">
                                        Rs. {{ number_format($fee->paid_amount, 2) }}
                                        @if($due > 0)
                                            <span class="block text-xs text-gray-400">Due: Rs. {{ number_format($due, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($fee->due_date)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$fee->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('school-admin.student-fees.edit', $fee->id) }}"
                                               class="text-[#0f9d8f] hover:underline text-sm font-medium">Edit</a>

                                            <form action="{{ route('school-admin.student-fees.destroy', $fee->id) }}"
                                                  method="POST" class="inline"
                                                  onsubmit="return confirm('Delete this fee record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline text-sm font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                                <line x1="2" y1="10" x2="22" y2="10"></line>
                                            </svg>
                                            No fee records found.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $studentFees->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>