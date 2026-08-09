<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Feedback
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="flex items-center gap-2 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-md text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-md border border-gray-100 overflow-hidden">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-md bg-indigo-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.4-.101-.816-.463-1.017C3.056 16.658 1.5 14.502 1.5 12 1.5 7.444 5.53 3.75 10.5 3.75S21 7.444 21 12z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Submitted Feedback</p>
                            <p class="text-xs text-gray-400">{{ $feedbacks->total() }} {{ Str::plural('item', $feedbacks->total()) }} from students and teachers across all schools</p>
                        </div>
                    </div>
                </div>

                {{-- Status Tabs --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/40">
                    @php
                        $currentStatus = request('status');
                        $tabs = [
                            ['key' => null,       'label' => 'All',       'count' => $counts['all'],      'dot' => null],
                            ['key' => 'pending',  'label' => 'Pending',   'count' => $counts['pending'],  'dot' => 'bg-amber-500'],
                            ['key' => 'reviewed', 'label' => 'Reviewed',  'count' => $counts['reviewed'], 'dot' => 'bg-indigo-500'],
                            ['key' => 'resolved', 'label' => 'Resolved', 'count' => $counts['resolved'], 'dot' => 'bg-emerald-500'],
                        ];
                    @endphp

                    <div class="flex items-center gap-2 overflow-x-auto">
                        @foreach ($tabs as $tab)
                            @php $active = $currentStatus === $tab['key']; @endphp
                            <a href="{{ route('admin.feedback.index', array_filter(array_merge(request()->except('status', 'page'), ['status' => $tab['key']]))) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap
                                      border transition-all duration-150
                                      {{ $active
                                            ? 'bg-gray-900 border-gray-900 text-white shadow-sm'
                                            : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50' }}">
                                @if ($tab['dot'])
                                    <span class="w-2 h-2 rounded-full {{ $tab['dot'] }} {{ $active ? 'ring-2 ring-white/30' : '' }}"></span>
                                @endif
                                {{ $tab['label'] }}
                                <span class="inline-flex items-center justify-center min-w-[1.375rem] h-5 px-1.5 rounded-md text-xs font-semibold
                                             {{ $active ? 'bg-white/15 text-white' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $tab['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Filters --}}
                <form method="GET" action="{{ route('admin.feedback.index') }}"
                      class="flex flex-wrap items-end gap-3 px-6 py-4 border-b border-gray-100 bg-gray-50/40">

                    @if ($currentStatus)
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                    @endif

                    <div class="flex flex-col gap-1 flex-1 min-w-[220px]">
                        <label class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">Search</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search subject, message, or sender..."
                                   class="w-full text-sm rounded-md border-gray-300 pl-9 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">School</label>
                        <select name="school_id" onchange="this.form.submit()"
                                class="text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 min-w-[180px]">
                            <option value="">All Schools</option>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">Role</label>
                        <select name="role" onchange="this.form.submit()"
                                class="text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 min-w-[140px]">
                            <option value="">All Roles</option>
                            <option value="student" @selected(request('role') === 'student')>Student</option>
                            <option value="teacher" @selected(request('role') === 'teacher')>Teacher</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition shrink-0">
                        Search
                    </button>

                    @if (request('school_id') || request('role') || request('search'))
                        <a href="{{ route('admin.feedback.index', array_filter(['status' => $currentStatus])) }}"
                           class="text-sm text-gray-500 hover:text-gray-700 pb-2">
                            Clear filters
                        </a>
                    @endif
                </form>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50/60 text-gray-500 text-xs uppercase tracking-wide">
                                <th class="py-3 px-6 font-medium">School</th>
                                <th class="py-3 px-6 font-medium">From</th>
                                <th class="py-3 px-6 font-medium">Subject</th>
                                <th class="py-3 px-6 font-medium">Message</th>
                                <th class="py-3 px-6 font-medium">Status</th>
                                <th class="py-3 px-6 font-medium">Submitted</th>
                                <th class="py-3 px-6 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($feedbacks as $feedback)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-4 px-6 text-gray-700">
                                        {{ $feedback->school->name ?? '—' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-medium text-gray-900">{{ $feedback->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-400 capitalize">{{ $feedback->submitted_by_role }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-700">
                                        {{ $feedback->subject ?? '—' }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 max-w-xs">
                                        <span class="line-clamp-2">{{ $feedback->message }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($feedback->status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 rounded-md text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Pending
                                            </span>
                                        @elseif ($feedback->status === 'reviewed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                Reviewed
                                            </span>
                                        @elseif ($feedback->status === 'resolved')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Resolved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-50 text-gray-600 rounded-md text-xs font-semibold">
                                                {{ ucfirst($feedback->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-gray-500 whitespace-nowrap">
                                        {{ $feedback->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.feedback.update-status', $feedback) }}">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()"
                                                        class="text-xs rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="pending" @selected($feedback->status === 'pending')>Pending</option>
                                                    <option value="reviewed" @selected($feedback->status === 'reviewed')>Reviewed</option>
                                                    <option value="resolved" @selected($feedback->status === 'resolved')>Resolved</option>
                                                </select>
                                            </form>

                                            <form method="POST" action="{{ route('admin.feedback.destroy', $feedback) }}"
                                                  onsubmit="return confirm('Delete this feedback? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold
                                                               bg-rose-500 text-white
                                                               hover:bg-rose-600 transition shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
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
                                    <td colspan="7" class="py-16">
                                        <div class="flex flex-col items-center gap-2 text-center">
                                            <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.4-.101-.816-.463-1.017C3.056 16.658 1.5 14.502 1.5 12 1.5 7.444 5.53 3.75 10.5 3.75S21 7.444 21 12z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">No feedback found for the selected filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($feedbacks->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $feedbacks->links() }}
                    </div>
                @endif

            </div>
</div>
    </div>
</x-app-layout>