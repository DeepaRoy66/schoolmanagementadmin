<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Teacher
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('school-admin.teachers.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teacher Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full border-gray-300 rounded-lg">
                        @error('phone')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="w-full border-gray-300 rounded-lg" placeholder="e.g. Mathematics">
                        @error('subject')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-1">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Class Teacher Of (Class)</label>
                            <input type="text" name="class_teacher_of_class" value="{{ old('class_teacher_of_class') }}"
                                   class="w-full border-gray-300 rounded-lg" placeholder="e.g. Grade 5">
                            @error('class_teacher_of_class')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                            <input type="text" name="class_teacher_of_section" value="{{ old('class_teacher_of_section') }}"
                                   class="w-full border-gray-300 rounded-lg" placeholder="e.g. A">
                            @error('class_teacher_of_section')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mb-6">
                       Leave these fields empty if this teacher is only a subject teacher.
                    </p>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password"
                               class="w-full border-gray-300 rounded-lg" required>
                        @error('password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                            Save Teacher
                        </button>
                        <a href="{{ route('school-admin.teachers.index') }}" class="text-gray-600 text-sm hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>