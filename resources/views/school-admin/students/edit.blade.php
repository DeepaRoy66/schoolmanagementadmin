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

    <div class="py-8 overflow-x-hidden">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6 min-w-0">

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
                <form method="POST" action="{{ route('school-admin.students.update', $student) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                <img id="photo_preview"
                                     src="{{ $student->photo ? Storage::url($student->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name) . '&background=e2e8f0&color=64748b&size=96' }}"
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
                                <p id="photo_filename" class="text-sm text-slate-500">
                                    {{ $student->photo ? 'Click the photo to replace' : 'Click the photo to upload' }}
                                </p>
                                <p class="text-slate-400 text-xs mt-0.5">JPG or PNG, max 2MB</p>

                                @if ($student->photo)
                                    <label class="inline-flex items-center gap-1.5 mt-1.5 text-xs text-slate-500 cursor-pointer">
                                        <input type="checkbox" name="remove_photo" id="remove_photo" value="1" onchange="handleRemovePhoto(event)"
                                               class="rounded border-slate-300 text-red-600 focus:ring-red-500/30">
                                        Remove current photo
                                    </label>
                                @endif

                                @error('photo')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $student->first_name) }}"
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
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Phone <span class="text-slate-400 text-xs">(student's own number)</span></label>
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

                    {{-- Parent / Guardian Details --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Parent / Guardian details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-1">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Parent Name</label>
                                <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('parent_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Parent Phone</label>
                                <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('parent_phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Telephone No. <span class="text-slate-400 text-xs">(optional)</span></label>
                                <input type="text" name="telephone_no" value="{{ old('telephone_no', $student->telephone_no) }}"
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
                                <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('mother_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Mother's Contact Number</label>
                                <input type="text" name="mother_phone" value="{{ old('mother_phone', $student->mother_phone) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('mother_phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Father's Name</label>
                                <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('father_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Father's Contact Number</label>
                                <input type="text" name="father_phone" value="{{ old('father_phone', $student->father_phone) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('father_phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-1">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Local Guardian's Name</label>
                                <input type="text" name="local_guardian_name" value="{{ old('local_guardian_name', $student->local_guardian_name) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('local_guardian_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Local Guardian's Contact Number</label>
                                <input type="text" name="local_guardian_phone" value="{{ old('local_guardian_phone', $student->local_guardian_phone) }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                @error('local_guardian_phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-1">
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

                    <div class="mx-6 border-t border-slate-100"></div>

                    {{-- Login Credentials --}}
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Login credentials</h3>

                        <div class="mb-1">
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Password</label>
                            <div class="flex gap-2">
                                <input type="text" name="password" id="password" value="{{ old('password') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 font-mono bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400" readonly
                                       placeholder="Leave blank to keep current password">
                                <button type="button" onclick="generatePassword()"
                                        class="whitespace-nowrap inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 border border-slate-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-slate-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Generate New Password
                                </button>
                            </div>
                            <p class="text-slate-400 text-xs mt-1">Only fill this in if you want to reset the student's login password.</p>
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

            // If a new photo is chosen, uncheck "remove photo" since they contradict each other.
            const removeCheckbox = document.getElementById('remove_photo');
            if (removeCheckbox) removeCheckbox.checked = false;
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

            const removeCheckbox = document.getElementById('remove_photo');
            if (removeCheckbox) removeCheckbox.checked = false;
        }

        function renderPhotoPreview(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('photo_preview').src = e.target.result;
            };
            reader.readAsDataURL(file);

            document.getElementById('photo_filename').textContent = file.name;
        }

        function handleRemovePhoto(event) {
            if (event.target.checked) {
                // Clear any newly selected file and show the placeholder avatar.
                document.getElementById('photo').value = '';
                document.getElementById('photo_preview').src =
                    'https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&background=e2e8f0&color=64748b&size=96';
                document.getElementById('photo_filename').textContent = 'Photo will be removed on save';
            } else {
                document.getElementById('photo_filename').textContent = 'Click the photo to replace';
            }
        }
    </script>
</x-app-layout>