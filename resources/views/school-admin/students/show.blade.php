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
            <span class="text-slate-400">{{ $student->full_name }}</span>
        </div>
    </x-slot>

    <div class="py-8 overflow-x-hidden">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6 min-w-0">

            @if (session('status'))
                <div class="flex items-center gap-2 border border-emerald-200 bg-emerald-50 text-emerald-700 rounded-md px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                {{-- Profile header --}}
                <div class="flex items-center gap-4 px-8 py-6">
                    @if ($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}"
                             class="w-14 h-14 rounded-full object-cover border border-slate-200 flex-shrink-0"
                             alt="{{ $student->full_name }}">
                    @else
                        <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-lg font-semibold flex-shrink-0">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">{{ $student->full_name }}</h2>
                        <p class="text-sm text-slate-400">Student profile</p>
                    </div>

                    @php
                        $statusColors = [
                            'active' => 'bg-green-100 text-green-700',
                            'inactive' => 'bg-gray-100 text-gray-600',
                            'dropped_out' => 'bg-red-100 text-red-700',
                        ];
                        $statusLabels = [
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'dropped_out' => 'Dropped Out',
                        ];
                    @endphp
                    <span class="ml-auto px-3 py-1.5 rounded-md text-xs font-medium {{ $statusColors[$student->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                    </span>
                </div>

                <div class="mx-8 border-t border-slate-100"></div>

                {{-- Info grid --}}
                <div class="px-8 py-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="border border-slate-200 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Email</p>
                        <p class="text-sm text-slate-800">{{ $student->email }}</p>
                    </div>

                    <div class="border border-slate-200 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Phone</p>
                        <p class="text-sm text-slate-800">{{ $student->phone ?? '—' }}</p>
                    </div>

                    <div class="border border-slate-200 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Class</p>
                        <p class="text-sm text-slate-800">{{ $student->schoolClass->name ?? '—' }}</p>
                    </div>

                    <div class="border border-slate-200 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Roll Number</p>
                        <p class="text-sm text-slate-800">{{ $student->roll_number ?? '—' }}</p>
                    </div>

                    <div class="border border-slate-200 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Parent Name</p>
                        <p class="text-sm text-slate-800">{{ $student->parent_name ?? '—' }}</p>
                    </div>

                    <div class="border border-slate-200 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Parent Phone</p>
                        <p class="text-sm text-slate-800">{{ $student->parent_phone ?? '—' }}</p>
                    </div>

                    <div class="border border-slate-200 rounded-lg px-4 py-3 md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Telephone No.</p>
                        <p class="text-sm text-slate-800">{{ $student->telephone_no ?? '—' }}</p>
                    </div>

                </div>

                <div class="mx-8 border-t border-slate-100"></div>

                {{-- Emergency Contacts --}}
                <div class="px-8 py-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs font-semibold text-slate-500 tracking-wide uppercase">Emergency Contacts</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-red-100 bg-red-50/40 rounded-lg px-4 py-3">
                            <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Mother's Contact Number</p>
                            <p class="text-sm text-slate-800">
                                {{ $student->mother_phone ?? '—' }}
                                @if ($student->mother_name)
                                    <span class="text-slate-400 text-xs">({{ $student->mother_name }})</span>
                                @endif
                            </p>
                        </div>

                        <div class="border border-red-100 bg-red-50/40 rounded-lg px-4 py-3">
                            <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Father's Contact Number</p>
                            <p class="text-sm text-slate-800">
                                {{ $student->father_phone ?? '—' }}
                                @if ($student->father_name)
                                    <span class="text-slate-400 text-xs">({{ $student->father_name }})</span>
                                @endif
                            </p>
                        </div>

                        <div class="border border-red-100 bg-red-50/40 rounded-lg px-4 py-3">
                            <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Local Guardian's Contact Number</p>
                            <p class="text-sm text-slate-800">
                                {{ $student->local_guardian_phone ?? '—' }}
                                @if ($student->local_guardian_name)
                                    <span class="text-slate-400 text-xs">({{ $student->local_guardian_name }})</span>
                                @endif
                            </p>
                        </div>

                        <div class="border border-red-100 bg-red-50/40 rounded-lg px-4 py-3">
                            <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase mb-1">Student's Own Contact Number</p>
                            <p class="text-sm text-slate-800">{{ $student->phone ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mx-8 border-t border-slate-100"></div>

                {{-- Back --}}
                <div class="px-8 py-6">
                    <a href="{{ route('school-admin.students.index') }}"
                       class="inline-flex items-center gap-1.5 text-slate-600 text-sm font-medium hover:text-slate-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to list
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>