<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit School: {{ $school->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Breadcrumb + back link --}}
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">
                    Schools <span class="text-gray-400 font-normal">&raquo; Edit {{ $school->name }}</span>
                </h1>
                <a href="{{ route('admin.schools.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Schools
                </a>
            </div>

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    Please fix the errors below and try again.
                </div>
            @endif

            {{-- Hero banner card --}}
            <div class="rounded-2xl bg-blue-600 px-6 py-6 shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-xl font-bold shrink-0">
                        {{ Str::substr($school->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-white font-semibold text-lg">{{ $school->name }}</p>
                        <p class="text-white/70 text-xs font-mono">{{ $school->school_code ?? 'No code assigned' }}</p>
                    </div>
                    <div class="ml-auto">
                        @if ($school->license_status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 backdrop-blur text-white rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                                Active
                            </span>
                        @elseif ($school->license_status === 'trial')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 backdrop-blur text-white rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-300"></span>
                                Trial
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 backdrop-blur text-white rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-300"></span>
                                Expired
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.schools.update', $school) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Basic Information --}}
                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-blue-50">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 0v10.5a1.5 1.5 0 01-1.5 1.5H6a1.5 1.5 0 01-1.5-1.5V9" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-800">Basic Information</h2>
                            <p class="text-xs text-gray-400">Core details used to identify this school</p>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">

                        {{-- School Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                School Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $school->name) }}"
                                   class="w-full border rounded-xl px-3.5 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                          {{ $errors->has('name') ? 'border-rose-400 focus:ring-2 focus:ring-rose-100 focus:border-rose-500' : 'border-gray-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}"
                                   required>
                            @error('name')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- School Code --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">School Code</label>
                            <input type="text" name="school_code" value="{{ old('school_code', $school->school_code) }}"
                                   class="w-full border rounded-xl px-3.5 py-2.5 text-sm text-gray-700 font-mono placeholder-gray-400 outline-none transition
                                          {{ $errors->has('school_code') ? 'border-rose-400 focus:ring-2 focus:ring-rose-100 focus:border-rose-500' : 'border-gray-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}">
                            @error('school_code')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                            <input type="text" name="address" value="{{ old('address', $school->address) }}"
                                   class="w-full border rounded-xl px-3.5 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                          {{ $errors->has('address') ? 'border-rose-400 focus:ring-2 focus:ring-rose-100 focus:border-rose-500' : 'border-gray-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}">
                            @error('address')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- License Details --}}
                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-blue-50">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-800">License Details</h2>
                            <p class="text-xs text-gray-400">Controls this school's access and billing status</p>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">

                        {{-- License Status as colorful badge-radio group --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                License Status <span class="text-rose-500">*</span>
                            </label>
                            <div class="flex flex-wrap gap-3">
                                @php
                                    // NOTE: Tailwind classes below are written out in full per status (not built
                                    // via string interpolation) because Tailwind's JIT compiler scans source files
                                    // for literal class names — a class assembled at runtime like
                                    // "peer-checked:border-{$color}-500" will never be generated/included in the
                                    // compiled CSS and the badge would silently render with no color.
                                    $statusOptions = [
                                        'trial' => [
                                            'label' => 'Trial',
                                            'dot' => 'bg-amber-500',
                                            'peer' => 'peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700',
                                        ],
                                        'active' => [
                                            'label' => 'Active',
                                            'dot' => 'bg-emerald-500',
                                            'peer' => 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700',
                                        ],
                                        'expired' => [
                                            'label' => 'Expired',
                                            'dot' => 'bg-rose-500',
                                            'peer' => 'peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-700',
                                        ],
                                    ];
                                    $selectedStatus = old('license_status', $school->license_status);
                                @endphp
                                @foreach ($statusOptions as $value => $opt)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="license_status" value="{{ $value }}"
                                               class="peer sr-only"
                                               {{ $selectedStatus == $value ? 'checked' : '' }} required>
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border-2 text-sm font-semibold transition
                                                      border-gray-200 text-gray-500 {{ $opt['peer'] }}">
                                            <span class="w-2 h-2 rounded-full {{ $opt['dot'] }}"></span>
                                            {{ $opt['label'] }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('license_status')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-5 pt-2">

                            {{-- License Start --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">License Start</label>
                                {{-- ->format('Y-m-d') is required: HTML date inputs only recognize that exact
                                     format. Passing a Carbon instance/date-with-time string straight in makes the
                                     browser silently show the field as empty, which is why the picked date "disappeared". --}}
                                <input type="date" name="license_start"
                                       value="{{ old('license_start', optional($school->license_start)->format('Y-m-d')) }}"
                                       class="w-full border rounded-xl px-3.5 py-2.5 text-sm text-gray-700 outline-none transition
                                              {{ $errors->has('license_start') ? 'border-rose-400 focus:ring-2 focus:ring-rose-100 focus:border-rose-500' : 'border-gray-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}">
                                @error('license_start')
                                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- License Expiry --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">License Expiry</label>
                                <input type="date" name="license_expiry"
                                       value="{{ old('license_expiry', optional($school->license_expiry)->format('Y-m-d')) }}"
                                       class="w-full border rounded-xl px-3.5 py-2.5 text-sm text-gray-700 outline-none transition
                                              {{ $errors->has('license_expiry') ? 'border-rose-400 focus:ring-2 focus:ring-rose-100 focus:border-rose-500' : 'border-gray-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}">
                                @error('license_expiry')
                                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Trial Ends At --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Trial Ends At</label>
                                <input type="date" name="trial_ends_at"
                                       value="{{ old('trial_ends_at', optional($school->trial_ends_at)->format('Y-m-d')) }}"
                                       class="w-full border rounded-xl px-3.5 py-2.5 text-sm text-gray-700 outline-none transition
                                              {{ $errors->has('trial_ends_at') ? 'border-rose-400 focus:ring-2 focus:ring-rose-100 focus:border-rose-500' : 'border-gray-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}">
                                <p class="text-xs text-gray-400 mt-1.5">If this is a trial, set the end date here.</p>
                                @error('trial_ends_at')
                                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 active:scale-[0.98] transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Update School
                    </button>
                    <a href="{{ route('admin.schools.index') }}"
                       class="text-gray-500 text-sm font-medium hover:text-gray-700 transition px-4 py-2.5">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>