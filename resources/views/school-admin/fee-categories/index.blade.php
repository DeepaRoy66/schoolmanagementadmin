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

            {{-- Box-card list instead of a plain table --}}
            <div class="space-y-3">
                @forelse ($categories as $category)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200 transition-all p-5 flex items-center justify-between">

                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#2dd4bf]/10 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#0f9c8c]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $category->name }}</p>
                                <div class="mt-1">
                                    @if ($category->is_recurring)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-[#2dd4bf]/10 text-[#0f9c8c]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
                                            Repeats {{ $category->recurring_interval ? $category->recurring_interval : '' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            Pay once
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="editId = {{ $category->id }}"
                                    title="Edit"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="{{ route('school-admin.fee-categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Delete this fee category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14z"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

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

                                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Payment schedule</label>
                                    <div class="grid grid-cols-2 gap-2 mb-5">
                                        <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg border border-gray-200 cursor-pointer has-[:checked]:border-[#2dd4bf] has-[:checked]:bg-[#2dd4bf]/5">
                                            <input type="radio" name="is_recurring" value="0"
                                                   {{ ! $category->is_recurring ? 'checked' : '' }}
                                                   class="text-[#2dd4bf] focus:ring-[#2dd4bf]">
                                            <span class="text-sm text-gray-700">Pay once</span>
                                        </label>
                                        <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg border border-gray-200 cursor-pointer has-[:checked]:border-[#2dd4bf] has-[:checked]:bg-[#2dd4bf]/5">
                                            <input type="radio" name="is_recurring" value="1"
                                                   {{ $category->is_recurring ? 'checked' : '' }}
                                                   class="text-[#2dd4bf] focus:ring-[#2dd4bf]">
                                            <span class="text-sm text-gray-700">Repeats</span>
                                        </label>
                                    </div>

                                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Repeat every</label>
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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-14 text-center">
                        <p class="text-gray-400 text-sm">No fee categories added yet</p>
                    </div>
                @endforelse
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

                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Payment schedule</label>
                    <div class="grid grid-cols-2 gap-2 mb-5">
                        <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg border border-gray-200 cursor-pointer has-[:checked]:border-[#2dd4bf] has-[:checked]:bg-[#2dd4bf]/5">
                            <input type="radio" name="is_recurring" value="0"
                                   {{ ! old('is_recurring') ? 'checked' : '' }}
                                   class="text-[#2dd4bf] focus:ring-[#2dd4bf]">
                            <span class="text-sm text-gray-700">Pay once</span>
                        </label>
                        <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg border border-gray-200 cursor-pointer has-[:checked]:border-[#2dd4bf] has-[:checked]:bg-[#2dd4bf]/5">
                            <input type="radio" name="is_recurring" value="1"
                                   {{ old('is_recurring') ? 'checked' : '' }}
                                   class="text-[#2dd4bf] focus:ring-[#2dd4bf]">
                            <span class="text-sm text-gray-700">Repeats</span>
                        </label>
                    </div>

                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Repeat every</label>
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