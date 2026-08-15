<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Emergency Contact</h2>
    </x-slot>

    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-slate-800">
                Edit Emergency Contact
            </h1>
            <a href="{{ route('school-admin.emergency-contacts.index') }}"
               class="text-sm text-slate-500 hover:text-slate-700">
                &larr; Back to list
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                Please fix the errors below and try again.
            </div>
        @endif

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 max-w-xl">
            <form action="{{ route('school-admin.emergency-contacts.update', $emergencyContact) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $emergencyContact->name) }}"
                           class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
                           required>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="designation" class="block text-sm font-medium text-slate-700 mb-1">Designation</label>
                    <input type="text" id="designation" name="designation" value="{{ old('designation', $emergencyContact->designation) }}"
                           class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
                           required>
                    @error('designation')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $emergencyContact->phone) }}"
                           class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]"
                           required>
                    @error('phone')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="sort_order" class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $emergencyContact->sort_order) }}"
                           class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1e4ed8]/30 focus:border-[#1e4ed8]">
                    @error('sort_order')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-md bg-[#1e4ed8] hover:bg-[#1e3a8a] text-white text-sm font-medium px-4 py-2 transition-colors">
                        Update
                    </button>
                    <a href="{{ route('school-admin.emergency-contacts.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium px-4 py-2 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>