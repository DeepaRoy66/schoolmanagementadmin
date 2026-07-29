<x-app-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Fee Rates</h1>
            <a href="{{ route('school-admin.fee-rates.create') }}" class="btn btn-primary">+ Add Fee Rate</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left">Fee Name</th>
                    <th class="p-2 text-left">Class</th>
                    <th class="p-2 text-left">Billing Period</th>
                    <th class="p-2 text-right">Amount</th>
                    <th class="p-2 text-center">Active</th>
                    <th class="p-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($feeRates as $rate)
                    <tr class="border-t">
                        <td class="p-2">{{ $rate->feeName->name }}</td>
                        <td class="p-2">{{ $rate->schoolClass->name }}</td>
                        <td class="p-2">{{ $rate->billingPeriod->name ?? 'N/A' }}</td>
                        <td class="p-2 text-right">{{ number_format($rate->amount, 2) }}</td>
                        <td class="p-2 text-center">
                            @if ($rate->is_active)
                                <span class="text-green-600">Yes</span>
                            @else
                                <span class="text-red-600">No</span>
                            @endif
                        </td>
                        <td class="p-2 text-center space-x-2">
                            <a href="{{ route('school-admin.fee-rates.edit', $rate) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('school-admin.fee-rates.destroy', $rate) }}" method="POST" class="inline" onsubmit="return confirm('Delete this fee rate?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">No fee rates found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>