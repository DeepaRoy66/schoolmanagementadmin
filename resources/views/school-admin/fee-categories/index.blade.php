<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fee Categories
        </h2>
    </x-slot>

    <div class="py-8" x-data="{ editId: null }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-4">
                <h1 class="text-xl font-bold text-gray-900">Fee Categories</h1>
                <a href="{{ route('school-admin.fee-categories.create') }}"
                   class="px-4 py-2 rounded bg-teal-600 text-white text-sm font-medium hover:bg-teal-700">
                    + Add Category
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-2 rounded bg-red-100 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Simple table --}}
            <div class="bg-white rounded border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-left">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Schedule</th>
                            <th class="px-4 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    @if ($category->is_recurring)
                                        Repeats {{ $category->recurring_interval ?? '' }}
                                    @else
                                        Pay once
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="editId = {{ $category->id }}"
                                            class="text-teal-600 hover:underline">Edit</button>
                                    <form action="{{ route('school-admin.fee-categories.destroy', $category) }}" method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Delete this fee category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit modal (kept inline since it's a quick edit, not a full page) --}}
                            <template x-if="editId === {{ $category->id }}">
                                <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
                                     @click.self="editId = null">
                                    <div class="bg-white rounded w-full max-w-sm p-5">
                                        <h3 class="font-semibold text-gray-900 mb-4">Edit Fee Category</h3>

                                        <form action="{{ route('school-admin.fee-categories.update', $category) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <label class="block text-xs text-gray-600 mb-1">Name</label>
                                            <input type="text" name="name" value="{{ $category->name }}"
                                                   class="w-full mb-3 rounded border-gray-300 text-sm" required>

                                            <label class="block text-xs text-gray-600 mb-1">Schedule</label>
                                            <select name="is_recurring" class="w-full mb-3 rounded border-gray-300 text-sm">
                                                <option value="0" {{ ! $category->is_recurring ? 'selected' : '' }}>Pay once</option>
                                                <option value="1" {{ $category->is_recurring ? 'selected' : '' }}>Repeats</option>
                                            </select>

                                            <label class="block text-xs text-gray-600 mb-1">Repeat every</label>
                                            <select name="recurring_interval" class="w-full mb-4 rounded border-gray-300 text-sm">
                                                <option value="">—</option>
                                                <option value="monthly" {{ $category->recurring_interval === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="yearly" {{ $category->recurring_interval === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                            </select>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="editId = null"
                                                        class="px-3 py-1.5 text-sm text-gray-600">Cancel</button>
                                                <button type="submit"
                                                        class="px-3 py-1.5 text-sm rounded bg-teal-600 text-white">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400">No fee categories added yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>