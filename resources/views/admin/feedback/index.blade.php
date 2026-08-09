<x-app-layout>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Feedback</h1>
            <p class="text-sm text-slate-500 mt-0.5">Feedback submitted by students and teachers across all schools.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.feedback.index') }}"
          class="flex flex-wrap items-end gap-3 mb-5 bg-white border border-slate-200 rounded-lg p-4">

        <div class="flex flex-col gap-1">
            <label class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">School</label>
            <select name="school_id" onchange="this.form.submit()"
                    class="text-sm rounded-md border-slate-300 focus:border-[#1e4ed8] focus:ring-[#1e4ed8] min-w-[180px]">
                <option value="">All Schools</option>
                @foreach ($schools as $school)
                    <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Role</label>
            <select name="role" onchange="this.form.submit()"
                    class="text-sm rounded-md border-slate-300 focus:border-[#1e4ed8] focus:ring-[#1e4ed8] min-w-[140px]">
                <option value="">All Roles</option>
                <option value="student" @selected(request('role') === 'student')>Student</option>
                <option value="teacher" @selected(request('role') === 'teacher')>Teacher</option>
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Status</label>
            <select name="status" onchange="this.form.submit()"
                    class="text-sm rounded-md border-slate-300 focus:border-[#1e4ed8] focus:ring-[#1e4ed8] min-w-[140px]">
                <option value="">All Statuses</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="reviewed" @selected(request('status') === 'reviewed')>Reviewed</option>
                <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
            </select>
        </div>

        @if (request('school_id') || request('role') || request('status'))
            <a href="{{ route('admin.feedback.index') }}"
               class="text-sm text-slate-500 hover:text-slate-700 pb-1.5">
                Clear filters
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                    <th class="px-5 py-3">School</th>
                    <th class="px-5 py-3">From</th>
                    <th class="px-5 py-3">Subject</th>
                    <th class="px-5 py-3">Message</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Submitted</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($feedbacks as $feedback)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5 text-slate-700">
                            {{ $feedback->school->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-slate-800 font-medium">{{ $feedback->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-400 capitalize">{{ $feedback->submitted_by_role }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-slate-700">
                            {{ $feedback->subject ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-600 max-w-xs">
                            <span class="line-clamp-2">{{ $feedback->message }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $statusStyles = [
                                    'pending'  => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    'reviewed' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'resolved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                ];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $statusStyles[$feedback->status] ?? 'bg-slate-50 text-slate-600 ring-slate-500/20' }}">
                                {{ ucfirst($feedback->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">
                            {{ $feedback->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('admin.feedback.update-status', $feedback) }}" class="flex items-center gap-1.5">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="text-xs rounded-md border-slate-300 focus:border-[#1e4ed8] focus:ring-[#1e4ed8]">
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
                                            class="text-xs font-medium text-red-600 hover:text-red-700 px-2 py-1 rounded-md hover:bg-red-50 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400 text-sm">
                            No feedback found for the selected filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($feedbacks->hasPages())
        <div class="mt-5">
            {{ $feedbacks->links() }}
        </div>
    @endif

</div>

</x-app-layout>