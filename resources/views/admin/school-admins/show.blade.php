<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $schoolAdmin->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3 text-sm">
                <p><span class="font-medium text-gray-700">Email:</span> {{ $schoolAdmin->email }}</p>
                <p><span class="font-medium text-gray-700">School:</span> {{ $schoolAdmin->school->name ?? '—' }}</p>
                <p><span class="font-medium text-gray-700">Role:</span> {{ $schoolAdmin->role }}</p>

                <div class="pt-4">
                    <a href="{{ route('admin.school-admins.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to list</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>