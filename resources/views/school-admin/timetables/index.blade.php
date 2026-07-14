<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Timetable
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 text-sm">Total entries: {{ $timetables->total() }}</p>
                    <a href="{{ route('school-admin.timetables.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Timetable Entry
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Class</th>
                            <th class="py-2">Day</th>
                            <th class="py-2">Period</th>
                            <th class="py-2">Subject</th>
                            <th class="py-2">Teacher</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($timetables as $entry)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $entry->class }}</td>
                                <td class="py-3">{{ $entry->day }}</td>
                                <td class="py-3">{{ $entry->period }}</td>
                                <td class="py-3">{{ $entry->subject }}</td>
                                <td class="py-3 text-gray-600">{{ $entry->teacher->name ?? '—' }}</td>
                                <td class="py-3 text-right">
                                    <form action="{{ route('school-admin.timetables.destroy', $entry) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo entry delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-500">
                                    Kunai timetable entry thapiyeko chaina.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $timetables->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>