<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Event</h2>
    </x-slot>

    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-slate-800">
                New Event
            </h1>
            <a href="{{ route('school-admin.calendar-events.index') }}"
               class="text-sm text-slate-500 hover:text-slate-700">
                &larr; Back to list
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                Please fix the errors below and try again.
            </div>
        @endif

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 max-w-xl">
            <form action="{{ route('school-admin.calendar-events.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           placeholder="e.g. Dashain, Tihar, Gai Jatra"
                           class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
                           required>
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                    <select id="type" name="type"
                            onchange="document.getElementById('custom_type_wrapper').classList.toggle('hidden', this.value !== 'other')"
                            class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
                            required>
                        <option value="">Select type</option>
                        @foreach (['holiday' => 'Holiday', 'exam' => 'Exam', 'event' => 'Event', 'meeting' => 'Meeting', 'other' => 'Other'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="custom_type_wrapper" class="mb-4 {{ old('type') === 'other' ? '' : 'hidden' }}">
                    <label for="custom_type" class="block text-sm font-medium text-slate-700 mb-1">Custom Type Name</label>
                    <input type="text" id="custom_type" name="custom_type" value="{{ old('custom_type') }}"
                           placeholder="e.g. Sports Day, Picnic, Parent Meeting"
                           class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
                    @error('custom_type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 grid grid-cols-2 gap-3">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
                               required>
                        @error('start_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">End Date <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
                        @error('end_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description <span class="text-slate-400 font-normal">(optional)</span></label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 flex items-center gap-2">
                    <input type="checkbox" id="is_recurring" name="is_recurring" value="1" @checked(old('is_recurring'))
                           class="rounded border-slate-300 text-[#1e4ed8] focus:ring-[#1e4ed8]/30">
                    <label for="is_recurring" class="text-sm text-slate-700">Repeats every year (e.g. Dashain, Tihar)</label>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-md bg-[#1e4ed8] hover:bg-[#1e3a8a] text-white text-sm font-medium px-4 py-2 transition-colors">
                        Save
                    </button>
                    <a href="{{ route('school-admin.calendar-events.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium px-4 py-2 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>