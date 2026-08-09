<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-semibold text-gray-900">Feedback</h1>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" class="mb-4 flex gap-3">
                <select name="role" class="rounded-md border-gray-300 text-sm" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <option value="student" @selected(request('role') === 'student')>Student</option>
                    <option value="teacher" @selected(request('role') === 'teacher')>Teacher</option>
                </select>
                <select name="status" class="rounded-md border-gray-300 text-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="reviewed" @selected(request('status') === 'reviewed')>Reviewed</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                </select>
            </form>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Name</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Role</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Subject</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Message</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Date</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($feedbacks as $feedback)
                            <tr>
                                <td class="px-4 py-3">{{ $feedback->user->name ?? '—' }}</td>
                                <td class="px-4 py-3 capitalize">{{ $feedback->submitted_by_role }}</td>
                                <td class="px-4 py-3">{{ $feedback->subject ?? '—' }}</td>
                                <td class="px-4 py-3 max-w-xs truncate">{{ $feedback->message }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $feedback->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $feedback->status === 'reviewed' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $feedback->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ ucfirst($feedback->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $feedback->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('school-admin.feedback.update-status', $feedback) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-md border-gray-300 text-xs" onchange="this.form.submit()">
                                            <option value="pending" @selected($feedback->status === 'pending')>Pending</option>
                                            <option value="reviewed" @selected($feedback->status === 'reviewed')>Reviewed</option>
                                            <option value="resolved" @selected($feedback->status === 'resolved')>Resolved</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">No feedback found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $feedbacks->links() }}
            </div>

        </div>
    </div>
</x-app-layout>