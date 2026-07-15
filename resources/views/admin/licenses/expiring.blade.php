<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Licenses Expiring Soon
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="flex items-center gap-2 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-xl text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <a href="{{ route('admin.licenses.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to All Licenses
            </a>

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Expiring within {{ $days }} days</p>
                            <p class="text-xs text-gray-400">{{ $schools->total() }} {{ Str::plural('school', $schools->total()) }} need attention</p>
                        </div>
                    </div>

                    {{-- Window selector --}}
                    <div class="flex items-center gap-2">
                        @foreach ([7, 14, 30, 60] as $window)
                            <a href="{{ route('admin.licenses.expiring', ['days' => $window]) }}"
                               class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium border transition
                                      {{ (int) $days === $window
                                            ? 'bg-gray-900 border-gray-900 text-white'
                                            : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50' }}">
                                {{ $window }}d
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50/60 text-gray-500 text-xs uppercase tracking-wide">
                                <th class="py-3 px-6 font-medium">School</th>
                                <th class="py-3 px-6 font-medium">Status</th>
                                <th class="py-3 px-6 font-medium">Expiry Date</th>
                                <th class="py-3 px-6 font-medium">Days Left</th>
                                <th class="py-3 px-6 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($schools as $school)
                                @php
                                    $daysLeft = (int) round(now()->startOfDay()->diffInDays(
                                        \Illuminate\Support\Carbon::parse($school->license_expiry)->startOfDay(),
                                        false
                                    ));
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-semibold shrink-0">
                                                {{ Str::substr($school->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-900 block">{{ $school->name }}</span>
                                                <span class="text-xs text-gray-400 font-mono">{{ $school->school_code ?? '—' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            {{ ucfirst($school->license_status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ $school->license_expiry }}</td>
                                    <td class="py-4 px-6">
                                        <span class="text-amber-600 font-medium">{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                    onclick="openRenewModal('{{ $school->id }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                           bg-indigo-50 text-indigo-700 border border-indigo-200
                                                           hover:bg-indigo-100 hover:border-indigo-300 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                                Renew
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16">
                                        <div class="flex flex-col items-center gap-2 text-center">
                                            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">Nothing expiring in the next {{ $days }} days.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($schools->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $schools->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Renew Modals --}}
    @foreach ($schools as $school)
        <div id="renew-modal-{{ $school->id }}"
             class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-900/50 p-4">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 text-lg">Renew License</h3>
                    <button type="button"
                            onclick="closeRenewModal('{{ $school->id }}')"
                            class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-5">{{ $school->name }}</p>

                <form action="{{ route('admin.licenses.renew', $school) }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            New Expiry Date
                        </label>
                        <input type="date" name="license_expiry"
                               value="{{ $school->license_expiry }}"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                               required>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-800 transition">
                            Confirm Renew
                        </button>
                        <button type="button"
                                onclick="closeRenewModal('{{ $school->id }}')"
                                class="text-gray-500 text-sm hover:text-gray-700 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        function openRenewModal(id) {
            const modal = document.getElementById('renew-modal-' + id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeRenewModal(id) {
            const modal = document.getElementById('renew-modal-' + id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.addEventListener('click', function (e) {
            if (e.target.id && e.target.id.startsWith('renew-modal-')) {
                closeRenewModal(e.target.id.replace('renew-modal-', ''));
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="renew-modal-"]').forEach(function (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            }
        });
    </script>
</x-app-layout>