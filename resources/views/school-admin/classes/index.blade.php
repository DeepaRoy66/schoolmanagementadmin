<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0f1b3d] leading-tight tracking-tight">Classes</h2>
    </x-slot>

    <div class="py-8 font-['Inter',_'Segoe_UI',_sans-serif] bg-gradient-to-b from-slate-50 to-slate-100 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Page intro --}}
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-[#0f1b3d] tracking-tight">Classes</h1>
                    <p class="text-slate-500 text-sm mt-1">Manage classes and their sections</p>
                </div>
                <a href="{{ route('school-admin.classes.create') }}"
                   class="inline-flex items-center gap-2 bg-[#0f1b3d] text-white px-4 py-2.5 rounded-lg text-sm font-medium
                          hover:bg-[#16234f] active:scale-[0.98] transition-all shadow-md shadow-[#0f1b3d]/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Class
                </a>
            </div>

            {{-- Stat cards --}}
            @php
                $totalSections = $classes->sum(fn($c) => $c->sections->count());
                $withoutSections = $classes->filter(fn($c) => $c->sections->count() === 0)->count();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-[#eef1f9] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#0f1b3d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-[#0f1b3d] leading-none">{{ $classes->count() }}</p>
                        <p class="text-slate-500 text-xs font-medium mt-1.5">Total Classes</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-[#eafaf0] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-[#0f1b3d] leading-none">{{ $totalSections }}</p>
                        <p class="text-slate-500 text-xs font-medium mt-1.5">Total Sections</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-[#fff4ec] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-[#0f1b3d] leading-none">{{ $withoutSections }}</p>
                        <p class="text-slate-500 text-xs font-medium mt-1.5">Without Sections</p>
                    </div>
                </div>
            </div>

            {{-- Classes card --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-[0_1px_3px_rgba(15,27,61,0.06),0_8px_24px_rgba(15,27,61,0.06)] overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-[#0f1b3d] via-[#2c3f7a] to-[#0f1b3d]"></div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200">
                                <th class="py-3.5 px-6 text-[11px] font-semibold text-[#5b6478] tracking-wider uppercase">Class Name</th>
                                <th class="py-3.5 px-6 text-[11px] font-semibold text-[#5b6478] tracking-wider uppercase">Sections</th>
                                <th class="py-3.5 px-6 text-[11px] font-semibold text-[#5b6478] tracking-wider uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $palette = [
                                    ['bg' => '#eef1f9', 'text' => '#0f1b3d'],
                                    ['bg' => '#eafaf0', 'text' => '#0f7a4b'],
                                    ['bg' => '#fff4ec', 'text' => '#b5560c'],
                                    ['bg' => '#fdeef2', 'text' => '#be1e4d'],
                                ];
                            @endphp
                            @forelse ($classes as $i => $class)
                                @php $color = $palette[$i % count($palette)]; @endphp
                                <tr class="border-b border-slate-100 last:border-b-0 hover:bg-[#f6f8fc] transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-sm shrink-0"
                                                 style="background-color: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                                {{ strtoupper(substr($class->name, 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-[#0f1b3d]">{{ $class->name }}</span>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse ($class->sections as $section)
                                                <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2
                                                             bg-[#eef1f9] text-[#0f1b3d] rounded-md text-xs font-semibold
                                                             border border-[#d8ddec]">
                                                    {{ $section->name }}
                                                </span>
                                            @empty
                                                <span class="text-slate-400 text-xs italic">No sections yet</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <form action="{{ route('school-admin.classes.destroy', $class) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yo class delete garne?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 text-rose-600 hover:text-white hover:bg-rose-600
                                                           font-medium text-xs px-2.5 py-1.5 rounded-md border border-rose-100
                                                           hover:border-rose-600 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-14 text-center text-slate-400 text-sm">
                                        No class added yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>