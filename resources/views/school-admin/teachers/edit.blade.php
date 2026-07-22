<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Teacher: {{ $teacher->full_name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Class teacher status is managed on its own page now, not here --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm font-medium text-gray-700 mb-1">Class Teacher Status</p>
                @if ($teacher->classTeacherAssignment)
                    <p class="text-sm text-gray-600">
                        Currently class teacher of
                        <span class="font-medium text-gray-900">
                            {{ $teacher->classTeacherAssignment->schoolClass->name ?? '' }}
                            - {{ $teacher->classTeacherAssignment->section->name ?? '' }}
                        </span>
                    </p>
                @else
                    <p class="text-sm text-gray-500">Not currently a class teacher (subject teacher only).</p>
                @endif
                <a href="{{ route('school-admin.class-teacher.form') }}" class="text-xs text-[#2dd4bf] hover:underline">
                    Manage class teacher assignments →
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('school-admin.teachers.update', $teacher) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $teacher->first_name) }}"
                                   class="w-full border-gray-300 rounded-lg" required>
                            @error('first_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $teacher->middle_name) }}"
                                   class="w-full border-gray-300 rounded-lg">
                            @error('middle_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $teacher->last_name) }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('last_name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $teacher->email) }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}"
                               class="w-full border-gray-300 rounded-lg">
                        @error('phone')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob', optional($teacher->dob)->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-lg">
                            @error('dob')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" class="w-full border-gray-300 rounded-lg">
                                <option value="">-- Select --</option>
                                @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('gender', $teacher->gender) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gender')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                            <select name="marital_status" class="w-full border-gray-300 rounded-lg">
                                <option value="">-- Select --</option>
                                @foreach (['single' => 'Single', 'married' => 'Married', 'other' => 'Other'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('marital_status', $teacher->marital_status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('marital_status')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PAN No.</label>
                            <input type="text" name="pan_no" value="{{ old('pan_no', $teacher->pan_no) }}"
                                   class="w-full border-gray-300 rounded-lg">
                            @error('pan_no')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" rows="2" class="w-full border-gray-300 rounded-lg">{{ old('address', $teacher->address) }}</textarea>
                        @error('address')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                        <input type="text" name="designation" value="{{ old('designation', $teacher->designation) }}"
                               class="w-full border-gray-300 rounded-lg">
                        @error('designation')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" class="w-full border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-400 mt-1">Khali rakhnus password change garna nachahane bhaye.</p>
                        @error('password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Update Teacher
                        </button>
                        <a href="{{ route('school-admin.teachers.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>