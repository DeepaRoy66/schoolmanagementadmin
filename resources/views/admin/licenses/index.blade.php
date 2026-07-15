<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            License Manager
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

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Manage Licenses</p>
                            <p class="text-xs text-gray-400">{{ $schools->total() }} {{ Str::plural('school', $schools->total()) }} · sorted by expiry</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.licenses.expiring') }}"
                       class="inline-flex items-center justify-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-amber-100 active:scale-[0.98] transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        View Expiring Soon
                    </a>
                </div>

                {{-- Filter Tabs --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/40">
                    @php
                        $currentStatus = request('status');
                        $tabs = [
                            ['key' => null,       'label' => 'All',     'count' => $counts['all'],     'dot' => null],
                            ['key' => 'active',   'label' => 'Active',  'count' => $counts['active'],  'dot' => 'bg-emerald-500'],
                            ['key' => 'trial',    'label' => 'Trial',   'count' => $counts['trial'],   'dot' => 'bg-amber-500'],
                            ['key' => 'expired',  'label' => 'Expired', 'count' => $counts['expired'], 'dot' => 'bg-rose-500'],
                        ];
                    @endphp

                    <div class="flex items-center gap-2 overflow-x-auto">
                        @foreach ($tabs as $tab)
                            @php $active = $currentStatus === $tab['key']; @endphp
                            <a href="{{ route('admin.licenses.index', $tab['key'] ? ['status' => $tab['key']] : []) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap
                                      border transition-all duration-150
                                      {{ $active
                                            ? 'bg-gray-900 border-gray-900 text-white shadow-sm'
                                            : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50' }}">
                                @if ($tab['dot'])
                                    <span class="w-2 h-2 rounded-full {{ $tab['dot'] }} {{ $active ? 'ring-2 ring-white/30' : '' }}"></span>
                                @endif
                                {{ $tab['label'] }}
                                <span class="inline-flex items-center justify-center min-w-[1.375rem] h-5 px-1.5 rounded-full text-xs font-semibold
                                             {{ $active ? 'bg-white/15 text-white' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $tab['count'] }}
                                </span>
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
                                    $daysLeft = $school->license_expiry
                                        ? (int) round(now()->startOfDay()->diffInDays(
                                            \Illuminate\Support\Carbon::parse($school->license_expiry)->startOfDay(),
                                            false
                                          ))
                                        : null;
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
                                        @if ($school->license_status === 'active')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Active
                                            </span>
                                        @elseif ($school->license_status === 'trial')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Trial
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Expired
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ $school->license_expiry ?? '—' }}</td>
                                    <td class="py-4 px-6">
                                        @if (is_null($daysLeft))
                                            <span class="text-gray-400">—</span>
                                        @elseif ($daysLeft < 0)
                                            <span class="text-rose-600 font-medium">{{ abs($daysLeft) }} days ago</span>
                                        @elseif ($daysLeft <= 14)
                                            <span class="text-amber-600 font-medium">{{ $daysLeft }} days</span>
                                        @else
                                            <span class="text-gray-500">{{ $daysLeft }} days</span>
                                        @endif
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

                                            <form action="{{ route('admin.licenses.toggle', $school) }}" method="POST"
                                                  onsubmit="return confirm('{{ $school->license_status === 'active' ? 'Deactivate' : 'Activate' }} license for {{ $school->name }}?');">
                                                @csrf
                                                @method('PATCH')
                                                @if ($school->license_status === 'active')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                                   bg-gray-50 text-gray-600 border border-gray-200
                                                                   hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 11-12.73 0M12 3v9" />
                                                        </svg>
                                                        Deactivate
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                                   bg-gray-50 text-gray-600 border border-gray-200
                                                                   hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Activate
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16">
                                        <div class="flex flex-col items-center gap-2 text-center">
                                            <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">No schools found.</p>
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