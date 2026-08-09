<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add School Admin
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Breadcrumb + back link --}}
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">
                    School Admins <span class="text-gray-400 font-normal">&raquo; Add School Admin</span>
                </h1>
                <a href="{{ route('admin.school-admins.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to School Admins
                </a>
            </div>

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    Please fix the errors below and try again.
                </div>
            @endif

            <form method="POST" action="{{ route('admin.school-admins.store') }}" class="space-y-6">
                @csrf

                {{-- Admin Details --}}
                <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-sm font-semibold text-gray-800">Admin Details</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Basic info and which school this admin manages.</p>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">

                        {{-- Which School --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Which School? <span class="text-red-500">*</span>
                            </label>
                            <select name="school_id"
                                    class="w-full border rounded-lg px-3.5 py-2.5 text-sm text-gray-700 outline-none transition bg-white
                                           {{ $errors->has('school_id') ? 'border-red-400 focus:ring-2 focus:ring-red-100 focus:border-red-500' : 'border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}"
                                    required>
                                <option value="">-- Select School --</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Admin Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Admin Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full border rounded-lg px-3.5 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                          {{ $errors->has('name') ? 'border-red-400 focus:ring-2 focus:ring-red-100 focus:border-red-500' : 'border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}"
                                   required>
                            @error('name')
                                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full border rounded-lg px-3.5 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                          {{ $errors->has('email') ? 'border-red-400 focus:ring-2 focus:ring-red-100 focus:border-red-500' : 'border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}"
                                   required>
                            @error('email')
                                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Security --}}
                <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-sm font-semibold text-gray-800">Security</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Set a login password for this admin.</p>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password"
                                   class="w-full border rounded-lg px-3.5 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                          {{ $errors->has('password') ? 'border-red-400 focus:ring-2 focus:ring-red-100 focus:border-red-500' : 'border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500' }}"
                                   placeholder="••••••••" required>
                            @error('password')
                                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 active:scale-[0.98] transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Save School Admin
                    </button>
                    <a href="{{ route('admin.school-admins.index') }}"
                       class="text-gray-500 text-sm font-medium hover:text-gray-700 transition px-4 py-2.5">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>