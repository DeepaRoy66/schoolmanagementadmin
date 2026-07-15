<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Announcement
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <a href="{{ route('admin.announcements.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Announcements
            </a>

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                <div class="px-8 pt-8 pb-2">
                    <h1 class="text-lg font-bold tracking-wider text-gray-800 uppercase">
                        Compose Announcement
                    </h1>
                    <p class="text-sm text-gray-400 mt-1">This will be sent to all schools on the platform.</p>
                </div>

                <form method="POST" action="{{ route('admin.announcements.store') }}"
                      enctype="multipart/form-data" class="px-8 pb-8 pt-4 space-y-5">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm text-gray-700 mb-1.5">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="w-full border rounded-md px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition
                                      {{ $errors->has('title') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}"
                               placeholder="e.g. Scheduled Maintenance on Friday" required>
                        @error('title')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <label class="block text-sm text-gray-700 mb-1.5">Message</label>
                        <textarea name="message" rows="6"
                                  class="w-full border rounded-md px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition resize-none
                                         {{ $errors->has('message') ? 'border-rose-400 focus:border-rose-500' : 'border-teal-400 focus:border-teal-500' }}"
                                  placeholder="Write your announcement here..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div>
                        <label class="block text-sm text-gray-700 mb-1.5">Image (optional)</label>

                        <label for="image-input"
                               id="image-drop-area"
                               class="flex flex-col items-center justify-center gap-2 border-2 border-dashed rounded-xl px-6 py-8 cursor-pointer transition
                                      {{ $errors->has('image') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 hover:border-teal-400 hover:bg-teal-50/30' }}">
                            <img id="image-preview" src="" alt="" class="hidden max-h-40 rounded-lg object-cover mb-1">

                            <svg id="upload-icon" class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 8.25c0-.966.784-1.75 1.75-1.75h14.5c.966 0 1.75.784 1.75 1.75v10.5A1.75 1.75 0 0119.25 21H4.75A1.75 1.75 0 013 19.5V8.25z" />
                            </svg>
                            <p id="upload-hint" class="text-sm text-gray-500">
                                <span class="text-teal-600 font-medium">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-400">PNG, JPG, WEBP up to 4MB</p>

                            <input id="image-input" type="file" name="image" accept="image/png, image/jpeg, image/webp" class="hidden">
                        </label>
                        @error('image')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-md text-sm font-medium hover:bg-teal-700 active:scale-[0.98] transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            Send to All Schools
                        </button>
                        <a href="{{ route('admin.announcements.index') }}"
                           class="text-gray-500 text-sm hover:text-gray-700 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('image-preview');
        const uploadIcon = document.getElementById('upload-icon');
        const uploadHint = document.getElementById('upload-hint');
        const dropArea = document.getElementById('image-drop-area');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
                uploadHint.innerHTML = '<span class="text-teal-600 font-medium">' + file.name + '</span> selected — click to change';
            };
            reader.readAsDataURL(file);
        });

        ['dragover', 'dragleave', 'drop'].forEach(evt => {
            dropArea.addEventListener(evt, e => e.preventDefault());
        });

        dropArea.addEventListener('drop', function (e) {
            const file = e.dataTransfer.files[0];
            if (file) {
                imageInput.files = e.dataTransfer.files;
                imageInput.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app-layout>