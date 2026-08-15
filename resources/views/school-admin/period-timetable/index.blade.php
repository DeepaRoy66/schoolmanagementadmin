<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Period Timetable</h2>
    </x-slot>

    <div class="p-6" x-data="{ showModal: false }" @keydown.escape.window="showModal = false">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-slate-800">
                Period Timetable
            </h1>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                Please fix the errors below and try again.
            </div>
        @endif

        {{-- Tabs --}}
        <div class="border-b border-slate-200 mb-6">
            <nav class="-mb-px flex gap-6 text-sm font-medium">
                <a href="{{ route('school-admin.period-timetable.index') }}"
                   class="py-3 border-b-2 {{ $activeTab === 'period-info' ? 'border-[#1e4ed8] text-[#1e4ed8]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    Period Info
                </a>
                <span class="py-3 border-b-2 border-transparent text-slate-300 cursor-not-allowed" title="Coming soon">
                    Teacher Subject Allocation
                </span>
                <span class="py-3 border-b-2 border-transparent text-slate-300 cursor-not-allowed" title="Coming soon">
                    Teachers Subject Period Association
                </span>
            </nav>
        </div>

        {{-- Period Info panel --}}
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-800">Period Info</h2>
                <button type="button" @click="showModal = true"
                        class="inline-flex items-center gap-1.5 rounded-md bg-[#1e4ed8] hover:bg-[#1e3a8a] text-white text-sm font-medium px-4 py-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    New
                </button>
            </div>
            {{-- Tabs --}}
<div class="border-b border-slate-200 mb-6">
    <nav class="-mb-px flex gap-6 text-sm font-medium">
        <a href="{{ route('school-admin.period-timetable.index') }}"
           class="py-3 border-b-2 {{ $activeTab === 'period-info' ? 'border-[#1e4ed8] text-[#1e4ed8]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Period Info
        </a>
        <a href="{{ route('school-admin.subject-allocations.index') }}"
           class="py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700">
            Teacher Subject Allocation
        </a>
        <a href="{{ route('school-admin.class-teacher.form') }}"
           class="py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700">
            Class Teacher
        </a>
    </nav>
</div>

            <div class="flex items-center justify-end px-5 py-3 border-b border-slate-100">
                <form method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search..."
                           class="text-sm border border-slate-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
                    <button type="submit"
                            class="text-sm px-3 py-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600">
                        Search
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b border-slate-100">
                            <th class="px-5 py-3 font-medium">SN</th>
                            <th class="px-5 py-3 font-medium">Period Name</th>
                            <th class="px-5 py-3 font-medium">Period Code</th>
                            <th class="px-5 py-3 font-medium">Start Time</th>
                            <th class="px-5 py-3 font-medium">End Time</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Break</th>
                            <th class="px-5 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($periods as $i => $period)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-500">{{ $periods->firstItem() + $i }}</td>
                                <td class="px-5 py-3 text-slate-700">{{ $period->name }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $period->code }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $period->start_time->format('h:i A') }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $period->end_time->format('h:i A') }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $period->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $period->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($period->is_break)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            Break
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('school-admin.period-timetable.edit', $period) }}"
                                           class="p-1.5 rounded-md bg-amber-100 hover:bg-amber-200 text-amber-700">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('school-admin.period-timetable.destroy', $period) }}" method="POST"
                                              onsubmit="return confirm('Delete this period?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-1.5 rounded-md bg-red-100 hover:bg-red-200 text-red-700">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-10 text-center text-slate-400">
                                    No periods added yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-100">
                {{ $periods->links() }}
            </div>
        </div>

        {{-- New Period Modal --}}
        <div x-show="showModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display: none;">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/40" @click="showModal = false"></div>

            {{-- Modal panel --}}
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">Period Info</h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('school-admin.period-timetable.store') }}" method="POST" class="px-6 py-5">
                    @include('school-admin.period-timetable._form')
                </form>
            </div>
        </div>

    </div>
</x-app-layout>