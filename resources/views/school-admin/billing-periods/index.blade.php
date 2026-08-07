<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Billing Periods
            </span>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">List all billing periods</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="flex items-center gap-2 border border-emerald-200 bg-emerald-50 text-emerald-700 rounded-md px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                {{-- Search + Add --}}
                <div class="flex flex-wrap items-center gap-3 px-6 py-5 border-b border-slate-100">
                    <label class="text-sm text-slate-600 font-medium">Search:</label>
                    <form action="{{ route('school-admin.billing-periods.index') }}" method="GET" class="flex items-center gap-2 flex-1 min-w-[260px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by period name or code..."
                               class="flex-1 max-w-sm px-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-400">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('school-admin.billing-periods.index') }}"
                               class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
                        @endif
                    </form>

                    <a href="{{ route('school-admin.billing-periods.create') }}"
                       class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Billing Period
                    </a>
                </div>

                <div class="flex items-center justify-between px-6 py-3 bg-slate-50/40 border-b border-slate-100">
                    <p class="text-sm text-slate-500">Total periods: <span class="font-medium text-slate-700">{{ $periods->count() }}</span></p>
                    @if (request('search'))
                        <span class="inline-flex items-center bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full">
                            Showing results for "{{ request('search') }}"
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse border border-slate-200">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600">
                                <th class="py-3 px-4 font-semibold border border-slate-200">Period Name</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Code</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Hierarchy</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Quantity</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200">Status</th>
                                <th class="py-3 px-4 font-semibold border border-slate-200 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($periods as $period)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-4 border border-slate-200 font-medium text-slate-900">{{ $period->name }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $period->code }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ $period->hierarchy }}</td>
                                    <td class="py-3 px-4 border border-slate-200 text-slate-600">{{ number_format($period->quantity, 2) }}</td>
                                    <td class="py-3 px-4 border border-slate-200">
                                        @if ($period->is_active)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Active</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs font-semibold">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 border border-slate-200">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('school-admin.billing-periods.edit', $period) }}"
                                               class="inline-flex items-center gap-1 bg-amber-500 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-amber-600 transition-colors shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('school-admin.billing-periods.destroy', $period) }}" method="POST"
                                                  onsubmit="return confirm('Yo billing period delete garne?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-red-700 transition-colors shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center border border-slate-200">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                            </svg>
                                            @if (request('search'))
                                                <p class="text-slate-500 text-sm">No billing periods match "{{ request('search') }}".</p>
                                                <a href="{{ route('school-admin.billing-periods.index') }}" class="text-blue-600 text-sm font-medium hover:underline">Clear search</a>
                                            @else
                                                <p class="text-slate-500 text-sm">None of the billing periods found.</p>
                                            @endif
                                        </div>
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