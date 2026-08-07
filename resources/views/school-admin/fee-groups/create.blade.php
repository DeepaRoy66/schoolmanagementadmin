<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6-6a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Fee Groups
            </span>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">Add Fee Group</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-100 bg-blue-50/60">
                    <p class="text-xs font-semibold text-blue-700 mb-1.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tips
                    </p>
                    <ul class="list-disc list-inside text-xs text-blue-700/90 space-y-0.5">
                        <li>Fee Group Name and Fee Group Code are compulsory.</li>
                        <li>Write remarks for its description.</li>
                    </ul>
                </div>

                @if ($errors->any())
                    <div class="mx-6 mt-5 border border-red-200 bg-red-50 rounded-md px-4 py-3">
                        <p class="text-sm font-medium text-red-700 mb-1">Form submit hudaina, yi error haru fix garnus:</p>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('school-admin.fee-groups.store') }}">
                    @csrf

                    <div class="px-6 pt-6">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Fee group details</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Fee Group Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400"
                                       placeholder="e.g. Miscellaneous Fee" required>
                                @error('name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Fee Group Code <span class="text-red-500">*</span></label>
                                <input type="text" name="code" value="{{ old('code') }}"
                                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400"
                                       placeholder="e.g. TF01" required>
                                @error('code')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Remarks</label>
                            <textarea name="remarks" rows="3"
                                      class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="inline-flex items-center gap-2 border border-slate-200 rounded-md px-3 py-2 hover:bg-slate-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/30">
                                <span class="text-sm text-slate-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-6 py-5 mt-2 border-t border-slate-100 bg-slate-50/50">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save
                        </button>
                        <a href="{{ route('school-admin.fee-groups.index') }}"
                           class="inline-flex items-center gap-1.5 border border-slate-300 text-slate-600 px-4 py-2.5 rounded-md text-sm font-medium hover:bg-slate-100 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>