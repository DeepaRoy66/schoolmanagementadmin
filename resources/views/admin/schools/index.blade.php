<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Schools
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 text-sm">Total schools: {{ $schools->total() }}</p>
                    <a href="{{ route('admin.schools.create') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add New School
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Name</th>
                            <th class="py-2">License Status</th>
                            <th class="py-2">License Expiry</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schools as $school)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $school->name }}</td>
                                <td class="py-3">
                                    @if ($school->license_status === 'active')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Active</span>
                                    @elseif ($school->license_status === 'trial')
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-semibold">Trial</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">Expired</span>
                                    @endif
                                </td>
                                <td class="py-3 text-gray-600">{{ $school->license_expiry ?? '—' }}</td>
                                <td class="py-3 text-right space-x-2">
                                    <a href="{{ route('admin.schools.edit', $school) }}" class="text-blue-600 hover:underline">Edit</a>
                                    @if (in_array($school->license_status, ['expired', 'trial']))
                                        <button type="button"
                                                onclick="openRenewModal('{{ $school->id }}')"
                                                class="text-green-600 hover:underline">
                                            Renew
                                        </button>
                                    @endif
                                    <form action="{{ route('admin.schools.destroy', $school) }}" method="POST" class="inline"
                                          onsubmit="return confirm('School delete garne? Yo undo huna sakdaina.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500">
                                    Kunai school thapiyeko chaina. "Add New School" click garnus.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $schools->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Renew Modals: rendered outside the table, at the end of the page --}}
    @foreach ($schools as $school)
        @if (in_array($school->license_status, ['expired', 'trial']))
            <div id="renew-modal-{{ $school->id }}"
                 style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; align-items:center; justify-content:center; background:rgba(0,0,0,0.5); padding:16px;">
                <div style="background:#fff; border-radius:16px; box-shadow:0 20px 40px rgba(0,0,0,0.2); padding:24px; width:100%; max-width:384px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                        <h3 style="font-weight:600; color:#1f2937; font-size:18px; margin:0;">
                            Renew License
                        </h3>
                        <button type="button"
                                onclick="closeRenewModal('{{ $school->id }}')"
                                style="background:none; border:none; color:#9ca3af; font-size:22px; line-height:1; cursor:pointer;">
                            &times;
                        </button>
                    </div>

                    <p style="font-size:14px; color:#6b7280; margin-bottom:16px;">{{ $school->name }}</p>

                    <form action="{{ route('admin.schools.renew', $school) }}" method="POST">
                        @csrf
                        <div style="margin-bottom:20px;">
                            <label style="display:block; font-size:14px; font-weight:500; color:#374151; margin-bottom:4px;">
                                New Expiry Date
                            </label>
                            <input type="date" name="license_expiry"
                                   style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:8px 12px; font-size:14px;"
                                   required>
                        </div>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <button type="submit"
                                    style="background:#111827; color:#fff; padding:8px 16px; border-radius:8px; font-size:14px; font-weight:500; border:none; cursor:pointer;">
                                Confirm Renew
                            </button>
                            <button type="button"
                                    onclick="closeRenewModal('{{ $school->id }}')"
                                    style="background:none; border:none; color:#4b5563; font-size:14px; cursor:pointer; text-decoration:underline;">
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
            document.getElementById('renew-modal-' + id).style.display = 'flex';
        }
        function closeRenewModal(id) {
            document.getElementById('renew-modal-' + id).style.display = 'none';
        }

        // Optional: close modal if user clicks the dark overlay outside the box
        document.addEventListener('click', function (e) {
            if (e.target.id && e.target.id.startsWith('renew-modal-')) {
                e.target.style.display = 'none';
            }
        });
    </script>
</x-app-layout>