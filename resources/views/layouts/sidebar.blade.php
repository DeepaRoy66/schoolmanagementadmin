<aside class="w-72 min-h-screen bg-white border-r border-gray-200 flex flex-col shadow-sm">

    {{-- Logo / Brand --}}
    <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-100">
        <div class="w-10 h-10 rounded-2xl bg-[#2dd4bf] flex items-center justify-center shadow-md shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6L3 9m9 5l9-5" />
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">School Manager</h1>
            <p class="text-xs text-gray-500 font-medium">Admin Panel</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-8">

        @php
            $item = 'flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-200';
            $active = 'bg-[#2dd4bf]/10 text-[#2dd4bf]';
            $inactive = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
        @endphp

        @if (auth()->user()->role === 'super_admin')
            
            {{-- General --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">General</p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="{{ $item }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.system-usage.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.system-usage.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        System Usage
                    </a>
                </div>
            </div>

            {{-- Schools --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">Schools</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.schools.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('admin.schools.index') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 21h18M5 21V7l8-4v18M19 21V10l-6-3M9 9h1m-1 4h1m-1 4h1" />
                        </svg>
                        All Schools
                    </a>

                    <a href="{{ route('admin.schools.create') }}" 
                       class="{{ $item }} {{ request()->routeIs('admin.schools.create') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4v16m8-8H4" />
                        </svg>
                        Add New School
                    </a>

                    <a href="{{ route('admin.school-admins.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('admin.school-admins.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87" />
                        </svg>
                        School Admins
                    </a>
                </div>
            </div>

            {{-- License --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">License</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.licenses.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.licenses.index') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Manage Licenses
                    </a>

                    <a href="{{ route('admin.licenses.expiring') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.licenses.expiring') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        Expiring Soon
                        @if (($expiringSoonCount ?? 0) > 0)
                            <span class="ml-auto inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                {{ $expiringSoonCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Communication --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">Communication</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.announcements.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.announcements.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535" />
                        </svg>
                        Announcements
                    </a>
                </div>
            </div>

        @elseif (auth()->user()->role === 'school_admin')

            {{-- General --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">General</p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="{{ $item }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>

            {{-- Administration --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">Administration</p>
                <div class="space-y-1">
                    <a href="{{ route('school-admin.classes.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.classes.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Classes
                    </a>
                    <a href="{{ route('school-admin.sections.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.sections.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Sections
                    </a>
                    <a href="{{ route('school-admin.subjects.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.subjects.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        Subjects
                    </a>
                </div>
            </div>

            {{-- Teachers --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">Teachers</p>
                <div class="space-y-1">
                    <a href="{{ route('school-admin.teachers.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.teachers.index') || request()->routeIs('school-admin.teachers.show') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.083 12.083 0 0112 20a12.083 12.083 0 01-6.16-9.42L12 14z" />
                        </svg>
                        All Teachers
                    </a>

                    <a href="{{ route('school-admin.teachers.create') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.teachers.create') || request()->routeIs('school-admin.teachers.store') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                        Add Teacher
                    </a>

                    {{-- FIXED: was 'school-admin.subject-allocation.index' (singular, no longer
                         exists). Points to SubjectAllocationController now. --}}
                    <a href="{{ route('school-admin.subject-allocations.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.subject-allocations.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        Subject Allocation
                    </a>

                    {{-- NOTE: school-admin.class-teacher.form still points at
                         TeacherController::assignClassTeacherForm(), which does not exist
                         yet. This link will 500 until that controller method is implemented. --}}
                    <a href="{{ route('school-admin.class-teacher.form') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.class-teacher.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87" />
                        </svg>
                        Assign Class Teacher
                    </a>
                </div>
            </div>

            {{-- Academics --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">Academics</p>
                <div class="space-y-1">
                    <a href="{{ route('school-admin.students.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.students.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Students
                    </a>

                    <a href="{{ route('school-admin.timetables.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.timetables.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Timetable
                    </a>

                    <a href="{{ route('school-admin.reports.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.reports.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Reports
                    </a>
                </div>
            </div>

            {{-- Communication --}}
            <div>
                <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">Communication</p>
                <div class="space-y-1">
                    <a href="{{ route('school-admin.notices.index') }}" 
                       class="{{ $item }} {{ request()->routeIs('school-admin.notices.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 9h8M8 13h6m-9 8V5a2 2 0 012-2h10a2 2 0 012 2v16l-4-3-3 3-3-3-4 3z" />
                        </svg>
                        Notices
                    </a>

                    <a href="{{ route('school-admin.announcements.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.announcements.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535" />
                        </svg>
                        Announcements
                    </a>
                </div>
            </div>

            {{-- Finance --}}
<div>
    <p class="px-4 mb-3 text-xs font-semibold text-gray-400 tracking-widest uppercase">Finance</p>
    <div class="space-y-1">

       

        <a href="{{ route('school-admin.fee-categories.index') }}"
           class="{{ $item }} {{ request()->routeIs('school-admin.fee-categories.*') ? $active : $inactive }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 10h16M4 14h10M4 18h10" />
            </svg>
            Fee Categories
        </a>

        <a href="{{ route('school-admin.student-fees.index') }}"
           class="{{ $item }} {{ request()->routeIs('school-admin.student-fees.*') ? $active : $inactive }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-6.32 4M18 21v-3m0 0v-3m0 3h3m-3 0h-3" />
            </svg>
            Student Fees
        </a>

        <a href="{{ route('school-admin.fee-payments.index') }}"
           class="{{ $item }} {{ request()->routeIs('school-admin.fee-payments.*') ? $active : $inactive }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a1.5 1.5 0 001.5-1.5V6.75a1.5 1.5 0 00-1.5-1.5h-15a1.5 1.5 0 00-1.5 1.5v10.5a1.5 1.5 0 001.5 1.5z" />
            </svg>
            Payments
        </a>

        <a href="{{ route('school-admin.fees.reports') }}"
           class="{{ $item }} {{ request()->routeIs('school-admin.fees.reports') ? $active : $inactive }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Fee Reports
        </a>

    </div>
</div>

        @else
            <div class="px-3">
                <a href="{{ route('dashboard') }}" 
                   class="{{ $item }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
            </div>
        @endif

    </nav>

    {{-- User Footer --}}
    <div class="border-t border-gray-100 p-4 mt-auto">
        <div class="flex items-center gap-3 px-3 py-3 rounded-2xl hover:bg-gray-50 transition-colors">
            <div class="w-10 h-10 rounded-2xl bg-[#2dd4bf] flex items-center justify-center text-white font-semibold text-lg shadow-inner">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
            </div>
        </div>
    </div>
</aside>