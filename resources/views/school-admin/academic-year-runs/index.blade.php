<x-app-layout>
<div class="px-6 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-700">
            Academic Year Run
            <span class="text-slate-400 font-normal text-base">&raquo; List of all Academic Year Run</span>
        </h1>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm">

        <form method="GET" action="{{ route('school-admin.academic-year-runs.index') }}"
              class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
            <label class="text-sm font-medium text-slate-600 w-32">Academic Year:</label>
            <input type="text" name="year" value="{{ request('year') }}" placeholder="Eg. 2082"
                   class="border border-slate-300 rounded-md px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">
                Search
            </button>
        </form>

        <div class="flex justify-end px-6 pt-5">
            <a href="{{ route('school-admin.academic-year-runs.create') }}"
               class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New
            </a>
        </div>

        <div class="px-6 py-5 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-left text-slate-600">
                        <th class="py-3 pr-4 font-semibold">S.No.</th>
                        <th class="py-3 pr-4 font-semibold">Program Offered</th>
                        <th class="py-3 pr-4 font-semibold">Start Date</th>
                        <th class="py-3 pr-4 font-semibold">End Date</th>
                        <th class="py-3 pr-4 font-semibold">Admission Term</th>
                        <th class="py-3 pr-4 font-semibold"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($academicYearRuns as $index => $run)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 pr-4 text-slate-700">
                                {{ $academicYearRuns->firstItem() + $index }}
                            </td>
                            <td class="py-3 pr-4 text-slate-700">{{ $run->academicYear->year ?? '-' }}</td>
                            <td class="py-3 pr-4 text-slate-700">{{ $run->schoolClass->name ?? '-' }}</td>
                            <td class="py-3 pr-4 text-slate-700">{{ $run->start_date }}</td>
                            <td class="py-3 pr-4 text-slate-700">{{ $run->end_date }}</td>
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('school-admin.academic-year-runs.edit', $run) }}"
                                       class="inline-flex items-center gap-1 bg-amber-400 hover:bg-amber-500 text-[#0f1b2d] text-xs font-semibold px-3 py-1.5 rounded-md transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('school-admin.academic-year-runs.destroy', $run) }}"
                                          method="POST" onsubmit="return confirm('Delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded-md transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($academicYearRuns->hasPages())
            <div class="px-6 pb-5">
                {{ $academicYearRuns->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>