<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fee Categories
        </h2>
    </x-slot>

    <div class="py-8" x-data="{ showAddModal: false, editId: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Fee Categories</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Manage recurring and one-time fee types</p>
                </div>
                <button @click="showAddModal = true"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#2dd4bf] text-white text-sm font-medium shadow-sm hover:bg-[#25b8a5] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Add Category
                </button>
            </div>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 text-red-700 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="text-left px-6 py-3">Name</th>
                            <th class="text-left px-6 py-3">Recurring</th>
                            <th class="text-left px-6 py-3">Interval</th>
                            <th class="text-right px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $category->name }}</td>
                                <td class="px-6 py-4">
                                    @if ($category->is_recurring)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#2dd4bf]/10 text-[#0f9c8c]">
                                            Recurring
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">One-time</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $category->recurring_interval ? ucfirst($category->recurring_interval) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <button @click="editId = {{ $category->id }}" class="text-gray-500 hover:text-gray-900 font-medium hover:underline">Edit</button>
                                    <form action="{{ route('school-admin.fee-categories.destroy', $category) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Delete this fee category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit modal for this row --}}
                            <template x-if="editId === {{ $category->id }}">
                                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                                     x-transition.opacity
                                     @click.self="editId = null" @keydown.escape.window="editId = null">
                                    <div class="bg-white rounded-2xl w-full max-w-sm shadow-xl overflow-hidden"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100">
                                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                                            <h3 class="text-base font-semibold text-gray-900">Edit Fee Category</h3>
                                            <button type="button" @click="editId = null" class="text-gray-400 hover:text-gray-600 p-1 -mr-1 rounded-lg hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                            </button>
                                        </div>

                                        <form action="{{ route('school-admin.fee-categories.update', $category) }}" method="POST" class="px-5 py-4">
                                            @csrf
                                            @method('PUT')

                                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Name</label>
                                            <input type="text" name="name" value="{{ $category->name }}"
                                                   class="w-full mb-4 rounded-lg border-gray-300 text-sm focus:ring-[#2dd4bf] focus:border-[#2dd4bf]" required>

                                            <label class="flex items-center justify-between mb-4 px-3 py-2.5 rounded-lg bg-gray-50 cursor-pointer">
                                                <span class="text-sm text-gray-700">Recurring fee</span>
                                                <input type="hidden" name="is_recurring" value="0">
                                                <input type="checkbox" name="is_recurring" value="1"
                                                       {{ $category->is_recurring ? 'checked' : '' }}
                                                       class="rounded text-[#2dd4bf] focus:ring-[#2dd4bf] w-4 h-4">
                                            </label>

                                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Recurring Interval</label>
                                            <select name="recurring_interval"
                                                    class="w-full mb-5 rounded-lg border-gray-300 text-sm focus:ring-[#2dd4bf] focus:border-[#2dd4bf]">
                                                <option value="">—</option>
                                                <option value="monthly" {{ $category->recurring_interval === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="yearly" {{ $category->recurring_interval === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                            </select>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="editId = null"
                                                        class="px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">Cancel</button>
                                                <button type="submit"
                                                        class="px-4 py-2 rounded-lg text-sm bg-[#2dd4bf] text-white font-medium hover:bg-[#25b8a5] transition-colors">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                    No fee categories added yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Add modal --}}
        <div x-show="showAddModal" x-cloak
             x-transition.opacity
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             @click.self="showAddModal = false" @keydown.escape.window="showAddModal = false">
            <div x-show="showAddModal"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-2xl w-full max-w-sm shadow-xl overflow-hidden">

                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Add Fee Category</h3>
                    <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 p-1 -mr-1 rounded-lg hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('school-admin.fee-categories.store') }}" method="POST" class="px-5 py-4">
                    @csrf

                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Tuition Fee"
                           class="w-full mb-1 rounded-lg border-gray-300 text-sm focus:ring-[#2dd4bf] focus:border-[#2dd4bf]" required>
                    @error('name')
                        <p class="text-red-600 text-xs mb-3">{{ $message }}</p>
                    @enderror
                    @if(!$errors->has('name'))
                        <div class="mb-4"></div>
                    @endif

                    <label class="flex items-center justify-between mb-4 px-3 py-2.5 rounded-lg bg-gray-50 cursor-pointer">
                        <span class="text-sm text-gray-700">Recurring fee</span>
                        <input type="hidden" name="is_recurring" value="0">
                        <input type="checkbox" name="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}
                               class="rounded text-[#2dd4bf] focus:ring-[#2dd4bf] w-4 h-4">
                    </label>

                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Recurring Interval</label>
                    <select name="recurring_interval"
                            class="w-full mb-1 rounded-lg border-gray-300 text-sm focus:ring-[#2dd4bf] focus:border-[#2dd4bf]">
                        <option value="">—</option>
                        <option value="monthly" {{ old('recurring_interval') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('recurring_interval') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    @error('recurring_interval')
                        <p class="text-red-600 text-xs mb-3">{{ $message }}</p>
                    @enderror
                    @if(!$errors->has('recurring_interval'))
                        <div class="mb-4"></div>
                    @endif

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showAddModal = false"
                                class="px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">Cancel</button>
                        <button type="submit"
                                class="px-4 py-2 rounded-lg text-sm bg-[#2dd4bf] text-white font-medium hover:bg-[#25b8a5] transition-colors">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>