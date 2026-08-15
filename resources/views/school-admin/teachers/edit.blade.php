<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Teacher: {{ $teacher->full_name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Class teacher status is managed on its own page now, not here --}}
            <div class="bg-white border border-gray-200 rounded-md px-6 py-4">
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
                <a href="{{ route('school-admin.class-teacher.form') }}" class="text-xs text-[#3b82f6] hover:underline">
                    Manage class teacher assignments →
                </a>
            </div>

            <div class="bg-white border border-gray-200 rounded-md">

                <div class="px-6 py-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500">
                        Update the teacher's details below. Leave the password field blank to keep their current password.
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

                <form method="POST" action="{{ route('school-admin.teachers.update', $teacher) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Personal Details -->
                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Personal Details</h3>

                        <!-- Photo Upload -->
                        <div class="flex items-center gap-4 mb-5">
                            <input type="file" name="photo" id="photo" accept="image/*" onchange="previewPhoto(event)" class="hidden">

                            <div id="photo_dropzone" onclick="document.getElementById('photo').click()"
                                 ondragover="event.preventDefault(); this.classList.add('ring-2','ring-blue-400')"
                                 ondragleave="this.classList.remove('ring-2','ring-blue-400')"
                                 ondrop="handlePhotoDrop(event)"
                                 class="relative w-24 h-24 rounded-full cursor-pointer shrink-0 group">
                                <img id="photo_preview"
                                     src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->first_name . ' ' . $teacher->last_name) . '&background=e2e8f0&color=64748b&size=96' }}"
                                     class="w-24 h-24 rounded-full object-cover border border-gray-200 bg-gray-100 group-hover:brightness-90 transition" alt="Teacher photo preview">
                                <span class="absolute bottom-0 right-0 flex items-center justify-center w-8 h-8 rounded-full bg-[#3b82f6] border-2 border-white text-white shadow-sm group-hover:bg-[#2563eb] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17a4 4 0 100-8 4 4 0 000 8z" />
                                    </svg>
                                </span>
                            </div>

                            <div class="min-w-0">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                                <p id="photo_filename" class="text-sm text-gray-500">Click the photo to change it</p>
                                <p class="text-gray-400 text-xs mt-0.5">JPG or PNG, max 2MB</p>
                                @error('photo')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $teacher->first_name) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                @error('first_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $teacher->middle_name) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                @error('middle_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $teacher->last_name) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                @error('last_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" name="dob" value="{{ old('dob', optional($teacher->dob)->format('Y-m-d')) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                @error('dob')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select name="gender" class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                                <select name="marital_status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
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
                                <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                                @error('phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $teacher->email) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                @error('email')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">{{ old('address', $teacher->address) }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-100 mx-6"></div>

                    <!-- Employment Details -->
                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Employment Details</h3>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation', $teacher->designation) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" placeholder="e.g. Senior Teacher">
                                @error('designation')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                @php $currentStatus = old('status', $teacher->is_active ? 'active' : 'inactive'); @endphp
                                <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]" required>
                                    <option value="active" {{ $currentStatus == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $currentStatus == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <p class="text-gray-400 text-xs mt-1">
                                    Inactive teachers can't log in and won't appear when assigning class teachers, but all their records are kept.
                                </p>
                                @error('status')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 mx-6"></div>

                    <!-- Account -->
                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Account Access</h3>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password"
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                            <p class="text-gray-400 text-xs mt-1">Leave blank to keep the teacher's current password.</p>
                            @error('password')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-6 py-5 mt-2 border-t border-gray-200 bg-gray-50 rounded-b-md">
                        <button type="submit"
                                class="bg-[#3b82f6] text-white px-5 py-2 rounded text-sm font-medium hover:bg-[#2563eb] transition-colors">
                            Update Teacher
                        </button>
                        <a href="{{ route('school-admin.teachers.index') }}" class="text-gray-600 text-sm font-medium hover:underline">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            renderPhotoPreview(file);
        }

        function handlePhotoDrop(event) {
            event.preventDefault();
            event.currentTarget.classList.remove('ring-2', 'ring-blue-400');
            const file = event.dataTransfer.files[0];
            if (!file) return;

            const input = document.getElementById('photo');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;

            renderPhotoPreview(file);
        }

        function renderPhotoPreview(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('photo_preview').src = e.target.result;
            };
            reader.readAsDataURL(file);

            document.getElementById('photo_filename').textContent = file.name;
            document.getElementById('photo_dropzone').classList.remove('ring-2', 'ring-blue-400');
        }
    </script>
</x-app-layout>