<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Classes</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 text-sm">Total classes: {{ $classes->count() }}</p>
                    <a href="{{ route('school-admin.classes.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Class
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Class Name</th>
                            <th class="py-2">Sections</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $class->name }}</td>
                                <td class="py-3 text-gray-600">
                                    @forelse ($class->sections as $section)
                                        <span class="px-2 py-1 bg-gray-100 rounded text-xs mr-1">{{ $section->name }}</span>
                                    @empty
                                        <span class="text-gray-400 text-xs">No sections yet</span>
                                    @endforelse
                                </td>
                                <td class="py-3 text-right">
                                    <form action="{{ route('school-admin.classes.destroy', $class) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo class delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-500">No class added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
</div>
</x-app-layout>