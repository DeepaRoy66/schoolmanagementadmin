<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subjects</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage subjects for each class</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 flex items-center gap-2 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm border border-gray-100 sm:rounded-xl overflow-hidden">

                <div class="flex justify-between items-center px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </span>
                        <p class="text-sm font-medium text-gray-700">{{ $subjects->total() }} {{ Str::plural('Subject', $subjects->total()) }}</p>
                    </div>
                    <a href="{{ route('school-admin.subjects.create') }}"
                       class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Subject
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                            <th class="py-3 px-6 font-medium">Subject Name</th>
                            <th class="py-3 px-6 font-medium">Subject Code</th>
                            <th class="py-3 px-6 font-medium">Class</th>
                            <th class="py-3 px-6 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($subjects as $subject)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="font-medium text-gray-800">{{ $subject->subject_name }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $subject->subject_code }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-gray-600">{{ $subject->schoolClass->name ?? '—' }}</td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('school-admin.subjects.edit', $subject) }}"
                                           class="inline-flex items-center gap-1 bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-yellow-600 transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('school-admin.subjects.destroy', $subject) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yo Subject delete garne?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-700 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <p class="text-gray-500 text-sm">No subjects added yet.</p>
                                        <a href="{{ route('school-admin.subjects.create') }}" class="text-indigo-600 text-sm font-medium hover:underline">Add your first subject</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($subjects->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $subjects->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>