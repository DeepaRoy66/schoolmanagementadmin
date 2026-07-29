@php
    $old = fn($key, $default = null) => old($key, $feeRate?->{$key} ?? $default);
@endphp

<div class="mb-3">
    <label class="block mb-1">Fee Name</label>
    <select name="fee_name_id" class="w-full border rounded p-2">
        <option value="">-- Select --</option>
        @foreach ($feeNames as $feeName)
            <option value="{{ $feeName->id }}" @selected($old('fee_name_id') == $feeName->id)>
                {{ $feeName->name }}
            </option>
        @endforeach
    </select>
    @error('fee_name_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="mb-3">
    <label class="block mb-1">Class</label>
    <select name="class_id" class="w-full border rounded p-2">
        <option value="">-- Select --</option>
        @foreach ($classes as $class)
            <option value="{{ $class->id }}" @selected($old('class_id') == $class->id)>
                {{ $class->name }}
                @if ($class->sections->isNotEmpty())
                    ({{ $class->sections->pluck('name')->join(', ') }})
                @endif
            </option>
        @endforeach
    </select>
    @error('class_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="mb-3">
    <label class="block mb-1">Billing Period</label>
    <select name="billing_period_id" class="w-full border rounded p-2">
        <option value="">-- N/A --</option>
        @foreach ($billingPeriods as $period)
            <option value="{{ $period->id }}" @selected($old('billing_period_id') == $period->id)>
                {{ $period->name }}
            </option>
        @endforeach
    </select>
    @error('billing_period_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="mb-3">
    <label class="block mb-1">Amount</label>
    <input type="number" step="0.01" name="amount" value="{{ $old('amount') }}" class="w-full border rounded p-2">
    @error('amount') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="mb-3">
    <label class="inline-flex items-center">
        <input type="checkbox" name="is_active" value="1" @checked($old('is_active', true))>
        <span class="ml-2">Active</span>
    </label>
</div>