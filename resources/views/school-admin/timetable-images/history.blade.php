<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload History
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if($images->isEmpty())
                    <p class="text-gray-400">No uploads found.</p>
                @else
                    <p class="text-sm text-gray-600 mb-4">
                        {{ $images->first()->schoolClass?->name }} - {{ $images->first()->section?->name }}
                    </p>

                    <ul class="divide-y">
                        @foreach ($images as $image)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $image->file_name }}
                                    </a>
                                    <p class="text-gray-400 text-xs">{{ $image->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <form method="POST" action="{{ route('school-admin.timetable-images.destroy', $image) }}"
                                      onsubmit="return confirm('Delete this upload?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-sm hover:underline">Delete</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <a href="{{ route('school-admin.timetable-images.index') }}" class="inline-block mt-6 text-gray-600 text-sm hover:underline">
                    ← Back to list
                </a>
            </div>
        </div>
    </div>
</x-app-layout>