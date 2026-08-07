<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <h2 class="font-semibold text-xl text-slate-700">Subject allocation</h2>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">Assign teachers to subjects</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <a href="{{ route('school-admin.subject-allocations.create') }}"
                   class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Allocate subject
                </a>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                <div class="flex flex-wrap items-center gap-3 px-6 py-5 border-b border-slate-100">
                    <label class="text-sm text-slate-600 font-medium">Search:</label>
                    <form action="{{ route('school-admin.subject-allocations.index') }}" method="GET" class="flex items-center gap-2 flex-1 min-w-[260px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by class, section, subject or teacher..."
                               class="flex-1 max-w-sm px-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('school-admin.subject-allocations.index') }}"
                               class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
                        @endif
                    </form>

                    <span class="text-xs text-slate-500 whitespace-nowrap ml-auto">{{ count($rows) }} {{ Str::plural('row', count($rows)) }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 border-b border-slate-200">
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Class</th>
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Section</th>
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Subject</th>
                                <th class="py-3 px-6 font-semibold border-r border-slate-200">Teacher</th>
                                <th class="py-3 px-6 font-semibold w-28">Option</th>
                            </tr>
                        </thead>

                        <tbody id="allocationBody">
                            @forelse ($rows as $row)
                                <tr data-section-id="{{ $row['section_id'] }}"
                                    data-subject-id="{{ $row['subject_id'] }}"
                                    class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">

                                    <td class="py-3 px-6 border-r border-slate-100 text-slate-700">{{ $row['class_name'] }}</td>
                                    <td class="py-3 px-6 border-r border-slate-100 text-slate-700">{{ $row['section_name'] }}</td>
                                    <td class="py-3 px-6 border-r border-slate-100">
                                        <span class="font-medium text-blue-700">{{ $row['subject_name'] }}</span>
                                    </td>

                                    <td class="py-3 px-6 border-r border-slate-100 teacher-cell">
                                        @if ($row['teacher_name'])
                                            <span class="text-slate-700">{{ $row['teacher_name'] }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100">
                                                Not assigned
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-6">
                                        <button
                                            type="button"
                                            class="edit-btn inline-flex items-center gap-1.5 bg-amber-500 text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-amber-600 transition-colors shadow-sm"
                                            data-current-teacher="{{ $row['teacher_id'] }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            @if (request('search'))
                                                <p class="text-slate-500 text-sm">No allocations match "{{ request('search') }}".</p>
                                                <a href="{{ route('school-admin.subject-allocations.index') }}" class="text-blue-600 text-sm font-medium hover:underline">Clear search</a>
                                            @else
                                                <p class="text-slate-500 text-sm">No subjects to allocate yet.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        const teachers = @json(
            $teachers->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->full_name
            ])
        );

        const csrfToken = '{{ csrf_token() }}';

        document.getElementById('allocationBody').addEventListener('click', function(e) {

            const btn = e.target.closest('.edit-btn');
            if (!btn) return;

            const tr = btn.closest('tr');
            const teacherCell = tr.querySelector('.teacher-cell');
            const currentTeacherId = btn.dataset.currentTeacher;

            const options = teachers.map(t =>
                `<option value="${t.id}" ${t.id == currentTeacherId ? 'selected' : ''}>
                    ${t.name}
                </option>`
            ).join('');

            teacherCell.innerHTML = `
                <select class="teacher-select border border-slate-300 rounded-md px-2 py-1.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                    <option value="">Select teacher</option>
                    ${options}
                </select>
            `;

            btn.textContent = 'Save';
            btn.classList.remove('bg-amber-500', 'hover:bg-amber-600');
            btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');

            btn.onclick = async function() {

                const select = teacherCell.querySelector('select');
                const teacherId = select.value;

                if (!teacherId) {
                    alert('Teacher select garnus.');
                    return;
                }

                const res = await fetch(
                    '{{ route("school-admin.subject-allocations.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            subject_id: tr.dataset.subjectId,
                            section_id: tr.dataset.sectionId,
                            teacher_id: teacherId,
                        }),
                    }
                );

                if (res.ok) {
                    location.reload();
                } else {
                    alert('Save huna sakena. Feri try garnus.');
                }
            };

        });
    </script>

</x-app-layout>