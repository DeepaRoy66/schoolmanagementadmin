<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fee Rates</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-indigo-50 text-indigo-700 font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182 1.106-.879 2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Fee Rates
                </span>
                <span class="text-gray-400">&raquo;</span>
                <span class="text-gray-500 text-sm">List of all fee rates</span>
            </div>

            @if (session('status'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-md overflow-hidden border-t-4 border-indigo-600">

                {{-- Search + Add button --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-200">
                    <form action="{{ route('school-admin.fee-rates.index') }}" method="GET" class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Search:</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by fee name or class..."
                               class="border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-700 placeholder-gray-400 outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 w-64">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('school-admin.fee-rates.index') }}"
                               class="text-sm text-gray-500 hover:text-gray-700 transition">
                                Clear
                            </a>
                        @endif
                    </form>

                    <a href="{{ route('school-admin.fee-rates.create') }}"
                       class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Fee Rate
                    </a>
                </div>

                <p class="px-6 pt-4 text-sm text-gray-500">
                    Total fee rates: {{ $feeRates->total() }}
                </p>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-600 bg-indigo-50/40">
                                <th class="py-3 px-6 font-semibold">Fee Name</th>
                                <th class="py-3 px-6 font-semibold">Class</th>
                                <th class="py-3 px-6 font-semibold">Billing Period</th>
                                <th class="py-3 px-6 font-semibold text-right">Amount</th>
                                <th class="py-3 px-6 font-semibold text-center">Active</th>
                                <th class="py-3 px-6 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($feeRates as $rate)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 font-medium text-gray-900">{{ $rate->feeName->name }}</td>
                                    <td class="py-4 px-6 text-gray-700">{{ $rate->schoolClass->name }}</td>
                                    <td class="py-4 px-6 text-gray-700">{{ $rate->billingPeriod->name ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-right text-gray-900 font-medium">{{ number_format($rate->amount, 2) }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if ($rate->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Yes
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-500 rounded-md text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('school-admin.fee-rates.edit', $rate) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-semibold
                                                      bg-amber-500 text-white hover:bg-amber-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('school-admin.fee-rates.destroy', $rate) }}" method="POST"
                                                  onsubmit="return confirm('Delete this fee rate?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-semibold
                                                               bg-red-600 text-white hover:bg-red-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center text-gray-500 text-sm">
                                        @if (request('search'))
                                            No fee rates found for "{{ request('search') }}".
                                        @else
                                            No fee rates found.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($feeRates->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $feeRates->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>