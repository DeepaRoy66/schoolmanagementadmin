<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit School: {{ $school->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.schools.update', $school) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">School Name</label>
                        <input type="text" name="name" value="{{ old('name', $school->name) }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">School Code</label>
                        <input type="text" name="school_code" value="{{ old('school_code', $school->school_code) }}"
                               class="w-full border-gray-300 rounded-lg">
                        @error('school_code')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" name="address" value="{{ old('address', $school->address) }}"
                               class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Status</label>
                        <select name="license_status" class="w-full border-gray-300 rounded-lg" required>
                            @foreach (['trial', 'active', 'expired'] as $status)
                                <option value="{{ $status }}" {{ old('license_status', $school->license_status) == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">License Start</label>
                            {{-- ->format('Y-m-d') is required: HTML date inputs only recognize that exact
                                 format. Passing a Carbon instance/date-with-time string straight in makes the
                                 browser silently show the field as empty, which is why the picked date "disappeared". --}}
                            <input type="date" name="license_start"
                                   value="{{ old('license_start', optional($school->license_start)->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-lg">
                            @error('license_start')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">License Expiry</label>
                            <input type="date" name="license_expiry"
                                   value="{{ old('license_expiry', optional($school->license_expiry)->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-lg">
                            @error('license_expiry')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trial Ends At</label>
                        <input type="date" name="trial_ends_at"
                               value="{{ old('trial_ends_at', optional($school->trial_ends_at)->format('Y-m-d')) }}"
                               class="w-full border-gray-300 rounded-lg">
                        @error('trial_ends_at')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Update School
                        </button>
                        <a href="{{ route('admin.schools.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>