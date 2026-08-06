<aside class="w-72 min-h-screen bg-[#0f1b2d] border-r border-black/20 flex flex-col font-['Inter',_'Segoe_UI',_sans-serif]">

    {{-- Logo / Brand --}}
    <div class="flex items-center gap-3 px-6 py-6 border-b border-white/10">
        <div class="w-11 h-11 rounded-lg bg-gradient-to-br from-[#1e4ed8] to-[#1e3a8a] flex items-center justify-center shadow-lg shrink-0 ring-1 ring-white/10">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 3L2 8l10 5 8-4.09V17h2V8L12 3zM4 10.16v3.7c0 2.03 3.58 4.14 8 4.14s8-2.11 8-4.14v-3.7l-8 4.09-8-4.09z" />
            </svg>
        </div>
        <div class="min-w-0">
            <h1 class="text-[15px] font-semibold text-white tracking-tight leading-tight truncate">School Manager</h1>
            <p class="text-[11px] text-slate-400 font-medium tracking-wide uppercase">Administration Portal</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-6 scrollbar-thin">

        @php
            $item = 'group relative flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-md text-[13.5px] font-medium transition-colors duration-150';
            $active = 'bg-white/[0.06] text-white';
            $inactive = 'text-slate-300/80 hover:bg-white/[0.04] hover:text-white';
            $bar = '<span class="absolute left-0 top-1/2 -translate-y-1/2 h-5 w-[3px] rounded-r-full bg-[#3b82f6]"></span>';
        @endphp

        @if (auth()->user()->role === 'super_admin')

            {{-- General --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">General</p>
                <div class="space-y-0.5">
                    <a href="{{ route('dashboard') }}"
                       class="{{ $item }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                        @if(request()->routeIs('dashboard')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.system-usage.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.system-usage.*') ? $active : $inactive }}">
                        @if(request()->routeIs('admin.system-usage.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        System Usage
                    </a>
                </div>
            </div>

            {{-- Schools --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">Schools</p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.schools.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.schools.index') ? $active : $inactive }}">
                        @if(request()->routeIs('admin.schools.index')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 21h18M5 21V7l8-4v18M19 21V10l-6-3M9 9h1m-1 4h1m-1 4h1" />
                        </svg>
                        All Schools
                    </a>

                    <a href="{{ route('admin.schools.create') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.schools.create') ? $active : $inactive }}">
                        @if(request()->routeIs('admin.schools.create')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16m8-8H4" />
                        </svg>
                        Add New School
                    </a>

                    <a href="{{ route('admin.school-admins.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.school-admins.*') ? $active : $inactive }}">
                        @if(request()->routeIs('admin.school-admins.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87" />
                        </svg>
                        School Admins
                    </a>
                </div>
            </div>

            {{-- License --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">License</p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.licenses.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.licenses.index') ? $active : $inactive }}">
                        @if(request()->routeIs('admin.licenses.index')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Manage Licenses
                    </a>

                    <a href="{{ route('admin.licenses.expiring') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.licenses.expiring') ? $active : $inactive }}">
                        @if(request()->routeIs('admin.licenses.expiring')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span class="flex-1">Expiring Soon</span>
                        @if (($expiringSoonCount ?? 0) > 0)
                            <span class="inline-flex items-center justify-center min-w-[1.2rem] h-[1.2rem] px-1 rounded-full text-[10.5px] font-semibold bg-amber-400 text-[#0f1b2d]">
                                {{ $expiringSoonCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Communication --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">Communication</p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.announcements.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.announcements.*') ? $active : $inactive }}">
                        @if(request()->routeIs('admin.announcements.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535" />
                        </svg>
                        Announcements
                    </a>
                </div>
            </div>

        @elseif (auth()->user()->role === 'school_admin')

            {{-- General --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">General</p>
                <div class="space-y-0.5">
                    <a href="{{ route('dashboard') }}"
                       class="{{ $item }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                        @if(request()->routeIs('dashboard')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>

            {{-- Administration --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">Administration</p>
                <div class="space-y-0.5">


                <a href="{{ route('school-admin.academic-years.index') }}"
           class="{{ $item }} {{ request()->routeIs('school-admin.academic-years.*') ? $active : $inactive }}">
            @if(request()->routeIs('school-admin.academic-years.*')) {!! $bar !!} @endif
            <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Academic Year
        </a>

  
                    <a href="{{ route('school-admin.classes.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.classes.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.classes.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Classes
                    </a>
                    <a href="{{ route('school-admin.sections.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.sections.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.sections.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Sections
                    </a>

                          <a href="{{ route('school-admin.academic-year-runs.index') }}"
   class="{{ $item }} {{ request()->routeIs('school-admin.academic-year-runs.*') ? $active : $inactive }}">
    @if(request()->routeIs('school-admin.academic-year-runs.*')) {!! $bar !!} @endif
    <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
    </svg>
    Academic Year Run
</a>

<a href="{{ route('school-admin.class-change.index') }}"
   class="{{ $item }} {{ request()->routeIs('school-admin.class-change.*') ? $active : $inactive }}">
    @if(request()->routeIs('school-admin.class-change.*')) {!! $bar !!} @endif
    <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4" />
    </svg>
    Class Change
</a>
        
                    <a href="{{ route('school-admin.subjects.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.subjects.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.subjects.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        Subjects
                    </a>
                </div>
            </div>

            {{-- Teachers --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">Teachers</p>
                <div class="space-y-0.5">
                    <a href="{{ route('school-admin.teachers.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.teachers.index') || request()->routeIs('school-admin.teachers.show') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.teachers.index') || request()->routeIs('school-admin.teachers.show')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.083 12.083 0 0112 20a12.083 12.083 0 01-6.16-9.42L12 14z" />
                        </svg>
                        All Teachers
                    </a>

                    <a href="{{ route('school-admin.teachers.create') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.teachers.create') || request()->routeIs('school-admin.teachers.store') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.teachers.create') || request()->routeIs('school-admin.teachers.store')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                        Add Teacher
                    </a>

                    {{-- FIXED: was 'school-admin.subject-allocation.index' (singular, no longer
                         exists). Points to SubjectAllocationController now. --}}
                    <a href="{{ route('school-admin.subject-allocations.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.subject-allocations.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.subject-allocations.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        Subject Allocation
                    </a>

                    {{-- NOTE: school-admin.class-teacher.form still points at
                         TeacherController::assignClassTeacherForm(), which does not exist
                         yet. This link will 500 until that controller method is implemented. --}}
                    <a href="{{ route('school-admin.class-teacher.form') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.class-teacher.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.class-teacher.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87" />
                        </svg>
                        Assign Class Teacher
                    </a>
                </div>
            </div>

            {{-- Academics --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">Academics</p>
                <div class="space-y-0.5">
                    <a href="{{ route('school-admin.students.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.students.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.students.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Students
                    </a>

                    <a href="{{ route('school-admin.timetable-images.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.timetable-images.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.timetable-images.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Timetable
                    </a>

                    <a href="{{ route('school-admin.reports.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.reports.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.reports.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Reports
                    </a>
                </div>
            </div>

            {{-- Communication --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">Communication</p>
                <div class="space-y-0.5">
                    <a href="{{ route('school-admin.notices.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.notices.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.notices.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 9h8M8 13h6m-9 8V5a2 2 0 012-2h10a2 2 0 012 2v16l-4-3-3 3-3-3-4 3z" />
                        </svg>
                        Notices
                    </a>

                    <a href="{{ route('school-admin.announcements.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.announcements.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.announcements.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535" />
                        </svg>
                        Announcements
                    </a>
                </div>
            </div>

            {{-- Finance --}}
            <div>
                <p class="px-4 mb-2 text-[10.5px] font-semibold text-slate-500 tracking-[0.12em] uppercase">Finance</p>
                <div class="space-y-0.5">
                    <a href="{{ route('school-admin.billing-periods.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.billing-periods.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.billing-periods.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Billing Periods
                    </a>

                    <a href="{{ route('school-admin.fee-groups.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.fee-groups.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.fee-groups.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 7h-9m3-3l3 3-3 3M4 17h9m-3 3l-3-3 3-3" />
                        </svg>
                        Fee Groups
                    </a>

                    <a href="{{ route('school-admin.fee-names.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.fee-names.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.fee-names.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Fee Names
                    </a>

                    <a href="{{ route('school-admin.fee-rates.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.fee-rates.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.fee-rates.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5S13.657 14 12 14m0-6c1.11 0 2.08.402 2.599 1M12 8V6.5M12 14v1.5m0-1.5c-1.11 0-2.08-.402-2.599-1M12 21a9 9 0 100-18 9 9 0 000 18z" />
                        </svg>
                        Fee Rates
                    </a>

                    <a href="{{ route('school-admin.fee-discounts.create') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.fee-discounts.*') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.fee-discounts.*')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                        </svg>
                        Discount
                    </a>

                    <a href="{{ route('school-admin.fee-payments.create') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.fee-payments.create') || request()->routeIs('school-admin.fee-payments.pay-form') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.fee-payments.create') || request()->routeIs('school-admin.fee-payments.pay-form')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a1.5 1.5 0 001.5-1.5V6.75a1.5 1.5 0 00-1.5-1.5h-15a1.5 1.5 0 00-1.5 1.5v10.5a1.5 1.5 0 001.5 1.5z" />
                        </svg>
                        Fee Payment
                    </a>

                    <a href="{{ route('school-admin.fee-payments.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.fee-payments.index') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.fee-payments.index')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5S13.657 14 12 14m0-6c1.11 0 2.08.402 2.599 1M12 8V6.5M12 14v1.5m0-1.5c-1.11 0-2.08-.402-2.599-1M12 21a9 9 0 100-18 9 9 0 000 18z" />
                        </svg>
                        Payment History
                    </a>

                    
                    
                   

                   

                    <a href="{{ route('school-admin.fees.reports') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.fees.reports') ? $active : $inactive }}">
                        @if(request()->routeIs('school-admin.fees.reports')) {!! $bar !!} @endif
                        <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
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
                    @if(request()->routeIs('dashboard')) {!! $bar !!} @endif
                    <svg class="w-[18px] h-[18px] shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
            </div>
        @endif

    </nav>

    {{-- User Footer --}}
    <div class="border-t border-white/10 p-3">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-white/[0.05] transition-colors cursor-pointer">
            <div class="w-9 h-9 rounded-md bg-gradient-to-br from-[#1e4ed8] to-[#1e3a8a] flex items-center justify-center text-white font-semibold text-sm ring-1 ring-white/10 shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[13px] font-medium text-white truncate leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-slate-400 capitalize leading-tight">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
            </div>
            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
            </svg>
        </div>
    </div>
</aside>