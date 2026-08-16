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

    <div class="py-8 overflow-x-hidden">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6 min-w-0">

            @if ($errors->any())
                <div class="border border-red-200 bg-red-50 rounded-md px-4 py-3">
                    <p class="text-sm font-medium text-red-700 mb-1">Form submission failed. Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                <form method="POST" action="{{ route('school-admin.students.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Personal Information --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Personal information</h3>

                        {{-- Photo Upload --}}
                        <div class="flex items-center gap-4 mb-5">
                            <input type="file" name="photo" id="photo" accept="image/*" onchange="previewPhoto(event)" class="hidden">

                            <div id="photo_dropzone" onclick="document.getElementById('photo').click()"
                                 ondragover="event.preventDefault(); this.classList.add('ring-2','ring-blue-400')"
                                 ondragleave="this.classList.remove('ring-2','ring-blue-400')"
                                 ondrop="handlePhotoDrop(event)"
                                 class="relative w-24 h-24 rounded-full cursor-pointer shrink-0 group">
                                <img id="photo_preview" src="https://ui-avatars.com/api/?name=Student&background=e2e8f0&color=64748b&size=96"
                                     class="w-24 h-24 rounded-full object-cover border border-slate-200 bg-slate-100 group-hover:brightness-90 transition" alt="Student photo preview">
                                <span class="absolute bottom-0 right-0 flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 border-2 border-white text-white shadow-sm group-hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17a4 4 0 100-8 4 4 0 000 8z" />
                                    </svg>
                                </span>
                            </div>

                            <div class="min-w-0">
                                <label class="block text-sm font-medium text-slate-600 mb-1">Photo</label>
                                <p id="photo_filename" class="text-sm text-slate-500">Click the photo to upload</p>
                                <p class="text-slate-400 text-xs mt-0.5">JPG or PNG, max 2MB</p>
                                @error('photo')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

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
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Phone <span class="text-slate-400 text-xs">(student's own number)</span></label>
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

                    {{-- Parent / Guardian Details --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Parent / Guardian details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-1">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Parent Name</label>
                                <input type="text" name="parent_name" value="{{ old('parent_name') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('parent_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Parent Phone</label>
                                <input type="text" name="parent_phone" value="{{ old('parent_phone') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('parent_phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Telephone No. <span class="text-slate-400 text-xs">(optional)</span></label>
                                <input type="text" name="telephone_no" value="{{ old('telephone_no') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('telephone_no')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mx-6 border-t border-slate-100"></div>

                    {{-- Emergency / Family Contacts --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Emergency contacts</h3>
                        <p class="text-slate-400 text-xs mb-4 -mt-2">These contacts will be used for emergency communication.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Mother's Name</label>
                                <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('mother_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Mother's Contact Number</label>
                                <input type="text" name="mother_phone" value="{{ old('mother_phone') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('mother_phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Father's Name</label>
                                <input type="text" name="father_name" value="{{ old('father_name') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('father_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Father's Contact Number</label>
                                <input type="text" name="father_phone" value="{{ old('father_phone') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('father_phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-1">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Local Guardian's Name</label>
                                <input type="text" name="local_guardian_name" value="{{ old('local_guardian_name') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('local_guardian_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Local Guardian's Contact Number</label>
                                <input type="text" name="local_guardian_phone" value="{{ old('local_guardian_phone') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('local_guardian_phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="text-slate-400 text-xs mt-2"></p>
                    </div>

                    <div class="mx-6 border-t border-slate-100"></div>

                    {{-- Academic Details --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Academic details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Class</label>
                                <select name="class_id" id="class_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
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
                                <select name="section_id" id="section_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                    <option value="">-- Select Section --</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                                data-class-ids="{{ $section->classes->pluck('id')->implode(',') }}"
                                                {{ old('section_id') == $section->id ? 'selected' : '' }}>
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

        function previewPhoto(event) {
            const file = event.target.files[0];
            renderPhotoPreview(file);
        }

        function handlePhotoDrop(event) {
            event.preventDefault();
            event.currentTarget.classList.remove('border-blue-400', 'bg-blue-50/50');
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

        function filterSections() {
            const classId = document.getElementById('class_id').value;
            const sectionSelect = document.getElementById('section_id');
            const options = sectionSelect.querySelectorAll('option[data-class-ids]');

            let hasSelectedVisible = false;

            options.forEach(option => {
                const classIds = option.dataset.classIds.split(',').filter(Boolean);
                const matches = !classId || classIds.includes(classId);

                option.hidden = !matches;
                option.disabled = !matches;

                if (matches && option.selected) hasSelectedVisible = true;
                if (!matches) option.selected = false;
            });

            if (!hasSelectedVisible) {
                sectionSelect.value = '';
            }
        }

        document.getElementById('class_id').addEventListener('change', filterSections);
        document.addEventListener('DOMContentLoaded', filterSections);
    </script>
</x-app-layout>