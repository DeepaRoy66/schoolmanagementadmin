<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notices
        </h2>
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
                    <p class="text-gray-600 text-sm">Total notices: {{ $notices->total() }}</p>
                    <a href="{{ route('school-admin.notices.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Post Notice
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse ($notices as $notice)
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-start">
                                <h3 class="font-semibold text-gray-800">{{ $notice->title }}</h3>
                                <form action="{{ route('school-admin.notices.destroy', $notice) }}" method="POST"
                                      onsubmit="return confirm('Yo notice delete garne?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-xs">Delete</button>
                                </form>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">{{ $notice->message }}</p>
                            <p class="text-gray-400 text-xs mt-2">{{ $notice->created_at->format('M d, Y') }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-6">Kunai notice post gareko chaina.</p>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $notices->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>