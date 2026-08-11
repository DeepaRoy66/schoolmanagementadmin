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
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6 min-w-0">

            {{-- Breadcrumb row (matches reference: page title left, breadcrumb right) --}}
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-blue-600">Student</h1>
                <div class="text-sm text-slate-400">
                    <a href="{{ route('school-admin.students.index') }}" class="hover:text-slate-600">Dashboard</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('school-admin.students.index') }}" class="hover:text-slate-600">Students</a>
                    <span class="mx-1">/</span>
                    <span class="text-slate-600">{{ $student->full_name }}</span>
                </div>
            </div>

            @if (session('status'))
                <div class="flex items-center gap-2 border border-emerald-200 bg-emerald-50 text-emerald-700 rounded-md px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Main two-column layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- LEFT: Avatar / summary card --}}
                <div class="lg:col-span-1 space-y-6">

                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm px-6 py-8 flex flex-col items-center text-center">
                        <div class="w-28 h-28 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-4xl font-semibold ring-4 ring-blue-50">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}
                        </div>
                        <h2 class="mt-4 text-base font-semibold text-slate-900">{{ $student->full_name }}</h2>

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
                        <span class="mt-2 inline-block px-3 py-1 rounded-md text-xs font-medium {{ $statusColors[$student->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                        </span>
                    </div>

                    {{-- Class / roll quick facts card, styled like the "wallet balance" card in the reference --}}
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm px-6 py-6 text-center">
                        <p class="text-xs font-semibold text-slate-400 tracking-wide uppercase">Class</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $student->schoolClass->name ?? '—' }}</p>

                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                            <span class="text-slate-400">Roll No.</span>
                            <span class="font-medium text-slate-700">{{ $student->roll_number ?? '—' }}</span>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <a href="{{ route('school-admin.students.edit', $student) }}"
                               class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">
                                Edit
                            </a>
                            <a href="{{ route('school-admin.students.index') }}"
                               class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-md transition-colors">
                                Back
                            </a>
                        </div>
                    </div>

                </div>

                {{-- RIGHT: Personal information panel --}}
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm px-8 py-6">
                        <p class="text-xs font-semibold text-slate-500 tracking-wide uppercase mb-4">Personal Information</p>

                        <div class="divide-y divide-slate-100">
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500">Full Name</span>
                                <span class="text-sm font-medium text-slate-800">{{ $student->full_name }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500">Email</span>
                                <span class="text-sm font-medium text-slate-800">{{ $student->email ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500">Phone</span>
                                <span class="text-sm font-medium text-slate-800">{{ $student->phone ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500">Telephone No.</span>
                                <span class="text-sm font-medium text-slate-800">{{ $student->telephone_no ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500">Parent Name</span>
                                <span class="text-sm font-medium text-slate-800">{{ $student->parent_name ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500">Parent Phone</span>
                                <span class="text-sm font-medium text-slate-800">{{ $student->parent_phone ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500">Account Status</span>
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium {{ $statusColors[$student->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Emergency contact panel, same row style as above --}}
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm px-8 py-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs font-semibold text-slate-500 tracking-wide uppercase">Emergency Contact</p>
                        </div>

                        @if ($student->emergency_contact_name || $student->emergency_contact_phone)
                            <div class="divide-y divide-slate-100">
                                <div class="flex items-center justify-between py-3">
                                    <span class="text-sm text-slate-500">Contact Name</span>
                                    <span class="text-sm font-medium text-slate-800">{{ $student->emergency_contact_name ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3">
                                    <span class="text-sm text-slate-500">Relationship</span>
                                    <span class="text-sm font-medium text-slate-800">{{ $student->emergency_contact_relation ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3">
                                    <span class="text-sm text-slate-500">Phone</span>
                                    <span class="text-sm font-semibold text-red-600">{{ $student->emergency_contact_phone ?? '—' }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-slate-400">
                                Emergency contact thapieko chaina.
                                <a href="{{ route('school-admin.students.edit', $student) }}" class="text-blue-600 hover:underline">Add garnus</a>.
                            </p>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>