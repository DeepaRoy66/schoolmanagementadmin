<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Teacher
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-md">

                <div class="px-6 py-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500">
                        Fill in the details below to create a teacher account. A password will be generated for the teacher, which you should provide to them for their initial login.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mx-6 mt-5 border border-red-200 bg-red-50 rounded-md px-4 py-3">
                        <p class="text-sm font-medium text-red-800 mb-1">Form submission failed. Please correct the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('school-admin.teachers.store') }}">
                    @csrf

                    <!-- Personal Details -->
                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Personal Details</h3>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                @error('first_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                @error('middle_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                @error('last_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" name="dob" value="{{ old('dob') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                @error('dob')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select name="gender" class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                    <option value="">-- Select --</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                                <select name="marital_status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                    <option value="">-- Select --</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="other" {{ old('marital_status') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('marital_status')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PAN No.</label>
                                <input type="text" name="pan_no" value="{{ old('pan_no') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                @error('pan_no')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 mx-6"></div>

                    <!-- Contact Details -->
                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Contact Details</h3>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                @error('phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                @error('email')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-100 mx-6"></div>

                    <!-- Employment Details -->
                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Employment Details</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                            <input type="text" name="designation" value="{{ old('designation') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" placeholder="e.g. Senior Teacher">
                            @error('designation')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- REMOVED: 'Subject' field -> ab "Subject Allocation" page will handle --}}
                        {{-- REMOVED: 'Class Teacher Of' + 'Section' fields -> ab "Assign Class Teacher" page will handle --}}
                    </div>

                    <div class="border-t border-gray-100 mx-6"></div>

                    <!-- Account -->
                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Account Access</h3>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="text" name="password" id="password" value="{{ old('password') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 font-mono bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" readonly required
                                       placeholder="Click 'Generate Password' to create one">
                                <button type="button" onclick="generatePassword()"
                                        class="whitespace-nowrap border border-[#3b82f6] text-[#3b82f6] px-4 py-2 rounded text-sm font-medium hover:bg-[#3b82f6] hover:text-white transition-colors">
                                    Generate Password
                                </button>
                            </div>
                            <p class="text-gray-400 text-xs mt-1">The generated password will be provided to the teacher for their initial login, and they can change it after logging in.</p>
                            @error('password')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-6 py-5 mt-2 border-t border-gray-200 bg-gray-50 rounded-b-md">
                        <button type="submit"
                                class="bg-[#3b82f6] text-white px-5 py-2 rounded text-sm font-medium hover:bg-[#2563eb] transition-colors">
                            Save Teacher
                        </button>
                        <a href="{{ route('school-admin.teachers.index') }}" class="text-gray-600 text-sm font-medium hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function generatePassword() {
            const firstName = document.getElementById('first_name').value.trim().toLowerCase().replace(/[^a-z]/g, '') || 'teacher';

            const chars = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
            let randomPart = '';
            for (let i = 0; i < 5; i++) {
                randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            document.getElementById('password').value = firstName + '@' + randomPart;
        }
    </script>
</x-app-layout>