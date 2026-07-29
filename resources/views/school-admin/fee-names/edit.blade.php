<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Fee Name: {{ $feeName->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('school-admin.fee-names.update', $feeName) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fee Group</label>
                        <select name="fee_group_id" class="w-full border-gray-300 rounded-lg" required>
                            @foreach ($feeGroups as $group)
                                <option value="{{ $group->id }}" {{ old('fee_group_id', $feeName->fee_group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('fee_group_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fee Name</label>
                            <input type="text" name="name" value="{{ old('name', $feeName->name) }}"
                                   class="w-full border-gray-300 rounded-lg" required>
                            @error('name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fee Code</label>
                            <input type="text" name="code" value="{{ old('code', $feeName->code) }}"
                                   class="w-full border-gray-300 rounded-lg" required>
                            @error('code')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fee Type</label>
                        <select name="fee_type" class="w-full border-gray-300 rounded-lg" required>
                            <option value="">-- Select --</option>
                            <option value="compulsory_regular" {{ old('fee_type', $feeName->fee_type) == 'compulsory_regular' ? 'selected' : '' }}>CRF - Compulsory/Regular</option>
                            <option value="extra_miscellaneous" {{ old('fee_type', $feeName->fee_type) == 'extra_miscellaneous' ? 'selected' : '' }}>EMF - Extra/Misc</option>
                            <option value="optional" {{ old('fee_type', $feeName->fee_type) == 'optional' ? 'selected' : '' }}>OF - Optional</option>
                            @php
    $typeLabels = [
        'compulsory_regular' => 'CRF - Compulsory/Regular',
        'extra_miscellaneous' => 'EMF - Extra/Misc',
        'optional' => 'OF - Optional',
    ];
@endphp
{{ $typeLabels[$fee->fee_type] ?? $fee->fee_type }}
                        </select>
                    </div>

                    <div class="mb-3 flex items-center gap-2">
                        <input type="checkbox" name="is_taxable" id="is_taxable" value="1"
                               {{ old('is_taxable', $feeName->is_taxable) ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="is_taxable" class="text-sm text-gray-700">Is Taxable?</label>
                    </div>

                    <div class="mb-3 flex items-center gap-2">
                        <input type="checkbox" name="discount_applicable" id="discount_applicable" value="1"
                               {{ old('discount_applicable', $feeName->discount_applicable) ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="discount_applicable" class="text-sm text-gray-700">Discount can be applied</label>
                    </div>

                    <div class="mb-6 flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $feeName->is_active) ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="is_active" class="text-sm text-gray-700">Is Active?</label>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Update
                        </button>
                        <a href="{{ route('school-admin.fee-names.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>