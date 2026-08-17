<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Gallery</h1>
                    <p class="text-sm text-gray-500 mt-1">Photos and videos uploaded by teachers and students.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $gallery->total() }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs font-medium text-blue-500 uppercase tracking-wide">Photos</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $photoCount ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs font-medium text-purple-500 uppercase tracking-wide">Videos</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $videoCount ?? 0 }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <form method="GET" class="bg-white border border-gray-200 rounded-xl p-4 flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Uploader name or caption..."
                           class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Class</label>
                    <select name="class_id" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                        <option value="">All Classes</option>
                        @foreach (($classes ?? []) as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Uploaded By</label>
                    <select name="role" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                        <option value="">Teacher & Student</option>
                        <option value="teacher" @selected(request('role') === 'teacher')>Teacher</option>
                        <option value="student" @selected(request('role') === 'student')>Student</option>
                    </select>
                </div>
                <div class="min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Media Type</label>
                    <select name="media_type" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                        <option value="">Photos & Videos</option>
                        <option value="image" @selected(request('media_type') === 'image')>Photos</option>
                        <option value="video" @selected(request('media_type') === 'video')>Videos</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 transition-colors">
                        Filter
                    </button>
                    @if (request('class_id') || request('search') || request('role') || request('media_type'))
                        <a href="{{ route('school-admin.gallery.index') }}"
                           class="rounded-md border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm font-medium px-4 py-2 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            {{-- Gallery grid --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-200 p-6">
                @forelse ($gallery as $item)
                @empty
                    <div class="px-6 py-16 text-center">
                        <p class="text-gray-400 text-sm">No photos or videos match your filters.</p>
                    </div>
                @endforelse

                @if ($gallery->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach ($gallery as $item)
                            <div class="group relative rounded-xl overflow-hidden border border-gray-200 bg-gray-50">

                                {{-- Media --}}
                                <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                                    @if ($item->media_type === 'video')
                                        <video class="w-full h-full object-cover" controls preload="metadata">
                                            <source src="{{ Storage::url($item->image_path) }}">
                                            Your browser does not support the video tag.
                                        </video>
                                        <span class="absolute top-2 left-2 bg-black/60 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded pointer-events-none">
                                            VIDEO
                                        </span>
                                    @else
                                        <img src="{{ Storage::url($item->image_path) }}"
                                             alt="{{ $item->caption }}"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="px-3 py-2">
                                    @if ($item->caption)
                                        <p class="text-xs text-gray-700 truncate">{{ $item->caption }}</p>
                                    @endif
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="text-[11px] text-gray-500 truncate">{{ $item->uploader->name ?? '—' }}</span>
                                        @if ($item->uploader)
                                            <span class="text-[9px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded
                                                {{ $item->uploader->role === 'teacher' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600' }}">
                                                {{ $item->uploader->role }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-gray-400 truncate mt-0.5">
                                        @if ($item->schoolClass)
                                            {{ $item->schoolClass->name }} ·
                                        @endif
                                        {{ $item->created_at->format('d M Y, h:i A') }}
                                    </p>
                                </div>

                                {{-- Delete (moderate) --}}
                                <form method="POST" action="{{ route('school-admin.gallery.destroy', $item) }}"
                                      class="absolute top-2 right-2"
                                      onsubmit="return confirm('Delete this item? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-black/60 hover:bg-red-600 text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                {{ $gallery->links() }}
            </div>

        </div>
    </div>
</x-app-layout>