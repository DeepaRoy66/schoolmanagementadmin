<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notices
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">All Notices</h3>
                    <p class="text-gray-500 text-sm mt-0.5">{{ $notices->total() }} total</p>
                </div>
                <a href="{{ route('school-admin.notices.create') }}"
                   class="bg-gray-900 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-700 transition shadow-sm">
                    + Post Notice
                </a>
            </div>

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 text-gray-500 uppercase text-[11px] tracking-wider">
                                <th class="px-6 py-3.5 font-semibold">Title</th>
                                <th class="px-6 py-3.5 font-semibold">Message</th>
                                <th class="px-6 py-3.5 font-semibold">Sent To</th>
                                <th class="px-6 py-3.5 font-semibold">Date</th>
                                <th class="px-6 py-3.5 font-semibold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($notices as $notice)
                                @php
                                    $badgeText = match($notice->target_type) {
                                        'all' => 'Everyone',
                                        'teacher' => 'Teachers Only',
                                        'student' => 'Students Only',
                                        'class_specific' => 'Specific Class',
                                        default => $notice->target_type,
                                    };

                                    $badgeColor = match($notice->target_type) {
                                        'all' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
                                        'teacher' => 'bg-purple-50 text-purple-700 ring-1 ring-purple-200',
                                        'student' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                                        'class_specific' => 'bg-green-50 text-green-700 ring-1 ring-green-200',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition align-top">
                                    <td class="px-6 py-5 font-medium text-gray-800 whitespace-nowrap">
                                        {{ $notice->title }}
                                    </td>
                                    <td class="px-6 py-5 text-gray-600 max-w-sm">
                                        <p class="line-clamp-2 leading-relaxed">{{ $notice->message }}</p>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-full {{ $badgeColor }}">
                                            {{ $badgeText }}
                                        </span>
                                        @if ($notice->target_type === 'class_specific' && $notice->targets->isNotEmpty())
                                            <div class="text-xs text-gray-400 mt-1.5">
                                                {{ $notice->targets->map(function ($t) {
                                                    return $t->schoolClass?->name . ($t->section ? ' - ' . $t->section->name : '');
                                                })->implode(', ') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-gray-400 whitespace-nowrap">
                                        {{ $notice->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-5 text-right whitespace-nowrap">
                                        <form action="{{ route('school-admin.notices.destroy', $notice) }}" method="POST"
                                              onsubmit="return confirm('Yo notice delete garne?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-xs font-medium transition">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                        No notices posted yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($notices->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $notices->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>