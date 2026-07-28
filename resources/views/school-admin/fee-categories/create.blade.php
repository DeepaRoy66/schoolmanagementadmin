<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fee Categories
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('school-admin.fee-categories.index') }}"
                   class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
                    &larr; Back to Fee Categories
                </a>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h1 class="text-lg font-bold text-gray-900 mb-1">Add Fee Category</h1>
                <p class="text-sm text-gray-500 mb-6">Create a new fee type for your school</p>

                <form action="{{ route('school-admin.fee-categories.store') }}" method="POST">
                    @csrf

                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Tuition Fee"
                           class="w-full mb-1 rounded-md border-gray-300 text-sm focus:ring-teal-500 focus:border-teal-500" required autofocus>
                    @error('name')
                        <p class="text-red-600 text-xs mb-3">{{ $message }}</p>
                    @enderror
                    @if(!$errors->has('name'))
                        <div class="mb-5"></div>
                    @endif

                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment schedule</label>
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <label class="flex items-center gap-2 px-4 py-3 rounded-md border border-gray-200 cursor-pointer has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition-colors">
                            <input type="radio" name="is_recurring" value="0"
                                   {{ ! old('is_recurring') ? 'checked' : '' }}
                                   class="text-teal-600 focus:ring-teal-500">
                            <span class="text-sm text-gray-700">Pay once</span>
                        </label>
                        <label class="flex items-center gap-2 px-4 py-3 rounded-md border border-gray-200 cursor-pointer has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition-colors">
                            <input type="radio" name="is_recurring" value="1"
                                   {{ old('is_recurring') ? 'checked' : '' }}
                                   class="text-teal-600 focus:ring-teal-500">
                            <span class="text-sm text-gray-700">Repeats</span>
                        </label>
                    </div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">Repeat every</label>
                    <select name="recurring_interval"
                            class="w-full mb-1 rounded-md border-gray-300 text-sm focus:ring-teal-500 focus:border-teal-500">
                        <option value="">—</option>
                        <option value="monthly" {{ old('recurring_interval') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('recurring_interval') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    @error('recurring_interval')
                        <p class="text-red-600 text-xs mb-3">{{ $message }}</p>
                    @enderror
                    @if(!$errors->has('recurring_interval'))
                        <div class="mb-6"></div>
                    @endif

                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <a href="{{ route('school-admin.fee-categories.index') }}"
                           class="px-4 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 transition-colors mt-4">Cancel</a>
                        <button type="submit"
                                class="px-4 py-2 rounded-md text-sm bg-teal-600 text-white font-medium hover:bg-teal-700 transition-colors mt-4">Save Category</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>