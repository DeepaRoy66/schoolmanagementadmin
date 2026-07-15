<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Announcements
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="flex items-center gap-2 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-xl text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            @php
                $isSuperAdmin = auth()->user()->role === 'super_admin';
            @endphp

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73s-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Sent Announcements</p>
                            <p class="text-xs text-gray-400">{{ $announcements->total() }} {{ Str::plural('announcement', $announcements->total()) }} sent to all schools</p>
                        </div>
                    </div>

                    @if ($isSuperAdmin)
                        <a href="{{ route('admin.announcements.create') }}"
                           class="inline-flex items-center justify-center gap-1.5 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800 active:scale-[0.98] transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            New Announcement
                        </a>
                    @endif
                </div>

                {{-- List --}}
                <div class="divide-y divide-gray-100">
                    @forelse ($announcements as $announcement)
                        <div class="p-6 flex gap-4">
                            @if ($announcement->image)
                                <img src="{{ $announcement->image_url }}"
                                     alt="{{ $announcement->title }}"
                                     class="w-20 h-20 rounded-xl object-cover border border-gray-100 shrink-0">
                            @else
                                <div class="w-20 h-20 rounded-xl bg-gray-50 flex items-center justify-center shrink-0">
                                    <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09" />
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $announcement->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ optional($announcement->sender)->name ?? 'Super Admin' }}
                                            · {{ $announcement->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if ($isSuperAdmin)
                                        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST"
                                              onsubmit="return confirm('Yo announcement delete garne?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition shrink-0"
                                                    title="Delete">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-2 whitespace-pre-line">{{ $announcement->message }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-16">
                            <div class="flex flex-col items-center gap-2 text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">No announcements sent yet.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if ($announcements->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $announcements->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>