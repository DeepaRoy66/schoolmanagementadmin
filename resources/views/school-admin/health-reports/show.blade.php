<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Breadcrumb --}}
            <div class="text-sm text-gray-500">
                <a href="{{ route('school-admin.health-reports.index') }}" class="text-blue-600 hover:underline">Health Reports</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700">{{ $healthReport->student->full_name ?? 'Report' }}</span>
            </div>

            @if (session('success'))
                <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Report header --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $healthReport->student->full_name ?? 'Unknown Student' }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Health report submitted on {{ $healthReport->created_at->format('d M Y, h:i A') }}</p>
                </div>

                @php
                    $statusStyles = [
                        'pending' => 'bg-amber-100 text-amber-700',
                        'reviewed' => 'bg-blue-100 text-blue-700',
                        'resolved' => 'bg-green-100 text-green-700',
                    ][$healthReport->status] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide {{ $statusStyles }}">
                        {{ $healthReport->status }}
                    </span>

                    <form method="POST" action="{{ route('school-admin.health-reports.update-status', $healthReport) }}"
                          class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status"
                                class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                            <option value="pending" @selected($healthReport->status === 'pending')>Pending</option>
                            <option value="reviewed" @selected($healthReport->status === 'reviewed')>Reviewed</option>
                            <option value="resolved" @selected($healthReport->status === 'resolved')>Resolved</option>
                        </select>
                        <button type="submit"
                                class="rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 transition-colors">
                            Save
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left: Report info --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Report Information</h3>

                    <div class="divide-y divide-gray-100">
                        <div class="flex justify-between py-3">
                            <span class="text-sm text-gray-500">Student</span>
                            <span class="text-sm font-medium text-gray-900">{{ $healthReport->student->full_name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between py-3">
                            <span class="text-sm text-gray-500">Class</span>
                            <span class="text-sm font-medium text-gray-900">{{ $healthReport->schoolClass->name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between py-3">
                            <span class="text-sm text-gray-500">Reported By</span>
                            <span class="text-sm font-medium text-gray-900">{{ $healthReport->reporter->name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between py-3">
                            <span class="text-sm text-gray-500">Submitted</span>
                            <span class="text-sm font-medium text-gray-900">{{ $healthReport->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="py-3">
                            <span class="text-sm text-gray-500 block mb-1">Message</span>
                            <p class="text-sm font-medium text-gray-900 whitespace-pre-line">{{ $healthReport->message ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Attached photo (evidence, not a profile pic) --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Attached Photo</h3>

                    <div class="w-full aspect-square rounded-xl overflow-hidden border-2 border-gray-200 bg-gray-50 flex items-center justify-center">
                        @if ($healthReport->photo_path)
                            <a href="{{ Storage::url($healthReport->photo_path) }}" target="_blank">
                                <img src="{{ Storage::url($healthReport->photo_path) }}"
                                     alt="Health report photo"
                                     class="w-full h-full object-cover">
                            </a>
                        @else
                            <div class="flex flex-col items-center text-gray-300">
                                <svg class="w-16 h-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18-3.5v6.5A2.25 2.25 0 004.5 21h15a2.25 2.25 0 002.25-2.25v-6.5m-19.5 0V6.75A2.25 2.25 0 014.5 4.5h15a2.25 2.25 0 012.25 2.25v6.5m-19.5 0h19.5M9 9.75a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                </svg>
                                <span class="text-sm">No Photo Attached</span>
                            </div>
                        @endif
                    </div>
                    @if ($healthReport->photo_path)
                        <p class="text-xs text-gray-400 mt-2 text-center">Click to view full size</p>
                    @endif
                </div>
            </div>

            <div>
                <a href="{{ route('school-admin.health-reports.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-600 hover:text-gray-900">
                    &larr; Back to Reports
                </a>
            </div>

        </div>
    </div>
</x-app-layout>