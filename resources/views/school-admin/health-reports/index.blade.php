<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Health Reports</h1>
                    <p class="text-sm text-gray-500 mt-1">Submissions from students, sorted by most recent.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $reports->total() }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs font-medium text-amber-500 uppercase tracking-wide">Pending</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $pendingCount ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs font-medium text-blue-500 uppercase tracking-wide">Reviewed</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $reviewedCount ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs font-medium text-green-500 uppercase tracking-wide">Resolved</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $resolvedCount ?? 0 }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <form method="GET" class="bg-white border border-gray-200 rounded-xl p-4 flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Search student</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Name..."
                           class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                        <option value="">All Statuses</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="reviewed" @selected(request('status') === 'reviewed')>Reviewed</option>
                        <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 transition-colors">
                        Filter
                    </button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('school-admin.health-reports.index') }}"
                           class="rounded-md border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm font-medium px-4 py-2 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            {{-- Table --}}
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Student</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Class</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Message</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Photo</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Date</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $report->student->full_name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $report->schoolClass->name ?? '—' }}</td>
                                <td class="px-6 py-4 max-w-xs truncate text-gray-600">{{ $report->message }}</td>
                                <td class="px-6 py-4">
                                    @if ($report->photo_path)
                                        <a href="{{ route('school-admin.health-reports.show', $report) }}"
                                           class="block w-11 h-11 rounded-lg overflow-hidden border border-gray-200 hover:ring-2 hover:ring-blue-400 transition">
                                            <img src="{{ Storage::url($report->photo_path) }}" alt="" class="w-full h-full object-cover">
                                        </a>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'reviewed' => 'bg-blue-100 text-blue-700',
                                            'resolved' => 'bg-green-100 text-green-700',
                                        ][$report->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide {{ $statusStyles }}">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $report->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        {{-- View --}}
                                        <a href="{{ route('school-admin.health-reports.show', $report) }}"
                                           class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            View
                                        </a>

                                        {{-- Status dropdown --}}
                                        <form method="POST" action="{{ route('school-admin.health-reports.update-status', $report) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                    class="rounded-md border-gray-300 text-xs focus:border-blue-500 focus:ring-blue-500/30">
                                                <option value="pending" @selected($report->status === 'pending')>Pending</option>
                                                <option value="reviewed" @selected($report->status === 'reviewed')>Reviewed</option>
                                                <option value="resolved" @selected($report->status === 'resolved')>Resolved</option>
                                            </select>
                                        </form>

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('school-admin.health-reports.destroy', $report) }}"
                                              onsubmit="return confirm('Delete this report?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-red-200 bg-white hover:bg-red-50 text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <p class="text-gray-400 text-sm">No health reports match your filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <div>
                {{ $reports->links() }}
            </div>

        </div>
    </div>
</x-app-layout>