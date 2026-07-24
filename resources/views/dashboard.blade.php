<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (auth()->user()->role === 'super_admin')

                {{-- Welcome banner --}}
                <div class="bg-teal-600 rounded-2xl p-6 flex items-center justify-between">
                    <div>
                        <p class="text-teal-100 text-sm">Welcome back,</p>
                        <h3 class="text-white text-xl font-bold mt-1">{{ auth()->user()->name }}</h3>
                        <p class="text-teal-100 text-sm mt-2">You're managing this platform as Super Admin.</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>

                {{-- Stat cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white shadow-sm rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 21h18M5 21V7l8-4v18M19 21V10l-6-3M9 9h1m-1 4h1m-1 4h1" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Total Schools</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalSchools }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Active Schools</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activeSchools }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">School Admins</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalAdmins }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Trial Licenses</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $trialSchools }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Recent schools widget --}}
                    <div class="lg:col-span-2 bg-white shadow-sm rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-gray-900">Recently Added Schools</h4>
                            <a href="{{ route('admin.schools.index') }}" class="text-xs font-medium text-teal-600 hover:text-teal-700">
                                View all
                            </a>
                        </div>

                        @if ($recentSchools->isEmpty())
                            <p class="text-sm text-gray-400 py-6 text-center">No schools added yet.</p>
                        @else
                            <div class="divide-y divide-gray-100">
                                @foreach ($recentSchools as $school)
                                    <div class="flex items-center justify-between py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-semibold text-gray-600 shrink-0">
                                                {{ substr($school->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $school->name }}</p>
                                                <p class="text-xs text-gray-400">
                                                    Added {{ $school->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                            {{ $school->license_status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                            {{ ucfirst($school->license_status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Quick actions --}}
                    <div class="bg-white shadow-sm rounded-2xl p-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h4>
                        <div class="flex flex-col gap-2.5">
                            <a href="{{ route('admin.schools.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-teal-600 text-white text-sm font-medium hover:bg-teal-700">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add New School
                            </a>
                            <a href="{{ route('admin.schools.index') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">
                                View All Schools
                            </a>
                            <a href="{{ route('admin.school-admins.index') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">
                                Manage School Admins
                            </a>
                        </div>
                    </div>

                </div>

            @elseif (auth()->user()->role === 'school_admin')

                {{-- Welcome banner --}}
                <div class="bg-teal-600 rounded-2xl p-6 flex items-center justify-between">
                    <div>
                        <p class="text-teal-100 text-sm">Welcome back,</p>
                        <h3 class="text-white text-xl font-bold mt-1">{{ auth()->user()->name }}</h3>
                        <p class="text-teal-100 text-sm mt-2">
                            Managing {{ auth()->user()->school->name ?? 'your school' }}
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 21h18M5 21V7l8-4v18M19 21V10l-6-3M9 9h1m-1 4h1m-1 4h1" />
                        </svg>
                    </div>
                </div>

                {{-- Stat cards: only this school's own data --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white shadow-sm rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Total Students</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.083 12.083 0 0112 20a12.083 12.083 0 01-6.16-9.42L12 14z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Teaching Staff</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalTeachers }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M8 9h8M8 13h6m-9 8V5a2 2 0 012-2h10a2 2 0 012 2v16l-4-3-3 3-3-3-4 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Notices</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalNotices }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5-1.343 1.5-3 1.5m0-6V6m0 1.5v9m0 1.5v-1.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Fees Collected</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalFeesCollected }}</p>
                        </div>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="bg-white shadow-sm rounded-2xl p-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('school-admin.teachers.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">
                            Manage Teachers
                        </a>
                        <a href="{{ route('school-admin.students.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">
                            Manage Students
                        </a>
                        <a href="{{ route('school-admin.notices.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-teal-600 text-white text-sm font-medium hover:bg-teal-700">
                            Post Notice
                        </a>
                        <a href="{{ route('school-admin.timetables.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">
                            View Timetable
                        </a>
                       <a href="{{ route('school-admin.student-fees.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">
                            Manage Fees
                        </a>
                        <a href="{{ route('school-admin.reports.index') }}"
   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">
    View Reports
</a>
                    </div>
                </div>

            @else

                <div class="bg-white shadow-sm rounded-2xl p-10 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium mb-2">Please use the mobile app to continue</p>
                    <p class="text-gray-500 text-sm max-w-sm mx-auto">
                        Your account is set up for the mobile app. Download and log in there to view your
                        {{ auth()->user()->role === 'teacher' ? 'classes and attendance' : 'attendance and homework' }}.
                    </p>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>