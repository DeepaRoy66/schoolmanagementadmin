<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Individual Invoice Statement</h2>
    </x-slot>

    {{-- Print CSS: hide everything except the invoice card(s). Adjust selectors
         (aside/nav/header) if your app-layout uses different tags/classes. --}}
    <style>
        @media print {
            aside, nav, header, .no-print { display: none !important; }
            body, html { background: #fff !important; }
            .invoice-card { box-shadow: none !important; border: none !important; margin: 0 !important; }
            main, .max-w-5xl { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        }
    </style>

    <div class="py-8 print:py-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 print:px-0 print:max-w-none">

            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-2xl font-semibold text-blue-500">Individual Invoice Statement</h3>
                    <p class="text-gray-500 text-sm mt-1">&raquo; Displays individual student's fee invoice.</p>
                </div>
                <div class="flex flex-col items-end gap-2 no-print">
                    <a href="#" class="text-sm text-blue-500 hover:underline inline-flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Read more
                    </a>
                    <button onclick="window.print()"
                            class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
                        </svg>
                        Generate
                    </button>
                </div>
            </div>

            <hr class="border-gray-200 mb-8">

            @forelse ($fees as $group)
                @php
                    $first = $group->first();
                    $school = $first->school ?? null;
                    $student = $first->student;
                    $grandTotal = $group->sum('amount');
                @endphp

                <div class="invoice-card mb-10 print:mb-0 print:break-after-page">

                    {{-- Centered company header --}}
                    <div class="text-center mb-6">
                        <h3 class="text-xl text-gray-800">{{ $school->name ?? 'School Name' }}</h3>
                        <p class="text-gray-700">{{ $school->address ?? '' }}</p>
                        <p class="text-gray-700">{{ $school->phone ?? '' }}</p>
                        <p class="text-gray-700">{{ $school->email ?? '' }}</p>
                    </div>

                    {{-- Student info row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-4">
                        <div>
                            <p><strong>Student Name:</strong> {{ strtoupper(trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))) }}</p>
                            <p><strong>Class:</strong> {{ $student->schoolClass->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p><strong>Student ID:</strong> {{ $student->student_id ?? '-' }}</p>
                            <p><strong>Section:</strong> {{ $student->section->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p><strong>CRN:</strong> {{ $student->id ?? '-' }}</p>
                            <p><strong>Billing Period:</strong> {{ $first->billingPeriod->name ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Fee table --}}
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-left">
                                <th class="px-3 py-2">S.No</th>
                                <th class="px-3 py-2">Invoice No</th>
                                <th class="px-3 py-2">Billed Date</th>
                                <th class="px-3 py-2">Due Date</th>
                                <th class="px-3 py-2">Fee Name</th>
                                <th class="px-3 py-2">Rate</th>
                                <th class="px-3 py-2">Qty</th>
                                <th class="px-3 py-2">Discount</th>
                                <th class="px-3 py-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($group as $i => $fee)
                                <tr class="border-b border-gray-100">
                                    <td class="px-3 py-2">{{ $i + 1 }}</td>
                                    <td class="px-3 py-2">{{ optional($first->invoice)->invoice_no ?? ('INV-' . str_pad($first->id, 6, '0', STR_PAD_LEFT)) }}</td>
                                    <td class="px-3 py-2">{{ $fee->billing_date }}</td>
                                    <td class="px-3 py-2">{{ $fee->due_date }}</td>
                                    <td class="px-3 py-2">{{ $fee->feeName->name ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ number_format($fee->amount, 2) }}</td>
                                    <td class="px-3 py-2">1</td>
                                    <td class="px-3 py-2">0</td>
                                    <td class="px-3 py-2">{{ number_format($fee->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 font-semibold text-gray-900">
                                <td colspan="8" class="px-3 py-2">Grand Total</td>
                                <td class="px-3 py-2">{{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @empty
                <div class="text-center text-gray-500 text-sm py-10">
                    No invoice records found for the selected item(s).
                </div>
            @endforelse

        </div>
    </div>

    @if (request()->boolean('print'))
        <script>
            window.addEventListener('load', () => setTimeout(() => window.print(), 300));
        </script>
    @endif
</x-app-layout>