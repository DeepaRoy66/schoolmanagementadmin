<x-app-layout>
    <div class="p-6 max-w-lg">
        <h1 class="text-xl font-semibold mb-4">Add Fee Rate</h1>

        <form action="{{ route('school-admin.fee-rates.store') }}" method="POST">
            @csrf
            @include('school-admin.fee-rates._form', ['feeRate' => null])
            <button type="submit" class="btn btn-primary mt-4">Save</button>
        </form>
    </div>
</x-app-layout>