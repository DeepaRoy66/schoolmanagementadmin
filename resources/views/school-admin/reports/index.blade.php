<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reports & Analytics
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Basic counts --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white shadow-sm rounded-2xl p-5">
                    <p class="text-xs text-gray-400">Total Teachers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTeachers }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-2xl p-5">
                    <p class="text-xs text-gray-400">Total Students</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-2xl p-5">
                    <p class="text-xs text-gray-400">Attendance (This Month)</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendancePercentage }}%</p>
                </div>
                <div class="bg-white shadow-sm rounded-2xl p-5">
                    <p class="text-xs text-gray-400">Average Marks</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $averageMarks }}%</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- Attendance breakdown --}}
                <div class="bg-white shadow-sm rounded-2xl p-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Attendance This Month</h4>
                    @if ($totalAttendanceRecords === 0)
                        <p class="text-sm text-gray-400 text-center py-6">No attendance records available.</p>
                    @else
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Present</span>
                                <span class="font-medium text-green-600">{{ $presentCount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Absent</span>
                                <span class="font-medium text-red-600">{{ $absentCount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Late</span>
                                <span class="font-medium text-amber-600">{{ $lateCount }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t">
                                <span class="text-gray-800 font-medium">Total Records</span>
                                <span class="font-semibold">{{ $totalAttendanceRecords }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Fee summary --}}
                <div class="bg-white shadow-sm rounded-2xl p-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Fee Collection</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Fee Amount</span>
                            <span class="font-medium">Rs. {{ number_format($totalFeeAmount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Collected</span>
                            <span class="font-medium text-green-600">Rs. {{ number_format($totalCollected, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pending</span>
                            <span class="font-medium text-red-600">Rs. {{ number_format($totalPending, 2) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t">
                            <span class="text-gray-800 font-medium">Unpaid Records</span>
                            <span class="font-semibold">{{ $unpaidCount }}</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Class-wise student count --}}
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Students by Class</h4>
                @if ($classCounts->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-6">No students found.</p>
                @else
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b text-gray-500">
                                <th class="py-2">Class</th>
                                <th class="py-2 text-right">Total Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classCounts as $row)
                                <tr class="border-b">
                                    <td class="py-2">{{ $row->class ?? '—' }}</td>
                                    <td class="py-2 text-right">{{ $row->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>