<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Subject Allocation
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <!-- Top Header -->
                <div class="flex justify-between items-center mb-4">
                    <p class="text-sm text-gray-500">
                        Kun teacher le kun class-section ma kun subject padhaucha, tyo yaha set garnus.
                    </p>

                    <a href="{{ route('school-admin.subject-allocations.create') }}"
                        class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700">
                        + Add Assignment
                    </a>
                </div>

                <!-- Table -->
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="border p-2">Class</th>
                            <th class="border p-2">Section</th>
                            <th class="border p-2">Subject</th>
                            <th class="border p-2">Teacher</th>
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>

                    <tbody id="allocationBody">
                        @foreach ($rows as $row)
                            <tr data-section-id="{{ $row['section_id'] }}"
                                data-subject-id="{{ $row['subject_id'] }}">

                                <td class="border p-2">
                                    {{ $row['class_name'] }}
                                </td>

                                <td class="border p-2">
                                    {{ $row['section_name'] }}
                                </td>

                                <td class="border p-2">
                                    {{ $row['subject_name'] }}
                                </td>

                                <td class="border p-2 teacher-cell">
                                    @if ($row['teacher_name'])
                                        {{ $row['teacher_name'] }}
                                    @else
                                        <span class="text-red-600 italic">
                                            Not assigned
                                        </span>
                                    @endif
                                </td>

                                <td class="border p-2">
                                    <button
                                        type="button"
                                        class="edit-btn text-blue-600 hover:underline"
                                        data-current-teacher="{{ $row['teacher_id'] }}">
                                        Edit
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

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

            if (!e.target.classList.contains('edit-btn')) return;

            const btn = e.target;
            const tr = btn.closest('tr');
            const teacherCell = tr.querySelector('.teacher-cell');
            const currentTeacherId = btn.dataset.currentTeacher;

            const options = teachers.map(t =>
                `<option value="${t.id}" ${t.id == currentTeacherId ? 'selected' : ''}>
                    ${t.name}
                </option>`
            ).join('');

            teacherCell.innerHTML = `
                <select class="teacher-select border border-gray-300 rounded px-2 py-1 w-full">
                    <option value="">-- Select Teacher --</option>
                    ${options}
                </select>
            `;

            btn.textContent = 'Save';

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