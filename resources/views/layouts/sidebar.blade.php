<aside class="w-64 min-h-screen bg-white border-r border-gray-100 flex flex-col">

    {{-- Logo / Brand --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6L3 9m9 5l9-5" />
            </svg>
        </div>
        <div>
            <h1 class="text-sm font-bold text-gray-900 leading-none">School Manager</h1>
            <p class="text-xs text-gray-400 mt-1">Admin Panel</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6">

        @php
            $item = 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors';
            $active = 'bg-indigo-50 text-indigo-600';
            $inactive = 'text-gray-500 hover:bg-gray-50 hover:text-gray-900';
        @endphp

        @if (auth()->user()->role === 'super_admin')

            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">General</p>
                <div class="space-y-0.5">
                    <a href="{{ route('dashboard') }}"
                       class="{{ $item }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Schools</p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.schools.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.schools.index') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 21h18M5 21V7l8-4v18M19 21V10l-6-3M9 9h1m-1 4h1m-1 4h1" />
                        </svg>
                        All Schools
                    </a>

                    <a href="{{ route('admin.schools.create') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.schools.create') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 4v16m8-8H4" />
                        </svg>
                        Add New School
                    </a>

                    <a href="{{ route('admin.school-admins.index') }}"
                       class="{{ $item }} {{ request()->routeIs('admin.school-admins.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87" />
                        </svg>
                        School Admins
                    </a>
                </div>
            </div>

        @elseif (auth()->user()->role === 'school_admin')

            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">General</p>
                <div class="space-y-0.5">
                    <a href="{{ route('dashboard') }}"
                       class="{{ $item }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Academics</p>
                <div class="space-y-0.5">
                    <a href="{{ route('school-admin.teachers.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.teachers.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.083 12.083 0 0112 20a12.083 12.083 0 01-6.16-9.42L12 14z" />
                        </svg>
                        Teachers
                    </a>

                    <a href="{{ route('school-admin.students.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.students.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Students
                    </a>

                    <a href="{{ route('school-admin.timetables.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.timetables.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Timetable
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Communication</p>
                <div class="space-y-0.5">
                    <a href="{{ route('school-admin.notices.index') }}"
                       class="{{ $item }} {{ request()->routeIs('school-admin.notices.*') ? $active : $inactive }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M8 9h8M8 13h6m-9 8V5a2 2 0 012-2h10a2 2 0 012 2v16l-4-3-3 3-3-3-4 3z" />
                        </svg>
                        Notices
                    </a>
                </div>
            </div>

        @else

            <div class="space-y-0.5">
                <a href="{{ route('dashboard') }}"
                   class="{{ $item }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
            </div>

        @endif

    </nav>

    {{-- User footer --}}
    <div class="border-t border-gray-100 px-4 py-4">
        <div class="flex items-center gap-3 px-2">
            <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-sm font-semibold text-gray-600">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
            </div>
        </div>
    </div>

</aside>