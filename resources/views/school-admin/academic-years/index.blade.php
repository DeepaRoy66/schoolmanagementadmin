{{-- resources/views/school-admin/academic-years/index.blade.php --}}
<x-app-layout>
<div class="px-6 py-6">

    {{-- Breadcrumb --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-700">
            Academic Year
            <span class="text-slate-400 font-normal text-base">&raquo; List of all Academic Year</span>
        </h1>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm">

        {{-- Search --}}
        <form method="GET" action="{{ route('school-admin.academic-years.index') }}"
              class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
            <label class="text-sm font-medium text-slate-600 w-32">Academic Year:</label>
            <input type="text" name="year" value="{{ request('year') }}" placeholder="Eg. 2071"
                   class="border border-slate-300 rounded-md px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">
                Search
            </button>
        </form>

        {{-- New button --}}
        <div class="flex justify-end px-6 pt-5">
            <a href="{{ route('school-admin.academic-years.create') }}"
               class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New
            </a>
        </div>

        {{-- Table --}}
        <div class="px-6 py-5 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-left text-slate-600">
                        <th class="py-3 pr-4 font-semibold">S.No.</th>
                        <th class="py-3 pr-4 font-semibold">Academic Year</th>
                        <th class="py-3 pr-4 font-semibold">Is Running?</th>
                        <th class="py-3 pr-4 font-semibold"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($academicYears as $index => $academicYear)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 pr-4 text-slate-700">
                                {{ $academicYears->firstItem() + $index }}
                            </td>
                            <td class="py-3 pr-4 text-slate-700">{{ $academicYear->year }}</td>
                            <td class="py-3 pr-4 text-slate-700">
                                {{ $academicYear->is_running ? 'True' : 'False' }}
                            </td>
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('school-admin.academic-years.edit', $academicYear) }}"
                                       class="inline-flex items-center gap-1 bg-amber-400 hover:bg-amber-500 text-[#0f1b2d] text-xs font-semibold px-3 py-1.5 rounded-md transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('school-admin.academic-years.destroy', $academicYear) }}"
                                          method="POST" onsubmit="return confirm('Delete this academic year?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded-md transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 3h6a1 1 0 011 1v2H8V4a1 1 0 011-1z" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">No academic years found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($academicYears->hasPages())
            <div class="px-6 pb-5">
                {{ $academicYears->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>