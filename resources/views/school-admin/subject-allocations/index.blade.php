<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Subject Allocation
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-md">

                <!-- Top Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500">
                        Assign teachers to subjects for each class and section.
                    </p>

                    <a href="{{ route('school-admin.subject-allocations.create') }}"
                        class="bg-[#3b82f6] text-white px-4 py-2 rounded text-sm font-medium hover:bg-[#2563eb] transition-colors">
                        + Allocate Subject
                    </a>
                </div>

                <!-- Table -->
                <div class="px-6 py-4">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left">
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Class</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Section</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Subject</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Teacher</th>
                                <th class="border border-gray-200 p-2 text-gray-600 font-medium">Action</th>
                            </tr>
                        </thead>

                        <tbody id="allocationBody">
                            @foreach ($rows as $row)
                                <tr data-section-id="{{ $row['section_id'] }}"
                                    data-subject-id="{{ $row['subject_id'] }}">

                                    <td class="border border-gray-200 p-2">
                                        {{ $row['class_name'] }}
                                    </td>

                                    <td class="border border-gray-200 p-2">
                                        {{ $row['section_name'] }}
                                    </td>

                                    <td class="border border-gray-200 p-2">
                                        {{ $row['subject_name'] }}
                                    </td>

                                    <td class="border border-gray-200 p-2 teacher-cell">
                                        @if ($row['teacher_name'])
                                            {{ $row['teacher_name'] }}
                                        @else
                                            <span class="text-red-600 italic">
                                                Not assigned
                                            </span>
                                        @endif
                                    </td>

                                    <td class="border border-gray-200 p-2">
                                        <button
                                            type="button"
                                            class="edit-btn bg-yellow-400 text-yellow-900 px-3 py-1 rounded text-xs font-medium hover:bg-yellow-500 transition-colors"
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
                <select class="teacher-select border border-gray-300 rounded px-2 py-1 w-full focus:outline-none focus:ring-1 focus:ring-[#3b82f6] focus:border-[#3b82f6]">
                    <option value="">-- Select Teacher --</option>
                    ${options}
                </select>
            `;

            btn.textContent = 'Save';
            btn.classList.remove('bg-yellow-400', 'text-yellow-900', 'hover:bg-yellow-500');
            btn.classList.add('bg-[#3b82f6]', 'text-white', 'hover:bg-[#2563eb]');

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