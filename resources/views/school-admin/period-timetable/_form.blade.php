@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Period Name</label>
        <input type="text" name="name" value="{{ old('name', $period->name ?? '') }}"
               class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
               placeholder="e.g. 1st Period">
        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Period Code</label>
        <input type="text" name="code" value="{{ old('code', $period->code ?? '') }}"
               class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
               placeholder="e.g. p1">
        @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
        <input type="time" name="start_time" value="{{ old('start_time', isset($period) ? $period->start_time->format('H:i') : '') }}"
               class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
        @error('start_time') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">End Time</label>
        <input type="time" name="end_time" value="{{ old('end_time', isset($period) ? $period->end_time->format('H:i') : '') }}"
               class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
        @error('end_time') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2 pt-1">
        <input type="hidden" name="is_break" value="0">
        <input type="checkbox" id="is_break" name="is_break" value="1"
               @checked(old('is_break', $period->is_break ?? false))
               class="rounded border-slate-300 text-[#1e4ed8] focus:ring-[#1e4ed8]/30">
        <label for="is_break" class="text-sm text-slate-700">This is a break period</label>
    </div>

    <div class="flex items-center gap-2 pt-1">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
               @checked(old('is_active', $period->is_active ?? true))
               class="rounded border-slate-300 text-[#1e4ed8] focus:ring-[#1e4ed8]/30">
        <label for="is_active" class="text-sm text-slate-700">Active</label>
    </div>

</div>

<div class="flex items-center gap-3 mt-6">
    <button type="submit"
            class="rounded-md bg-[#1e4ed8] hover:bg-[#1e3a8a] text-white text-sm font-medium px-5 py-2.5 transition-colors">
        {{ isset($period) ? 'Update' : 'Save' }} Period
    </button>
    <button type="button" @click="showModal = false"
            class="text-sm text-slate-500 hover:text-slate-700">
        Cancel
    </button>
</div>