<x-app-layout>
    <div class="p-6 max-w-6xl mx-auto">
        <div class="mb-6 flex items-baseline gap-2">
            <h1 class="text-2xl font-medium text-sky-600">List Student(s) For Fee Payment</h1>
            <span class="text-gray-400">&raquo;</span>
            <p class="text-gray-400 text-sm">Pay student's fees.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- FILTER FORM --}}
        <form method="GET" action="{{ route('school-admin.fee-payments.create') }}"
              class="mb-8 rounded-xl border border-gray-200 bg-white shadow-sm px-6 py-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Class</label>
                    <select name="class_id" class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">-- Select --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">Section</label>
                    <select name="section_id" class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">-- Select --</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">Student Name</label>
                    <input type="text" name="student_name" value="{{ request('student_name') }}"
                           placeholder="e.g. Aayush"
                           class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">Student ID</label>
                    <input type="text" name="student_number" value="{{ request('student_number') }}"
                           placeholder="Roll no."
                           class="w-full rounded-md border-gray-300 text-sm focus:border-sky-500 focus:ring-sky-500">
                </div>
            </div>

            <div class="mt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    Search
                </button>
            </div>
        </form>

        {{-- RESULTS --}}
        @if ($students->isNotEmpty())
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                            <tr>
                                <th class="p-3 text-left w-12">SN</th>
                                <th class="p-3 text-left">Student ID</th>
                                <th class="p-3 text-left">Student Name</th>
                                <th class="p-3 text-left">Class</th>
                                <th class="p-3 text-left">Section</th>
                                <th class="p-3 text-right">Total Fee</th>
                                <th class="p-3 text-right">Paid</th>
                                <th class="p-3 text-right">Due</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($students as $i => $student)
                                @php
                                    $total = $student->total_fee_amount ?? 0;
                                    $paid = $student->total_paid_amount ?? 0;
                                    $due = $total - $paid;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 text-gray-500">{{ $students->firstItem() + $i }}</td>
                                    <td class="p-3">{{ $student->roll_number }}</td>
                                    <td class="p-3 font-medium text-gray-800">{{ trim($student->first_name . ' ' . $student->last_name) }}</td>
                                    <td class="p-3">{{ $student->schoolClass->name ?? '-' }}</td>
                                    <td class="p-3">{{ $student->section->name ?? '-' }}</td>
                                    <td class="p-3 text-right text-gray-600">{{ number_format($total, 2) }}</td>
                                    <td class="p-3 text-right text-gray-600">{{ number_format($paid, 2) }}</td>
                                    <td class="p-3 text-right font-medium {{ $due > 0 ? 'text-red-600' : 'text-gray-500' }}">{{ number_format($due, 2) }}</td>
                                    <td class="p-3 text-center">
                                        @if ($due <= 0)
                                            <span class="inline-block rounded-full bg-green-100 text-green-700 text-xs px-2 py-0.5">Paid</span>
                                        @elseif ($paid > 0)
                                            <span class="inline-block rounded-full bg-amber-100 text-amber-700 text-xs px-2 py-0.5">Partial</span>
                                        @else
                                            <span class="inline-block rounded-full bg-red-100 text-red-700 text-xs px-2 py-0.5">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('school-admin.fee-payments.pay-form', $student->id) }}"
                                           class="inline-flex items-center gap-1 rounded-md bg-sky-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-sky-700 transition-colors">
                                            Pay Fee
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                    {{ $students->links() }}
                </div>
            </div>
        @elseif (request()->hasAny(['class_id', 'section_id', 'student_name', 'student_number']))
            <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-500 text-sm">
                No students matched this filter.
            </div>
        @endif
    </div>
</x-app-layout>