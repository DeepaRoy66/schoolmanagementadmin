<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Section</h2>
            <p class="text-sm text-gray-500 mt-0.5">Update section details</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm border border-gray-100 sm:rounded-xl p-6">

                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">Edit Section</p>
                        <p class="text-xs text-gray-500">Update the section name below</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('school-admin.sections.update', $section) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Section Name</label>
                        <input type="text" name="name" value="{{ old('name', $section->name) }}"
                               class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-400 @enderror"
                               placeholder="e.g. A" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Section
                        </button>
                        <a href="{{ route('school-admin.sections.index') }}" class="text-gray-600 text-sm font-medium hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>