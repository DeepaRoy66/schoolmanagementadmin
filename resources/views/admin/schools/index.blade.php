<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Schools
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 0v10.5a1.5 1.5 0 01-1.5 1.5H6a1.5 1.5 0 01-1.5-1.5V9" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">All Schools</p>
                            <p class="text-xs text-gray-400">{{ $schools->total() }} {{ Str::plural('school', $schools->total()) }} registered</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.schools.create') }}"
                       class="inline-flex items-center justify-center gap-1.5 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800 active:scale-[0.98] transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add New School
                    </a>
                </div>

                {{-- Filter Tabs --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/40">
                    @php
                        $currentStatus = request('status');
                        $tabs = [
                            ['key' => null,       'label' => 'All',     'count' => $counts['all'],     'dot' => null,               'ring' => 'ring-gray-900'],
                            ['key' => 'active',   'label' => 'Active',  'count' => $counts['active'],  'dot' => 'bg-emerald-500',   'ring' => 'ring-emerald-500'],
                            ['key' => 'trial',    'label' => 'Trial',   'count' => $counts['trial'],   'dot' => 'bg-amber-500',     'ring' => 'ring-amber-500'],
                            ['key' => 'expired',  'label' => 'Expired', 'count' => $counts['expired'], 'dot' => 'bg-rose-500',      'ring' => 'ring-rose-500'],
                        ];
                    @endphp

                    <div class="flex items-center gap-2 overflow-x-auto">
                        @foreach ($tabs as $tab)
                            @php $active = $currentStatus === $tab['key']; @endphp
                            <a href="{{ route('admin.schools.index', $tab['key'] ? ['status' => $tab['key']] : []) }}"
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
                                <th class="py-3 px-6 font-medium">Name</th>
                                <th class="py-3 px-6 font-medium">Code</th>
                                <th class="py-3 px-6 font-medium">License Status</th>
                                <th class="py-3 px-6 font-medium">License Expiry</th>
                                <th class="py-3 px-6 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($schools as $school)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-semibold shrink-0">
                                                {{ Str::substr($school->name, 0, 1) }}
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $school->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500 font-mono text-xs">
                                        {{ $school->school_code ?? '—' }}
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
                                        <div class="flex items-center justify-end gap-2">
                                            @if ($school->license_status === 'expired')
                                                <button type="button"
                                                        onclick="openRenewModal('{{ $school->id }}')"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                               bg-rose-50 text-rose-700 border border-rose-200
                                                               hover:bg-rose-100 hover:border-rose-300 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                    </svg>
                                                    Renew
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.schools.edit', $school) }}"
                                               class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.schools.destroy', $school) }}" method="POST"
                                                  onsubmit="return confirm('School delete garne? Yo undo huna sakdaina.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                                        title="Delete">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 0v10.5a1.5 1.5 0 01-1.5 1.5H6a1.5 1.5 0 01-1.5-1.5V9" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">
                                                @if (request('status'))
                                                    No {{ request('status') }} schools found.
                                                @else
                                                    No schools found
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-400">"Add New School"</p>
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
        @if ($school->license_status === 'expired')
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

                    <form action="{{ route('admin.schools.renew', $school) }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                New Expiry Date
                            </label>
                            <input type="date" name="license_expiry"
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
        @endif
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