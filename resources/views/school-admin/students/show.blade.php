<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Students
            </span>
            <span class="text-slate-300">»</span>
            <span class="text-slate-400">{{ $student->name }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                <div class="flex items-center gap-4 px-6 py-5 border-b border-slate-100 bg-slate-50/40">
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-lg flex-shrink-0">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">{{ $student->name }}</h3>
                        <p class="text-xs text-slate-500">Student profile</p>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="border border-slate-200 rounded-md px-4 py-3">
                            <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Email</dt>
                            <dd class="text-sm text-slate-800">{{ $student->email }}</dd>
                        </div>
                        <div class="border border-slate-200 rounded-md px-4 py-3">
                            <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Phone</dt>
                            <dd class="text-sm text-slate-800">{{ $student->phone ?? '—' }}</dd>
                        </div>
                        <div class="border border-slate-200 rounded-md px-4 py-3">
                            <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Class</dt>
                            <dd class="text-sm text-slate-800">{{ $student->class ?? '—' }}</dd>
                        </div>
                        <div class="border border-slate-200 rounded-md px-4 py-3">
                            <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Roll Number</dt>
                            <dd class="text-sm text-slate-800">{{ $student->roll_number ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="flex items-center gap-3 px-6 py-5 border-t border-slate-100 bg-slate-50/50">
                    <a href="{{ route('school-admin.students.index') }}"
                       class="inline-flex items-center gap-1.5 border border-slate-300 text-slate-600 px-4 py-2.5 rounded-md text-sm font-medium hover:bg-slate-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to list
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>