<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Fee Rate</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('school-admin.fee-rates.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-indigo-50 text-indigo-700 font-semibold text-sm hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182 1.106-.879 2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Fee Rates
                </a>
                <span class="text-gray-400">&raquo;</span>
                <span class="text-gray-500 text-sm">Add Fee Rate</span>
            </div>

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow rounded-md overflow-hidden border-t-4 border-indigo-600">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800">Fee Rate Details</h3>
                    <p class="text-sm text-gray-500 mt-1">Fill in the details below to add a new fee rate.</p>
                </div>

                <form action="{{ route('school-admin.fee-rates.store') }}" method="POST" class="px-6 py-6 max-w-2xl">
                    @csrf
                    @include('school-admin.fee-rates._form', ['feeRate' => null])

                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                        <a href="{{ route('school-admin.fee-rates.index') }}"
                           class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-5 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>