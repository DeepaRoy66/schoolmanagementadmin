<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Calendar Events</h2>
    </x-slot>

    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-slate-800">
                Calendar Events
            </h1>
            <a href="{{ route('school-admin.calendar-events.create') }}"
               class="inline-flex items-center gap-1.5 rounded-md bg-[#1e4ed8] hover:bg-[#1e3a8a] text-white text-sm font-medium px-4 py-2 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Event
            </a>
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

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm">

            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100">
                <form method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search title..."
                           class="text-sm border border-slate-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">

                    <select name="type"
                            class="text-sm border border-slate-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
                        <option value="">All Types</option>
                        @foreach (['holiday' => 'Holiday', 'exam' => 'Exam', 'event' => 'Event', 'meeting' => 'Meeting', 'other' => 'Other'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="text-sm px-3 py-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600">
                        Filter
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b border-slate-100">
                            <th class="px-5 py-3 font-medium">SN</th>
                            <th class="px-5 py-3 font-medium">Title</th>
                            <th class="px-5 py-3 font-medium">Type</th>
                            <th class="px-5 py-3 font-medium">Start Date</th>
                            <th class="px-5 py-3 font-medium">End Date</th>
                            <th class="px-5 py-3 font-medium">Recurring</th>
                            <th class="px-5 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $typeColors = [
                                'holiday' => 'bg-green-100 text-green-700',
                                'exam'    => 'bg-red-100 text-red-700',
                                'event'   => 'bg-blue-100 text-blue-700',
                                'meeting' => 'bg-amber-100 text-amber-700',
                                'other'   => 'bg-slate-100 text-slate-600',
                            ];
                        @endphp
                        @forelse ($events as $i => $event)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-500">{{ $events->firstItem() + $i }}</td>
                                <td class="px-5 py-3 text-slate-700">{{ $event->title }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $typeColors[$event->type] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $event->type === 'other' && $event->custom_type ? $event->custom_type : ucfirst($event->type) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-slate-500">
                                    {{ \Anuzpandey\LaravelNepaliDate\LaravelNepaliDate::from($event->start_date->format('Y-m-d'))->toNepaliDate(format: 'j F Y', locale: 'np') }}
                                </td>
                                <td class="px-5 py-3 text-slate-500">
                                    @if ($event->end_date)
                                        {{ \Anuzpandey\LaravelNepaliDate\LaravelNepaliDate::from($event->end_date->format('Y-m-d'))->toNepaliDate(format: 'j F Y', locale: 'np') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $event->is_recurring ? 'Yes' : 'No' }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('school-admin.calendar-events.edit', $event) }}"
                                           class="p-1.5 rounded-md bg-amber-100 hover:bg-amber-200 text-amber-700">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('school-admin.calendar-events.destroy', $event) }}" method="POST"
                                              onsubmit="return confirm('Delete this event?');">
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
                                <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                    No events added yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-100">
                {{ $events->links() }}
            </div>
        </div>

    </div>
</x-app-layout>