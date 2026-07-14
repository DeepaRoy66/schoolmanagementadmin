<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New School
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.schools.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">School Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full border-gray-300 rounded-lg">
                        @error('address')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Status</label>
                        <select name="license_status" class="w-full border-gray-300 rounded-lg" required>
                            <option value="trial" {{ old('license_status') == 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="active" {{ old('license_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ old('license_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                        @error('license_status')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">License Start</label>
                            <input type="date" name="license_start" value="{{ old('license_start') }}"
                                   class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">License Expiry</label>
                            <input type="date" name="license_expiry" value="{{ old('license_expiry') }}"
                                   class="w-full border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trial Ends At (agar Trial ho vane)</label>
                        <input type="date" name="trial_ends_at" value="{{ old('trial_ends_at') }}"
                               class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Save School
                        </button>
                        <a href="{{ route('admin.schools.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>