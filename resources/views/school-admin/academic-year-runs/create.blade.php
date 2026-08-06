<x-app-layout>
<div class="px-6 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-700">
            Academic Year Run
            <span class="text-slate-400 font-normal text-base">&raquo; Add / Edit Academic Year Run</span>
        </h1>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2.5">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2.5">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm px-6 py-6">

        <form method="POST" action="{{ route('school-admin.academic-year-runs.store') }}">
            @csrf

            <div class="mb-6 max-w-xs">
                <label class="block text-sm font-medium text-slate-600 mb-2">Academic Year</label>
                <select name="academic_year_id" required
                        class="w-full border border-slate-300 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                    <option value="">-- Select --</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-md transition-colors">
                    Save
                </button>
                <a href="{{ route('school-admin.academic-year-runs.index') }}"
                   class="inline-flex items-center gap-1.5 bg-slate-400 hover:bg-slate-500 text-white text-sm font-medium px-5 py-2.5 rounded-md transition-colors">
                    Cancel
                </a>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-md">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-left text-slate-600">
                            <th class="py-3 px-4 font-semibold w-10"></th>
                            <th class="py-3 px-4 font-semibold">Program Offered</th>
                            <th class="py-3 px-4 font-semibold">Start Date</th>
                            <th class="py-3 px-4 font-semibold">End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-b border-slate-100">
                                <td class="py-3 px-4">
                                    <input type="checkbox" name="class_ids[]" value="{{ $class->id }}"
                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-3 px-4 text-slate-700">{{ $class->name }}</td>
                                <td class="py-3 px-4">
                                    <input type="date" name="start_date[{{ $class->id }}]"
                                           class="w-full border border-slate-300 rounded-md px-2 py-2 text-sm">
                                </td>
                                <td class="py-3 px-4">
                                    <input type="date" name="end_date[{{ $class->id }}]"
                                           class="w-full border border-slate-300 rounded-md px-2 py-2 text-sm">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">No classes found. Please add classes first.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
</x-app-layout>