<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Students
            </span>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">Edit: {{ $student->full_name }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="border border-red-200 bg-red-50 rounded-md px-4 py-3">
                    <p class="text-sm font-medium text-red-700 mb-1">Form submit hudaina, yi error haru fix garnus:</p>
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                <form method="POST" action="{{ route('school-admin.students.update', $student) }}">
                    @csrf
                    @method('PUT')

                    {{-- Personal Information --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Personal information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                @error('first_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('middle_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                @error('last_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Date of Birth</label>
                                <input type="date" name="dob" value="{{ old('dob', $student->dob?->format('Y-m-d')) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('dob')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Gender</label>
                                <select name="gender" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                    <option value="">-- Select Gender --</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mx-6 border-t border-slate-100"></div>

                    {{-- Contact Details --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Contact details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $student->email) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                @error('email')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mx-6 border-t border-slate-100"></div>

                    {{-- Academic Details --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Academic details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Class</label>
                                <select name="class_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                    <option value="">-- Select Class --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Academic Year</label>
                                <select name="academic_year_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                    <option value="">-- Select Academic Year --</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id', $student->academic_year_id) == $year->id ? 'selected' : '' }}>
                                            {{ $year->year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academic_year_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Section</label>
                                <select name="section_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                    <option value="">-- Select Section --</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Roll Number</label>
                                <input type="text" name="roll_number" value="{{ old('roll_number', $student->roll_number) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('roll_number')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Status</label>
                                @php
                                    $currentStatus = old('status', $student->status);
                                    $statusRing = [
                                        'active'      => 'focus:ring-green-500/30 focus:border-green-400',
                                        'inactive'    => 'focus:ring-slate-400/30 focus:border-slate-400',
                                        'dropped_out' => 'focus:ring-red-500/30 focus:border-red-400',
                                    ][$currentStatus] ?? 'focus:ring-blue-500/30 focus:border-blue-400';
                                @endphp
                                <select name="status" required
                                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 {{ $statusRing }}">
                                    <option value="active" {{ $currentStatus == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $currentStatus == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="dropped_out" {{ $currentStatus == 'dropped_out' ? 'selected' : '' }}>Dropped Out</option>
                                </select>
                                @error('status')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-6 py-5 mt-2 border-t border-slate-100 bg-slate-50/50">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Student
                        </button>
                        <a href="{{ route('school-admin.students.index') }}"
                           class="inline-flex items-center gap-1.5 border border-slate-300 text-slate-600 px-4 py-2.5 rounded-md text-sm font-medium hover:bg-slate-100 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>