<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Subjects
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 text-sm">Total subjects: {{ $subjects->total() }}</p>
                    <a href="{{ route('school-admin.subjects.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Subject
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Subject Name</th>
                            <th class="py-2">Subject Code</th>
                            <th class="py-2">Class</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $subject->subject_name }}</td>
                                <td class="py-3 text-gray-600">{{ $subject->subject_code }}</td>
                                <td class="py-3 text-gray-600">{{ $subject->schoolClass->name ?? '—' }}</td>
                                <td class="py-3 text-right space-x-2">
                                    <a href="{{ route('school-admin.subjects.edit', $subject) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('school-admin.subjects.destroy', $subject) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo Subject delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500">
                                    No subjects added.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $subjects->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>