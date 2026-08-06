<x-app-layout>
<div class="px-6 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-700">
            Academic Year Run
            <span class="text-slate-400 font-normal text-base">&raquo; Add / Edit Academic Year Run</span>
        </h1>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm px-6 py-6 max-w-xl">

        <form method="POST" action="{{ route('school-admin.academic-year-runs.update', $academicYearRun) }}">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-600 mb-2">Academic Year</label>
                <select name="academic_year_id" required
                        class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $academicYearRun->academic_year_id == $year->id ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-600 mb-2">Program Offered</label>
                <select name="class_id" required
                        class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" {{ $academicYearRun->class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-600 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ $academicYearRun->start_date }}"
                       class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-sm">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-600 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ $academicYearRun->end_date }}"
                       class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-sm">
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-md transition-colors">
                    Save
                </button>
                <a href="{{ route('school-admin.academic-year-runs.index') }}"
                   class="inline-flex items-center gap-1.5 bg-slate-400 hover:bg-slate-500 text-white text-sm font-medium px-5 py-2.5 rounded-md transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>