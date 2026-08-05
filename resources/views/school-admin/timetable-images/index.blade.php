<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Timetable Images
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 bg-green-50 text-green-700 text-sm px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-sm text-gray-600">Latest uploaded timetable per class-section.</p>
                    <a href="{{ route('school-admin.timetable-images.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Upload Timetable
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Class</th>
                            <th class="py-2">Section</th>
                            <th class="py-2">Uploaded</th>
                            <th class="py-2">Preview</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestPerClassSection as $item)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $item->schoolClass?->name }}</td>
                                <td class="py-3">{{ $item->section?->name }}</td>
                                <td class="py-3 text-gray-500">{{ $item->created_at->diffForHumans() }}</td>
                                <td class="py-3">
                                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                        View
                                    </a>
                                </td>
                                <td class="py-3 text-right space-x-3">
                                    <a href="{{ route('school-admin.timetable-images.history', [$item->class_id, $item->section_id]) }}"
                                       class="text-gray-600 hover:underline">History</a>
                                    <form method="POST" action="{{ route('school-admin.timetable-images.destroy', $item) }}" class="inline"
                                          onsubmit="return confirm('Delete this timetable image?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">No timetable images uploaded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>