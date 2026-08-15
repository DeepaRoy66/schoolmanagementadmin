<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Period</h2>
    </x-slot>

    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-slate-800">
                New Period
            </h1>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
            <form action="{{ route('school-admin.period-timetable.store') }}" method="POST">
                @include('school-admin.period-timetable._form')
            </form>
        </div>

    </div>
</x-app-layout>