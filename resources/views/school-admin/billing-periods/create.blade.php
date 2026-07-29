<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Billing Period</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 p-4 bg-blue-50 text-blue-700 text-xs rounded-lg">
                    <p class="font-semibold mb-1">Tips</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        <li>Hierarchy should be unique for each period (used for ordering, e.g. 0, 1, 2...).</li>
                        <li>Quantity defaults to 1.00 if left blank.</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('school-admin.billing-periods.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Billing Period Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded-lg" placeholder="e.g. Baisakh" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                        <input type="text" name="code" value="{{ old('code') }}"
                               class="w-full border-gray-300 rounded-lg" placeholder="e.g. 1" required>
                        @error('code')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hierarchy</label>
                        <input type="number" name="hierarchy" value="{{ old('hierarchy') }}"
                               class="w-full border-gray-300 rounded-lg" placeholder="e.g. 0" required>
                        @error('hierarchy')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" step="0.01" name="quantity" value="{{ old('quantity', '1.00') }}"
                               class="w-full border-gray-300 rounded-lg">
                        @error('quantity')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea name="remarks" rows="3" class="w-full border-gray-300 rounded-lg">{{ old('remarks') }}</textarea>
                    </div>

                    <div class="mb-6 flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="is_active" class="text-sm text-gray-700">Is Active?</label>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Save
                        </button>
                        <a href="{{ route('school-admin.billing-periods.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>