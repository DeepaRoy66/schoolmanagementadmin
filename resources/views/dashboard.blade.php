<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (auth()->user()->role === 'super_admin')
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                    <p class="text-gray-600 text-sm">
                        Welcome back, <span class="font-semibold text-gray-900">{{ auth()->user()->name }}</span>.
                        You're managing this platform as <span class="font-semibold">Super Admin</span>.
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <a href="{{ route('admin.schools.index') }}"
                       class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition block">
                        <div class="w-10 h-10 rounded-lg bg-gray-900 text-white flex items-center justify-center font-bold mb-4">S</div>
                        <h3 class="font-semibold text-gray-800 mb-1">Schools</h3>
                        <p class="text-sm text-gray-500">View, add, and manage every school on the platform.</p>
                    </a>
                    <a href="{{ route('admin.schools.create') }}"
                       class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition block">
                        <div class="w-10 h-10 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold mb-4">+</div>
                        <h3 class="font-semibold text-gray-800 mb-1">Add New School</h3>
                        <p class="text-sm text-gray-500">Register a new school and set up its license.</p>
                    </a>
                    <a href="{{ route('admin.school-admins.index') }}"
                       class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition block">
                        <div class="w-10 h-10 rounded-lg bg-blue-500 text-white flex items-center justify-center font-bold mb-4">A</div>
                        <h3 class="font-semibold text-gray-800 mb-1">School Admins</h3>
                        <p class="text-sm text-gray-500">Create and manage school admins.</p>
                    </a>
                </div>

            @elseif (auth()->user()->role === 'school_admin')

                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                    <p class="text-gray-600 text-sm">
                        Welcome back, <span class="font-semibold text-gray-900">{{ auth()->user()->name }}</span>.
                        You're managing <span class="font-semibold">{{ auth()->user()->school->name ?? 'your school' }}</span>.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <a href="{{ route('school-admin.teachers.index') }}"
                       class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition block">
                        <div class="w-10 h-10 rounded-lg bg-green-500 text-white flex items-center justify-center font-bold mb-4">T</div>
                        <h3 class="font-semibold text-gray-800 mb-1">Teachers</h3>
                        <p class="text-sm text-gray-500">Add and manage teachers.</p>
                    </a>

                    <a href="{{ route('school-admin.students.index') }}"
                       class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition block">
                        <div class="w-10 h-10 rounded-lg bg-purple-500 text-white flex items-center justify-center font-bold mb-4">St</div>
                        <h3 class="font-semibold text-gray-800 mb-1">Students</h3>
                        <p class="text-sm text-gray-500">Add and manage students.</p>
                    </a>

                    <a href="{{ route('school-admin.notices.index') }}"
                       class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition block">
                        <div class="w-10 h-10 rounded-lg bg-orange-500 text-white flex items-center justify-center font-bold mb-4">N</div>
                        <h3 class="font-semibold text-gray-800 mb-1">Notices</h3>
                        <p class="text-sm text-gray-500">Post announcements for your school.</p>
                    </a>

                    <a href="{{ route('school-admin.timetables.index') }}"
   class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition block">
    <div class="w-10 h-10 rounded-lg bg-teal-500 text-white flex items-center justify-center font-bold mb-4">TT</div>
    <h3 class="font-semibold text-gray-800 mb-1">Timetable</h3>
    <p class="text-sm text-gray-500">Manage class schedules.</p>
</a>
                </div>

                

            @else
                {{-- Teacher/Student: web dashboard chaidaina, mobile app use garchan --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-gray-700 font-medium mb-2">Please use the mobile app to continue.</p>
                    <p class="text-gray-500 text-sm">Your account is set up for the mobile app. Download and log in there to view your {{ auth()->user()->role === 'teacher' ? 'classes and attendance' : 'attendance and homework' }}.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>