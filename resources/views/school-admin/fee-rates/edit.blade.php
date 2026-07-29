<x-app-layout>
    <div class="p-6 max-w-lg">
        <h1 class="text-xl font-semibold mb-4">Edit Fee Rate</h1>

        <form action="{{ route('school-admin.fee-rates.update', $feeRate) }}" method="POST">
            @csrf
            @method('PUT')
            @include('school-admin.fee-rates._form', ['feeRate' => $feeRate])
            <button type="submit" class="btn btn-primary mt-4">Update</button>
        </form>
    </div>
</x-app-layout>