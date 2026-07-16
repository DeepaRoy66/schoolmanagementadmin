<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            System Usage Monitor
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============ Top Stat Cards ============ --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Total Schools --}}
                <div class="bg-white rounded-2xl shadow-sm p-5 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-50 rounded-full"></div>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center mb-3 shadow-md shadow-indigo-200">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 0v10.5a1.5 1.5 0 01-1.5 1.5H6a1.5 1.5 0 01-1.5-1.5V9" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_schools'] }}</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">Total Schools</p>
                    </div>
                </div>

                {{-- Active Licenses --}}
                <div class="bg-white rounded-2xl shadow-sm p-5 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50 rounded-full"></div>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center mb-3 shadow-md shadow-emerald-200">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_licenses'] }}</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">Active Licenses</p>
                    </div>
                </div>

                {{-- Expiring / Expired --}}
                <div class="bg-white rounded-2xl shadow-sm p-5 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-rose-50 rounded-full"></div>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl bg-rose-500 flex items-center justify-center mb-3 shadow-md shadow-rose-200">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['expired_licenses'] }}</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">Expired Licenses</p>
                    </div>
                </div>

                {{-- Total Users --}}
                <div class="bg-white rounded-2xl shadow-sm p-5 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full"></div>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center mb-3 shadow-md shadow-amber-200">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_admins'] + $stats['total_teachers'] + $stats['total_students'] }}</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">Total Users</p>
                    </div>
                </div>
            </div>

            {{-- ============ Secondary breakdown row ============ --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['total_admins'] }}</p>
                        <p class="text-xs text-gray-500">School Admins</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.083 12.083 0 0112 20a12.083 12.083 0 01-6.16-9.42L12 14z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['total_teachers'] }}</p>
                        <p class="text-xs text-gray-500">Teachers</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['total_students'] }}</p>
                        <p class="text-xs text-gray-500">Students</p>
                    </div>
                </div>
            </div>

            {{-- ============ Charts row ============ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- License distribution donut --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-sm font-semibold text-gray-900 mb-1">License Distribution</p>
                    <p class="text-xs text-gray-400 mb-4">Breakdown by status</p>
                    <div class="relative h-56">
                        <canvas id="licenseDonut"></canvas>
                    </div>
                    <div class="flex items-center justify-center gap-4 mt-4 text-xs">
                        <span class="flex items-center gap-1.5 text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                        </span>
                        <span class="flex items-center gap-1.5 text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Trial
                        </span>
                        <span class="flex items-center gap-1.5 text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Expired
                        </span>
                    </div>
                </div>

                {{-- Growth bar chart --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 lg:col-span-2">
                    <p class="text-sm font-semibold text-gray-900 mb-1">School Growth</p>
                    <p class="text-xs text-gray-400 mb-4">New schools added, last 6 months</p>
                    <div class="relative h-56">
                        <canvas id="growthBar"></canvas>
                    </div>
                </div>
            </div>

            {{-- ============ Active / Inactive lists ============ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                {{-- Recently Active --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Recently Active Schools</p>
                            <p class="text-xs text-gray-400">Based on most recent user login</p>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse ($recentlyActive as $school)
                            <div class="flex items-center gap-3 px-6 py-3.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs font-semibold shrink-0">
                                    {{ Str::substr($school->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $school->name }}</p>
                                </div>
                                <span class="text-xs text-gray-400 shrink-0">
                                    {{ \Illuminate\Support\Carbon::parse($school->last_activity)->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <p class="px-6 py-8 text-sm text-gray-400 text-center">No login activity recorded yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Inactive --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Inactive Schools</p>
                            <p class="text-xs text-gray-400">No login in the last 30 days</p>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse ($inactiveSchools as $school)
                            <div class="flex items-center gap-3 px-6 py-3.5">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-semibold shrink-0">
                                    {{ Str::substr($school->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $school->name }}</p>
                                </div>
                                <span class="text-xs text-gray-400 shrink-0">
                                    {{ $school->last_activity ? \Illuminate\Support\Carbon::parse($school->last_activity)->diffForHumans() : 'Never logged in' }}
                                </span>
                            </div>
                        @empty
                            <p class="px-6 py-8 text-sm text-gray-400 text-center">All schools are active. 🎉</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        // License distribution donut
        new Chart(document.getElementById('licenseDonut'), {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Trial', 'Expired'],
                datasets: [{
                    data: [
                        {{ $licenseDistribution['Active'] }},
                        {{ $licenseDistribution['Trial'] }},
                        {{ $licenseDistribution['Expired'] }}
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });

        // School growth bar chart
        new Chart(document.getElementById('growthBar'), {
            type: 'bar',
            data: {
                labels: [@foreach ($schoolGrowth as $point) '{{ $point['label'] }}', @endforeach],
                datasets: [{
                    label: 'New Schools',
                    data: [@foreach ($schoolGrowth as $point) {{ $point['count'] }}, @endforeach],
                    backgroundColor: '#6366f1',
                    borderRadius: 8,
                    maxBarThickness: 40,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</x-app-layout>