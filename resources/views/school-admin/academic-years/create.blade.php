{{-- resources/views/school-admin/academic-years/create.blade.php --}}
<x-app-layout>
<div class="px-6 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-700">
            Academic Year
            <span class="text-slate-400 font-normal text-base">&raquo; Add/Edit Academic Year</span>
        </h1>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm px-6 py-6 max-w-xl">

        <form method="POST"
              action="{{ $academicYear->exists
                    ? route('school-admin.academic-years.update', $academicYear)
                    : route('school-admin.academic-years.store') }}">
            @csrf
            @if ($academicYear->exists)
                @method('PUT')
            @endif

            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-600 mb-2">Academic Year</label>
                <input type="text" name="year" value="{{ old('year', $academicYear->year) }}"
                       class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                @error('year')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 flex items-center gap-2">
                <input type="checkbox" id="is_running" name="is_running" value="1"
                       {{ old('is_running', $academicYear->is_running) ? 'checked' : '' }}
                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="is_running" class="text-sm text-slate-600">Is Running</label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Save
                </button>
                <a href="{{ route('school-admin.academic-years.index') }}"
                   class="inline-flex items-center gap-1.5 bg-slate-400 hover:bg-slate-500 text-white text-sm font-medium px-5 py-2.5 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>