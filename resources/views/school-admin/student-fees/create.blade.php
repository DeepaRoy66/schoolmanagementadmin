<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign New Fee
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-700 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('school-admin.student-fees.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                        <select name="student_id" required
                                class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                            <option value="">-- Select Student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fee Category</label>
                        <select name="fee_category_id" required
                                class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                            <option value="">-- Select Category --</option>
                            @foreach($feeCategories as $category)
                                <option value="{{ $category->id }}" @selected(old('fee_category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required
                               class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" required
                               class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                        <textarea name="notes" rows="3"
                                  class="w-full rounded-xl border-gray-300 focus:border-[#2dd4bf] focus:ring-[#2dd4bf]">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="px-5 py-2.5 bg-[#2dd4bf] text-white text-sm font-medium rounded-xl hover:bg-teal-500 transition-colors">
                            Assign Fee
                        </button>
                        <a href="{{ route('school-admin.student-fees.index') }}"
                           class="text-sm text-gray-600 hover:underline">Cancel</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>