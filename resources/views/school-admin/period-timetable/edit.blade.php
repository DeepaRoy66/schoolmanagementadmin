<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Period</h2>
    </x-slot>

    <div class="p-6 max-w-3xl">
        <h1 class="text-2xl font-semibold text-slate-800 mb-6">Edit Period</h1>

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('school-admin.period-timetable.update', $period) }}">
                @method('PUT')
                @include('school-admin.period-timetable._form')
            </form>
        </div>
    </div>
</x-app-layout>