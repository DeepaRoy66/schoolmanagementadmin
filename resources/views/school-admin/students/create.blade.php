<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Students
            </span>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">Add Student</span>
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
                <form method="POST" action="{{ route('school-admin.students.store') }}">
                    @csrf

                    {{-- Personal Information --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Personal information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                @error('first_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                @error('last_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Date of Birth</label>
                                <input type="date" name="dob" value="{{ old('dob') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('dob')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Gender</label>
                                <select name="gender" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                    <option value="">-- Select Gender --</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
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
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" required>
                                @error('email')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">{{ old('address') }}</textarea>
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
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
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
                                        <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-1">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Roll Number</label>
                                <input type="text" name="roll_number" value="{{ old('roll_number') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('roll_number')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Status</label>
                                <select name="status" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-400" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="dropped_out" {{ old('status') == 'dropped_out' ? 'selected' : '' }}>Dropped Out</option>
                                </select>
                                @error('status')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="text-slate-400 text-xs mb-2">Student ID gets automatically generated.</p>
                    </div>

                    <div class="mx-6 border-t border-slate-100"></div>

                    {{-- Login Credentials --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Login credentials</h3>

                        <div class="mb-1">
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="text" name="password" id="password" value="{{ old('password') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 font-mono bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" readonly required
                                       placeholder="Click 'Generate Password' to create a password">
                                <button type="button" onclick="generatePassword()"
                                        class="whitespace-nowrap inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 border border-slate-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-slate-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Generate Password
                                </button>
                            </div>
                            <p class="text-slate-400 text-xs mt-1">This password will be used for the student to log in.</p>
                            @error('password')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-6 py-5 mt-2 border-t border-slate-100 bg-slate-50/50">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Student
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

    <script>
        function generatePassword() {
            const firstName = document.getElementById('first_name').value.trim().toLowerCase().replace(/[^a-z]/g, '') || 'student';

            const chars = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
            let randomPart = '';
            for (let i = 0; i < 5; i++) {
                randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            document.getElementById('password').value = firstName + '@' + randomPart;
        }
    </script>
</x-app-layout>