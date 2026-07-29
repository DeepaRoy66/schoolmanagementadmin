<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Billing Period: {{ $period->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('school-admin.billing-periods.update', $period) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Billing Period Name</label>
                        <input type="text" name="name" value="{{ old('name', $period->name) }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                        <input type="text" name="code" value="{{ old('code', $period->code) }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('code')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hierarchy</label>
                        <input type="number" name="hierarchy" value="{{ old('hierarchy', $period->hierarchy) }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('hierarchy')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" step="0.01" name="quantity" value="{{ old('quantity', $period->quantity) }}"
                               class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea name="remarks" rows="3" class="w-full border-gray-300 rounded-lg">{{ old('remarks', $period->remarks) }}</textarea>
                    </div>

                    <div class="mb-6 flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $period->is_active) ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="is_active" class="text-sm text-gray-700">Is Active?</label>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Update
                        </button>
                        <a href="{{ route('school-admin.billing-periods.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>