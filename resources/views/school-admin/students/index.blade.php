<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Students
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
                    <p class="text-gray-600 text-sm">Total students: {{ $students->total() }}</p>
                    <a href="{{ route('school-admin.students.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Student
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Name</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Class</th>
                            <th class="py-2">Roll No.</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $student->name }}</td>
                                <td class="py-3 text-gray-600">{{ $student->email }}</td>
                                <td class="py-3 text-gray-600">{{ $student->class ?? '—' }}</td>
                                <td class="py-3 text-gray-600">{{ $student->roll_number ?? '—' }}</td>
                                <td class="py-3 text-right space-x-2">
                                    <a href="{{ route('school-admin.students.edit', $student) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('school-admin.students.destroy', $student) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yo Student delete garne?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-500">
                                    Kunai student thapiyeko chaina.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $students->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>