<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Fee Name: {{ $feeName->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('school-admin.fee-names.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-indigo-50 text-indigo-700 font-semibold text-sm hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182 1.106-.879 2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Fee Names
                </a>
                <span class="text-gray-400">&raquo;</span>
                <span class="text-gray-500 text-sm">Edit "{{ $feeName->name }}"</span>
            </div>

            <div class="bg-white shadow rounded-md overflow-hidden border-t-4 border-indigo-600 p-6">

                <form method="POST" action="{{ route('school-admin.fee-names.update', $feeName) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Group</label>
                        <select name="fee_group_id"
                                class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Name</label>
                            <input type="text" name="name" value="{{ old('name', $feeName->name) }}"
                                   class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Code</label>
                            <input type="text" name="code" value="{{ old('code', $feeName->code) }}"
                                   class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('code')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Type</label>
                        <select name="fee_type"
                                class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Select --</option>
                            <option value="compulsory_regular" {{ old('fee_type', $feeName->fee_type) == 'compulsory_regular' ? 'selected' : '' }}>CRF - Compulsory/Regular</option>
                            <option value="extra_miscellaneous" {{ old('fee_type', $feeName->fee_type) == 'extra_miscellaneous' ? 'selected' : '' }}>EMF - Extra/Misc</option>
                            <option value="optional" {{ old('fee_type', $feeName->fee_type) == 'optional' ? 'selected' : '' }}>OF - Optional</option>
                        </select>
                        @error('fee_type')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 flex items-center gap-2">
                        <input type="checkbox" name="is_taxable" id="is_taxable" value="1"
                               {{ old('is_taxable', $feeName->is_taxable) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_taxable" class="text-sm text-gray-700">Is Taxable?</label>
                    </div>

                    <div class="mb-3 flex items-center gap-2">
                        <input type="checkbox" name="discount_applicable" id="discount_applicable" value="1"
                               {{ old('discount_applicable', $feeName->discount_applicable) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="discount_applicable" class="text-sm text-gray-700">Discount can be applied</label>
                    </div>

                    <div class="mb-6 flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $feeName->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_active" class="text-sm text-gray-700">Is Active?</label>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit"
                                class="bg-indigo-600 text-white px-5 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition">
                            Update
                        </button>
                        <a href="{{ route('school-admin.fee-names.index') }}"
                           class="text-gray-600 text-sm hover:text-gray-800 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>