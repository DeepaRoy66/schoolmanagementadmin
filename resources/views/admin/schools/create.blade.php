<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New School
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <a href="{{ route('admin.schools.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Schools
            </a>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                <div class="px-8 pt-8 pb-2">
                    <h1 class="text-lg font-bold tracking-wider text-gray-800 uppercase">
                        School Registration Form
                    </h1>
                </div>

                <form method="POST" action="{{ route('admin.schools.store') }}" class="px-8 pb-8 pt-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-5">

                        {{-- School Name --}}
                        <div class="lg:col-span-2">
                            <label class="block text-sm text-gray-700 mb-1.5">School Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full border rounded-md px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                          {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}"
                                   placeholder="Name" required>
                            @error('name')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- School Code --}}
                        <div class="lg:col-span-2">
                            <label class="block text-sm text-gray-700 mb-1.5">School Code</label>
                            <input type="text" name="school_code" value="{{ old('school_code') }}"
                                   class="w-full border rounded-md px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                          {{ $errors->has('school_code') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}"
                                   placeholder="School Code">
                            <p class="text-xs text-gray-400 mt-1">If left blank, the system will auto-generate a school code.</p>
                            @error('school_code')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="lg:col-span-2">
                            <label class="block text-sm text-gray-700 mb-1.5">Address</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                   class="w-full border rounded-md px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                          {{ $errors->has('address') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}"
                                   placeholder="Address">
                            @error('address')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- License Status --}}
                        <div>
                            <label class="block text-sm text-gray-700 mb-1.5">License Status</label>
                            <div class="relative">
                                <select name="license_status"
                                        class="w-full appearance-none border rounded-md px-3 py-2.5 text-sm text-gray-700 outline-none transition bg-white
                                               {{ $errors->has('license_status') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}"
                                        required>
                                    <option value="trial" {{ old('license_status') == 'trial' ? 'selected' : '' }}>Trial</option>
                                    <option value="active" {{ old('license_status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expired" {{ old('license_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            @error('license_status')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- License Start --}}
                        <div>
                            <label class="block text-sm text-gray-700 mb-1.5">License Start</label>
                            <input type="date" name="license_start" value="{{ old('license_start') }}"
                                   class="w-full border rounded-md px-3 py-2.5 text-sm text-gray-700 outline-none transition
                                          {{ $errors->has('license_start') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}">
                            @error('license_start')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- License Expiry --}}
                        <div>
                            <label class="block text-sm text-gray-700 mb-1.5">License Expiry</label>
                            <input type="date" name="license_expiry" value="{{ old('license_expiry') }}"
                                   class="w-full border rounded-md px-3 py-2.5 text-sm text-gray-700 outline-none transition
                                          {{ $errors->has('license_expiry') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}">
                            @error('license_expiry')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Trial Ends At --}}
                        <div>
                            <label class="block text-sm text-gray-700 mb-1.5">Trial Ends At</label>
                            <input type="date" name="trial_ends_at" value="{{ old('trial_ends_at') }}"
                                   class="w-full border rounded-md px-3 py-2.5 text-sm text-gray-700 outline-none transition
                                          {{ $errors->has('trial_ends_at') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}">
                            <p class="text-xs text-gray-400 mt-1">If this is a trial period, set the end date here.</p>
                            @error('trial_ends_at')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                        <button type="submit"
                                class="bg-teal-600 text-white px-6 py-2.5 rounded-md text-sm font-medium hover:bg-teal-700 active:scale-[0.98] transition">
                            Save School
                        </button>
                        <a href="{{ route('admin.schools.index') }}"
                           class="text-gray-500 text-sm hover:text-gray-700 transition">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>