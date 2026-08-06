<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Classes
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-3">
                <a href="{{ route('school-admin.classes.index') }}" class="hover:text-gray-700">Classes</a>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-700 font-medium">Edit Class</span>
            </div>

            {{-- Page header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Edit Class</h1>
                <p class="text-sm text-gray-500 mt-1">Update the class name or its assigned sections.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 border border-red-200 p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form card --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('school-admin.classes.update', $class) }}" id="edit-class-form">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-5 border-b border-gray-200">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Class Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $class->name) }}"
                               class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500"
                               placeholder="e.g. Class 10" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="px-6 py-5 border-b border-gray-200">
                        <p class="block text-sm font-medium text-gray-700 mb-3">Sections</p>

                        @if ($sections->isEmpty())
                            <p class="text-sm text-gray-400 italic">No sections exist yet. You can add sections later and assign them to this class.</p>
                        @else
                            @php
                                $assignedIds = old('section_ids', $class->sections->pluck('id')->toArray());
                            @endphp
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                @foreach ($sections as $section)
                                    <label class="flex items-center gap-2 px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-700
                                                   has-[:checked]:border-sky-400 has-[:checked]:bg-sky-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="section_ids[]" value="{{ $section->id }}"
                                               {{ collect($assignedIds)->contains($section->id) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-sky-500 focus:ring-sky-500">
                                        {{ $section->name }}
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        @error('section_ids')
                            <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                        @enderror
                        @error('section_ids.*')
                            <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                </form>

                {{-- Footer sits outside the update form so the Delete form isn't nested inside it --}}
                <div class="px-6 py-4 flex items-center justify-between gap-3 bg-gray-50 rounded-b-lg">
                    <form action="{{ route('school-admin.classes.destroy', $class) }}" method="POST"
                          onsubmit="return confirm('Yo class delete garne?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                            Delete Class
                        </button>
                    </form>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('school-admin.classes.index') }}"
                           class="text-sm font-medium text-gray-700 px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-100 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" form="edit-class-form"
                                class="text-sm font-medium text-white bg-sky-500 px-4 py-2 rounded-md hover:bg-sky-600 transition-colors shadow-sm shadow-sky-500/20">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>