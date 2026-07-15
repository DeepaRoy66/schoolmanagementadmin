<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $school->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3 text-sm">
                <p><span class="font-medium text-gray-700">School Code:</span> {{ $school->school_code ?? '—' }}</p>
                <p><span class="font-medium text-gray-700">Address:</span> {{ $school->address ?? '—' }}</p>
                <p><span class="font-medium text-gray-700">License Status:</span> {{ ucfirst($school->license_status) }}</p>
                <p><span class="font-medium text-gray-700">License Start:</span> {{ $school->license_start ?? '—' }}</p>
                <p><span class="font-medium text-gray-700">License Expiry:</span> {{ $school->license_expiry ?? '—' }}</p>
                <p><span class="font-medium text-gray-700">Trial Ends At:</span> {{ $school->trial_ends_at ?? '—' }}</p>

                <div class="pt-4">
                    <a href="{{ route('admin.schools.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to list</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>